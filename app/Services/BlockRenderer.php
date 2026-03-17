<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\HtmlString;

class BlockRenderer
{
    public function render(Page $page): HtmlString
    {
        $html = '';

        foreach ($page->pageBuilderBlocks as $block) {
            $view = $block->block_type::getView();

            if (! $view) {
                continue;
            }

            $data = $block->block_type::formatForSingleView($block->data ?? []);

            try {
                $html .= view($view, ['data' => $data])->render();
            } catch (\Exception) {
            }
        }

        return new HtmlString($html);
    }
}
