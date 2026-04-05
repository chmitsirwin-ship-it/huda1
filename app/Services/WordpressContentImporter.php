<?php

namespace App\Services;

use App\Models\Khutba;
use App\Models\KhutbaCategory;
use App\Models\Language;
use App\Models\News;
use App\Models\NewsCategory;
use App\Support\AssetPath;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WordpressContentImporter
{
    private const KHUTBA_CATEGORY_SLUGS = [
        'piatkowy-wyklad',
        'ramadan-wyklad',
        'english-khutbahs',
    ];

    public function import(string $type = 'all', bool $dryRun = false, ?int $limit = null): array
    {
        if (! $dryRun) {
            $this->truncateImportedContent($type);
        }

        $summary = [
            'khutba_categories' => $this->importKhutbaCategories($dryRun),
            'news_categories' => $this->importNewsCategories($dryRun),
            'khutbas' => 0,
            'news' => 0,
        ];

        $posts = $this->wordpressPosts($limit);

        if (in_array($type, ['all', 'khutbas'], true)) {
            $summary['khutbas'] = $this->importKhutbas($posts, $dryRun);
        }

        if (in_array($type, ['all', 'news'], true)) {
            $summary['news'] = $this->importNews($posts, $dryRun);
        }

        return $summary;
    }

    public function importKhutbaCategories(bool $dryRun = false): int
    {
        $categories = $this->wordpressCategories()
            ->filter(fn (object $category): bool => in_array($category->slug, self::KHUTBA_CATEGORY_SLUGS, true))
            ->values();

        if ($dryRun) {
            return $categories->count();
        }

        foreach ($categories as $index => $category) {
            KhutbaCategory::query()->create([
                'name' => $this->translations($category->name),
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }

        return $categories->count();
    }

    public function importNewsCategories(bool $dryRun = false): int
    {
        $categories = $this->wordpressCategories()
            ->reject(fn (object $category): bool => in_array($category->slug, self::KHUTBA_CATEGORY_SLUGS, true))
            ->values();

        if ($dryRun) {
            return $categories->count();
        }

        $createdCategories = collect();

        foreach ($categories as $index => $category) {
            $createdCategories->put($category->term_id, NewsCategory::query()->create([
                'name' => $this->translations($category->name),
                'is_active' => true,
                'sort_order' => $index,
            ]));
        }

        foreach ($categories as $category) {
            if (! $category->parent) {
                continue;
            }

            $newsCategory = $createdCategories->get($category->term_id);
            $parentCategory = $createdCategories->get($category->parent);

            if ($newsCategory && $parentCategory) {
                $newsCategory->update(['parent_id' => $parentCategory->getKey()]);
            }
        }

        return $categories->count();
    }

    public function importKhutbas(Collection $posts, bool $dryRun = false): int
    {
        $khutbaPosts = $posts->filter(fn (array $post): bool => $this->isKhutbaPost($post['categories']))->values();

        if ($dryRun) {
            return $khutbaPosts->count();
        }

        $playerAudioMap = $this->playerAudioMap();
        $khutbaCategories = KhutbaCategory::query()->orderBy('id')->get();

        foreach ($khutbaPosts as $post) {
            $cleanContent = $this->sanitizeContent($post['post_content']);

            $khutba = Khutba::query()->create([
                'title' => $this->translations($post['post_title']),
                'slug' => $this->uniqueSlug(Khutba::class, $post['post_name']),
                'topic' => $this->translations($this->topicFromCategories($post['categories'])),
                'summary' => $this->translations($this->excerptFor($post, $cleanContent)),
                'content' => $this->nullableTranslations($cleanContent),
                'speaker' => $this->translations(__('Imported Khutba')),
                'date' => $post['post_date'],
                'audio_url' => $this->audioUrlFor($post, $playerAudioMap),
                'video_url' => $this->videoUrlFromContent($post['post_content']),
                'featured_image' => $this->featuredImageFromContent($post['post_content']),
                'is_published' => true,
            ]);

            $khutba->categories()->sync(
                collect($post['categories'])
                    ->filter(fn (array $category): bool => in_array($category['slug'], self::KHUTBA_CATEGORY_SLUGS, true))
                    ->map(fn (array $category): ?int => $khutbaCategories->first(fn (KhutbaCategory $record): bool => (string) $record->name === $category['name'])?->getKey())
                    ->filter()
                    ->values()
                    ->all(),
            );
        }

        return $khutbaPosts->count();
    }

    public function importNews(Collection $posts, bool $dryRun = false): int
    {
        $newsPosts = $posts->reject(fn (array $post): bool => $this->isKhutbaPost($post['categories']))->values();

        if ($dryRun) {
            return $newsPosts->count();
        }

        $newsCategories = NewsCategory::query()->orderBy('id')->get();

        foreach ($newsPosts as $post) {
            $cleanContent = $this->sanitizeContent($post['post_content']);

            $newsItem = News::query()->create([
                'title' => $this->translations($post['post_title']),
                'slug' => $this->uniqueSlug(News::class, $post['post_name']),
                'excerpt' => $this->nullableTranslations($this->excerptFor($post, $cleanContent)),
                'content' => $this->nullableTranslations($cleanContent),
                'meta_title' => null,
                'meta_description' => null,
                'featured_image' => $this->featuredImageFromContent($post['post_content']),
                'published_at' => $post['post_date'],
                'is_published' => true,
            ]);

            $newsItem->categories()->sync(
                collect($post['categories'])
                    ->reject(fn (array $category): bool => in_array($category['slug'], self::KHUTBA_CATEGORY_SLUGS, true))
                    ->map(fn (array $category): ?int => $newsCategories->first(fn (NewsCategory $record): bool => (string) $record->name === $category['name'])?->getKey())
                    ->filter()
                    ->values()
                    ->all(),
            );
        }

        return $newsPosts->count();
    }

    private function wordpressCategories(): Collection
    {
        return DB::connection('wordpress')
            ->table('wp_terms')
            ->join('wp_term_taxonomy', 'wp_terms.term_id', '=', 'wp_term_taxonomy.term_id')
            ->where('wp_term_taxonomy.taxonomy', 'category')
            ->orderBy('wp_terms.name')
            ->get([
                'wp_terms.term_id',
                'wp_terms.name',
                'wp_terms.slug',
                'wp_term_taxonomy.parent',
            ]);
    }

    private function wordpressPosts(?int $limit = null): Collection
    {
        $posts = DB::connection('wordpress')
            ->table('wp_posts')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->orderBy('post_date')
            ->when($limit, fn ($query) => $query->limit($limit))
            ->get([
                'ID',
                'post_title',
                'post_name',
                'post_date',
                'post_content',
                'post_excerpt',
            ]);

        $categoryRelations = DB::connection('wordpress')
            ->table('wp_term_relationships')
            ->join('wp_term_taxonomy', function ($join): void {
                $join->on('wp_term_relationships.term_taxonomy_id', '=', 'wp_term_taxonomy.term_taxonomy_id')
                    ->where('wp_term_taxonomy.taxonomy', '=', 'category');
            })
            ->join('wp_terms', 'wp_term_taxonomy.term_id', '=', 'wp_terms.term_id')
            ->whereIn('wp_term_relationships.object_id', $posts->pluck('ID'))
            ->get([
                'wp_term_relationships.object_id as post_id',
                'wp_terms.term_id',
                'wp_terms.name',
                'wp_terms.slug',
            ])
            ->groupBy('post_id');

        return $posts->map(function (object $post) use ($categoryRelations): array {
            return [
                'ID' => $post->ID,
                'post_title' => html_entity_decode((string) $post->post_title, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'post_name' => (string) $post->post_name,
                'post_date' => (string) $post->post_date,
                'post_content' => (string) $post->post_content,
                'post_excerpt' => (string) $post->post_excerpt,
                'categories' => collect($categoryRelations->get($post->ID, collect()))
                    ->map(fn (object $category): array => [
                        'term_id' => $category->term_id,
                        'name' => html_entity_decode((string) $category->name, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                        'slug' => (string) $category->slug,
                    ])
                    ->values()
                    ->all(),
            ];
        });
    }

    private function playerAudioMap(): Collection
    {
        return DB::connection('wordpress')
            ->table('wp_postmeta')
            ->join('wp_posts', 'wp_postmeta.post_id', '=', 'wp_posts.ID')
            ->where('wp_posts.post_type', 'audioplayer')
            ->whereIn('wp_postmeta.meta_key', ['_h5ap_plyr', '_ahp_audio-file'])
            ->get(['wp_postmeta.post_id', 'wp_postmeta.meta_key', 'wp_postmeta.meta_value'])
            ->groupBy('post_id')
            ->map(function (Collection $meta): ?string {
                $serialized = $meta->firstWhere('meta_key', '_h5ap_plyr');

                if ($serialized && preg_match('/h5vp_default_audio";s:\d+:"([^"]+)"/', (string) $serialized->meta_value, $matches)) {
                    return AssetPath::normalize($matches[1]);
                }

                return AssetPath::normalize(optional($meta->firstWhere('meta_key', '_ahp_audio-file'))->meta_value);
            });
    }

    private function truncateImportedContent(string $type): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            if (in_array($type, ['all', 'news'], true)) {
                DB::table('news_category_news')->truncate();
                DB::table('news')->truncate();
                DB::table('news_categories')->truncate();
            }

            if (in_array($type, ['all', 'khutbas'], true)) {
                DB::table('khutba_category_khutba')->truncate();
                DB::table('khutbas')->truncate();
                DB::table('khutba_categories')->truncate();
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function isKhutbaPost(array $categories): bool
    {
        return collect($categories)->contains(fn (array $category): bool => in_array($category['slug'], self::KHUTBA_CATEGORY_SLUGS, true));
    }

    private function uniqueSlug(string $modelClass, ?string $preferredSlug): string
    {
        $baseSlug = Str::slug((string) $preferredSlug);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'item';
        $slug = $baseSlug;
        $counter = 2;

        while ($modelClass::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function translations(?string $value): array
    {
        $value = trim((string) $value);

        return collect($this->localeCodes())
            ->mapWithKeys(fn (string $locale): array => [$locale => $value])
            ->all();
    }

    private function nullableTranslations(?string $value): ?array
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return $this->translations($value);
    }

    private function localeCodes(): array
    {
        $codes = Language::query()->pluck('code')->all();
        $codes[] = config('app.locale', 'en');
        $codes[] = config('app.fallback_locale', 'en');

        return array_values(array_unique(array_filter($codes)));
    }

    private function excerptFor(array $post, ?string $cleanContent = null): string
    {
        $excerpt = $this->sanitizePlainText((string) $post['post_excerpt']);

        if ($excerpt !== '') {
            return $excerpt;
        }

        return Str::limit($this->sanitizePlainText($cleanContent ?? (string) $post['post_content']), 320, '...');
    }

    private function topicFromCategories(array $categories): string
    {
        $topic = collect($categories)
            ->filter(fn (array $category): bool => in_array($category['slug'], self::KHUTBA_CATEGORY_SLUGS, true))
            ->pluck('name')
            ->implode(', ');

        return $topic !== '' ? $topic : __('Khutba');
    }

    private function audioUrlFor(array $post, Collection $playerAudioMap): ?string
    {
        if (preg_match('/\[player\s+id=(?:"|\')?(\d+)(?:"|\')?\]/', (string) $post['post_content'], $matches)) {
            return $playerAudioMap->get((int) $matches[1]);
        }

        if (preg_match('/\[audio:([^\]]+)\]/', (string) $post['post_content'], $matches)) {
            return AssetPath::normalize(trim($matches[1]));
        }

        return null;
    }

    private function videoUrlFromContent(?string $content): ?string
    {
        if (preg_match('/https?:\/\/(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/|vimeo\.com\/)[^\s"\']+/', (string) $content, $matches)) {
            return $matches[0];
        }

        if (preg_match('/https?:\/\/[^\s"\']+\.(mp4|mov|webm|avi|mkv)/i', (string) $content, $matches)) {
            return AssetPath::normalize($matches[0]);
        }

        return null;
    }

    private function featuredImageFromContent(?string $content): ?string
    {
        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', (string) $content, $matches)) {
            return AssetPath::normalize($matches[1]);
        }

        return null;
    }

    private function sanitizeContent(?string $content): string
    {
        $content = (string) $content;
        $content = preg_replace('/<!--\s*wp:.*?-->/s', '', $content) ?? $content;
        $content = preg_replace('/<!--\s*\/wp:.*?-->/s', '', $content) ?? $content;
        $content = preg_replace('/\[(?!\/)(?:player|audio|video|playlist|caption|gallery|embed)[^\]]*\]/i', '', $content) ?? $content;
        $content = preg_replace('/\[\/(?:player|audio|video|playlist|caption|gallery|embed)\]/i', '', $content) ?? $content;
        $content = preg_replace('/<(?:br\s*\/?)>/i', "\n", $content) ?? $content;
        $content = preg_replace('/<\/(?:p|div|section|article|li|ul|ol|h[1-6]|figure|blockquote)>/i', "\n", $content) ?? $content;
        $content = strip_tags($content);
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $content = preg_replace('/[\t ]+/u', ' ', $content) ?? $content;
        $content = preg_replace('/\n{3,}/u', "\n\n", $content) ?? $content;

        return trim($content);
    }

    private function sanitizePlainText(?string $content): string
    {
        $content = $this->sanitizeContent($content);
        $content = preg_replace('/\s+/u', ' ', $content) ?? $content;

        return trim($content);
    }
}
