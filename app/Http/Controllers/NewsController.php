<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $categoryId = $request->integer('category');
        $locale = app()->getLocale();

        $query = News::query()
            ->with('categories')
            ->published();

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($locale, $search): void {
                $builder
                    ->where("title->{$locale}", 'like', "%{$search}%")
                    ->orWhere("excerpt->{$locale}", 'like', "%{$search}%")
                    ->orWhere("content->{$locale}", 'like', "%{$search}%");
            });
        }

        $activeCategory = null;

        if ($categoryId > 0) {
            $activeCategory = NewsCategory::query()
                ->whereKey($categoryId)
                ->where('is_active', true)
                ->firstOrFail();

            $query->whereHas('categories', fn (Builder $builder) => $builder->whereKey($activeCategory->getKey()));
        }

        $newsItems = $query->paginate(12)->withQueryString();

        $categories = NewsCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('pages.news.index', compact('newsItems', 'categories', 'activeCategory', 'categoryId', 'search'));
    }

    public function show(string $slug): View
    {
        $newsItem = News::query()
            ->with('categories')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedNews = News::query()
            ->with('categories')
            ->published()
            ->whereKeyNot($newsItem->getKey())
            ->when(
                $newsItem->categories->isNotEmpty(),
                fn (Builder $builder) => $builder->whereHas(
                    'categories',
                    fn (Builder $categoryQuery) => $categoryQuery->whereIn('news_categories.id', $newsItem->categories->pluck('id')),
                ),
            )
            ->limit(3)
            ->get();

        return view('pages.news.show', compact('newsItem', 'relatedNews'));
    }
}
