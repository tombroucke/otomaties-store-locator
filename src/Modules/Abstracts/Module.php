<?php

namespace Otomaties\StoreLocator\Modules\Abstracts;

use Otomaties\StoreLocator\Helpers\Loader;
use Otomaties\StoreLocator\Helpers\Assets;

abstract class Module
{
    public function __construct(
        protected Loader $loader,
        protected Assets $assets,
    ) {
    }

    abstract public function init();
}
