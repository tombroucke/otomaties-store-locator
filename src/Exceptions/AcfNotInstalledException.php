<?php

namespace Otomaties\StoreLocator\Exceptions;

use Exception;

class AcfNotInstalledException extends Exception
{
    public function __construct()
    {
        parent::__construct('ACF is not installed');
    }
}
