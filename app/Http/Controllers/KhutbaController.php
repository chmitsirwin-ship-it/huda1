<?php

namespace App\Http\Controllers;

use App\Models\Khutba;
use App\Models\KhutbaCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KhutbaController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $categoryId = $request->integer('category');
        $locale = app()->getLocale();
        $query = Khutba::query()->with('categories')->published();

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($locale, $search): void {
                $builder->where("title->{$locale}", 'like', "%{$search}%")
                    ->orWhere("speaker->{$locale}", 'like', "%{$search}%")
                    ->orWhere("topic->{$locale}", 'like', "%{$search}%");
            });
        }

        $activeCategory = null;

        if ($categoryId > 0) {
            $activeCategory = KhutbaCategory::query()
                ->whereKey($categoryId)
                ->where('is_active', true)
                ->firstOrFail();

            $query->whereHas('categories', fn (Builder $builder) => $builder->whereKey($activeCategory->getKey()));
        }

        $khutbas = $query->paginate(15);
        $categories = KhutbaCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('pages.khutba.index', compact('khutbas', 'search', 'categories', 'activeCategory', 'categoryId'));
    }

    public function show(string $slug): View
    {
        $khutba = Khutba::query()
            ->with('categories')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedKhutbas = Khutba::query()
            ->with('categories')
            ->published()
            ->whereKeyNot($khutba->getKey())
            ->when(
                $khutba->categories->isNotEmpty(),
                fn (Builder $builder) => $builder->whereHas(
                    'categories',
                    fn (Builder $categoryQuery) => $categoryQuery->whereIn('khutba_categories.id', $khutba->categories->pluck('id')),
                ),
            )
            ->limit(3)
            ->get();

        return view('pages.khutba.show', compact('khutba', 'relatedKhutbas'));
    }
}
