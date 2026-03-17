<?php

namespace App\Http\Controllers;

use App\Enums\MediaType;
use App\Models\MediaItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $collection = $request->get('collection');
        $query = MediaItem::images();

        if ($collection) {
            $query->byCollection($collection);
        }

        $items = $query->paginate(24);
        $collections = MediaItem::query()
            ->where('type', MediaType::Image)
            ->whereNotNull('collection')
            ->distinct()
            ->pluck('collection')
            ->filter()
            ->values();

        return view('pages.gallery.index', compact('items', 'collections', 'collection'));
    }
}
