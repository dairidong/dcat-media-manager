<?php

namespace Jatdung\MediaManager;

use Dcat\Admin\Extend\ServiceProvider;

class MediaManagerServiceProvider extends ServiceProvider
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
