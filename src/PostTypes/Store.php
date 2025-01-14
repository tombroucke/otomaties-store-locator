<?php

namespace Otomaties\StoreLocator\PostTypes;

use ExtCPTs\PostType as ExtCPTsPostType;
use Otomaties\StoreLocator\Helpers\Labels;
use Otomaties\StoreLocator\PostTypes\Contracts\PostType;
use Otomaties\StoreLocator\Exceptions\ExtendedCptsNotInstalledException;

class Store implements PostType
{
    const POST_TYPE = 'store';

    public function register() : ExtCPTsPostType
    {
        if (!function_exists('register_extended_post_type')) {
            throw new ExtendedCptsNotInstalledException();
        }

        $postSingularName = __('Store', 'otomaties-store-locator');
        $postPluralName = __('Stores', 'otomaties-store-locator');

        $args = [
            'show_in_feed' => false,
            'show_in_rest' => false,
            'has_archive' => false,
            'publicly_queryable' => false,
            'menu_icon' => 'dashicons-store',
            'labels' => Labels::postType($postSingularName, $postPluralName),
            'dashboard_activity' => true,
            'supports' => ['title'],
            'admin_cols' => [
                'store_category' => [
                    'title'          => __('Category', 'otomaties-store-locator'),
                    'taxonomy'      => 'store_category',
                ],
            ],
            'admin_filters' => [
                'store_category' => [
                    'taxonomy' => 'store_category',
                ],
            ],
        ];

        $names = [
            'singular' => $postSingularName,
            'plural'   => $postPluralName,
            'slug'     => self::POST_TYPE,
        ];

        return register_extended_post_type(self::POST_TYPE, $args, $names);
    }
}
