<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\BlockRenderer;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(private BlockRenderer $blockRenderer) {}

    public function home(): View
    {
        $page = Page::with('pageBuilderBlocks')->where('slug', 'home')->where('is_published', true)->firstOrFail();
        $blocks = $this->blockRenderer->render($page);

        return view('pages.home', compact('page', 'blocks'));
    }

    public function show(string $slug): View
    {
        $page = Page::with('pageBuilderBlocks')->where('slug', $slug)->where('is_published', true)->firstOrFail();
        $blocks = $this->blockRenderer->render($page);

        return view('pages.show', compact('page', 'blocks'));
    }
}
