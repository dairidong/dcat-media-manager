<?php

namespace Jatdung\MediaManager;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;

class MediaManagerServiceProvider extends ServiceProvider
{
    protected $js = [
        'js/index.js',
    ];
    protected $css = [
        'css/index.css',
    ];

    protected $menu = [
        [
            'title' => 'Media Manager',
            'uri'   => 'media',
            'icon'  => 'fa-folder-open'
        ],
    ];
}
