<?php

namespace Otomaties\StoreLocator\Fields;

use StoutLogic\AcfBuilder\FieldsBuilder;

class Store extends Abstracts\Field
{
    protected function fields(): FieldsBuilder
    {
        $fieldsBuilder = new FieldsBuilder('store', [
            'title' => __('Store', 'otomaties-store-locator'),
        ]);

        $fieldsBuilder
            ->setLocation('post_type', '==', 'store');

        $fieldsBuilder
            ->addGoogleMap('location', [
				'label' => __('Location', 'otomaties-store-locator'),
				'zoom' => 16,
			])
            ->addTextarea('extra_marker_content', [
                'label' => __('Marker content', 'otomaties-store-locator'),
            ]);

        return $fieldsBuilder;
    }
}
