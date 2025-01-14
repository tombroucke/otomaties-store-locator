<?php

namespace Otomaties\StoreLocator\PostTypes\Contracts;

use ExtCPTs\PostType as ExtCPTsPostType;

interface PostType
{
    public function register() : ExtCPTsPostType;
}
