<?php

namespace Otomaties\StoreLocator\Modules;

use Otomaties\StoreLocator\Helpers\Assets;
use Otomaties\StoreLocator\Modules\Abstracts\Module;

class Frontend extends Module
{
    public function init()
    {
        $this->loader->addAction('wp_enqueue_scripts', $this, 'enqueueScripts');
    }

    public function enqueueScripts()
    {
        if (!has_shortcode(get_the_content(), 'otomaties-store-locator') && apply_filters('otomaties/store-locator/enqueue_scripts', false) === false) {
            return;
        }

        wp_enqueue_script('google-maps', '//maps.google.com/maps/api/js?key=' . getenv('GOOGLE_MAPS_KEY'), [], null, true); // phpcs:ignore Generic.Files.LineLength.TooLong

        $js = app()->make(Assets::class)->entrypoints()->{'resources/js/app.js'};
        wp_enqueue_script('otomaties-store-locator-app', app()->make(Assets::class)->url($js->file), ['google-maps'], null, true);

        $css = app()->make(Assets::class)->entrypoints()->{'resources/scss/app.scss'};
        wp_enqueue_style('otomaties-store-locator-app', app()->make(Assets::class)->url($css->file), [], null);
    }
}
