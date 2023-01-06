<?php

namespace Jatdung\MediaManager\Widgets;

use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Traits\LazyWidget;
use Jatdung\MediaManager\MediaService;

class DetailShow implements LazyRenderable
{
    use LazyWidget;

    public function render()
    {
        $disk = $this->payload['disk'];
        $path = $this->payload['path'];

        $service = new MediaService();
        $attributes = $service->setDisk($disk)->metadata($path);

        return view('jatdung.media-manager::_detail', ['attributes' => $attributes]);
    }
}