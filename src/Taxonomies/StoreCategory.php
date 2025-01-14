<?php

namespace Otomaties\StoreLocator\Taxonomies;

use ExtCPTs\Taxonomy as ExtCPTsTaxonomy;
use Otomaties\StoreLocator\Helpers\Labels;
use Otomaties\StoreLocator\Taxonomies\Contracts\Taxonomy;
use Otomaties\StoreLocator\Exceptions\ExtendedCptsNotInstalledException;

class StoreCategory implements Taxonomy
{
    const TAXONOMY = 'store_category';
    const POST_TYPE = 'store';

    public function register() : ExtCPTsTaxonomy
    {
        if (!function_exists('register_extended_taxonomy')) {
            throw new ExtendedCptsNotInstalledException();
        }

        $taxonomySingularName = __('Category', 'otomaties-store-locator');
        $taxonomyPluralName = __('Categories', 'otomaties-store-locator');

        $args = [
            'meta_box' => 'radio', // can be null, 'simple', 'radio', 'dropdown'
            'exclusive' => false, // true means: just one can be selected; only for simple
            'labels' => Labels::taxonomy($taxonomySingularName, $taxonomyPluralName),
            'admin_cols' => [
                'updated' => [
                    'title_cb'    => function () {
                        return '<em>Last</em> Updated';
                    },
                    'meta_key'    => 'updated_date',
                    'date_format' => 'd/m/Y',
                ],
            ],
        ];

        $names = [
            'plural' => $taxonomyPluralName,
        ];

        return register_extended_taxonomy(self::TAXONOMY, self::POST_TYPE, $args, $names);
    }
}
