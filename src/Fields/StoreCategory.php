<?php

namespace Otomaties\StoreLocator\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class StoreCategory extends Abstracts\Field
{
    protected function fields(): FieldsBuilder
    {
        $fieldsBuilder = new FieldsBuilder('store_category', [
            'title' => __('Category', 'otomaties-store-locator'),
        ]);

        $fieldsBuilder
            ->setLocation('taxonomy', '==', 'store_category');

        $fieldsBuilder
            ->addImage('marker', [
                'label' => __('Marker', 'otomaties-store-locator'),
            ]);

        return $fieldsBuilder;
    }
}
