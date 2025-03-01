<?php

namespace Otomaties\StoreLocator\Helpers;

class Assets
{
    public function __construct(
        private string $path
    ) {
    }

    public function entrypoints()
    {
        $path = fn ($endpoint) => join("/", [$this->path, $endpoint]);
        $read = fn ($endpoint) => file_get_contents($path($endpoint));
        return json_decode($read('manifest.json'));
    }

    /**
     * Get the value of path
     *
     * @param string $endpoint E.g. checkout.scripts
     * @return void
     */
    public function url($endpoint)
    {
        return join("/", [plugin_dir_url(dirname(__FILE__, 2)), 'public/build', $endpoint]);
    }
}
