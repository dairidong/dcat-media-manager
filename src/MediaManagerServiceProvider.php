<?php

namespace Jatdung\MediaManager;

use Dcat\Admin\Extend\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class MediaManagerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    protected $js = [
        'js/index.js',
        'js/clipboard.min.js'
    ];

    protected $css = [
        'css/index.css',
    ];

    protected $menu = [
        [
            'title' => 'Media Manager',
            'uri' => 'media',
            'icon' => 'fa-folder-open',
        ],
    ];
}
