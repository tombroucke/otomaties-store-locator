<?php

namespace Otomaties\StoreLocator\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Otomaties\AcfObjects\Fields\GoogleMap;
use Otomaties\AcfObjects\Facades\AcfObjects;
use Otomaties\StoreLocator\Models\Abstracts\Post;

class Store extends Post
{
    const POST_TYPE = 'store';

    public function location(): GoogleMap
    {
        return AcfObjects::getField('location', $this->getId());
    }

    public function categories() : Collection
    {
        return collect(get_the_terms($this->getId(), 'store_category'))->filter();
    }

    public function category(): ?\WP_Term
    {
        return $this->categories()->first();
    }

    public function marker(): ?string
    {
        $category = $this->category();

        if (! $category) {
            return null;
        }
        
        $categoryMarker = AcfObjects::getField('marker', 'store_category_' . $category->term_id);
        if (! $categoryMarker->isSet()) {
            return null;
        }

        return $categoryMarker->url('medium');
    }

    public function markerContent(): string
    {
        $location = $this->location();
        
        $extraMarkerContent = AcfObjects::getField('extra_marker_content', $this->getId());

        $address = Str::replace(',', '<br>', $location->address());

        return wpautop($address) . wpautop($extraMarkerContent);
    }
}
