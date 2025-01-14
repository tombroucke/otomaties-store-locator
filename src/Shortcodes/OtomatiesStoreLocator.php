<?php

namespace Otomaties\StoreLocator\Shortcodes;

use Illuminate\Support\Str;
use Smappee\Contracts\Shortcode;
use Otomaties\StoreLocator\Models\Store;

class OtomatiesStoreLocator implements Shortcode
{
    const SHORTCODE_NAME = 'otomaties-store-locator';

    /**
     * Shortcode callback
     *
     * @param  array<string, mixed>|string  $atts  The shortcode attributes.
     */
    public function callback(array|string $atts = []): string
    {
        $atts = shortcode_atts( [
            'category' => '',
        ], $atts );

        $categories = Str::of($atts['category'])
            ->replace(' ', '')
            ->explode(',')
            ->map(fn ($category) => get_term_by('slug', $category, 'store_category'))
            ->filter();

        $categories->each(function ($category) use (&$categories) {
            $children = collect(get_terms('store_category', ['parent' => $category->term_id]));

            if ($children->isNotEmpty()) {
                $children->each(function ($child) use (&$categories) {
                    $categories->push($child);
                });

                $categories->forget($categories->search($category));
            }
        });

        if ($categories->isEmpty()) {
            $categories = collect(get_terms('store_category', [
                'hide_empty' => false,
                'parent' => 0
            ]));
        }

        return view('StoreLocator::store-locator', [
            'categories' => $categories,
            'stores' => Store::all(),
        ])->render();
    }
}
