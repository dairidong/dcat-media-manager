<?php

namespace Jatdung\MediaManager\Files;

class Directory extends File
{
    public function preview()
    {
        $url = admin_route('media.index', [
            'disk' => $this->manager()->disk(),
            'path' => $this->path(),
        ]);

        return "<a href=\"{$url}\"><span class=\"file-icon text-aqua\"><i class=\"fa fa-folder\"></i></span></a>";
    }

    public function renderName()
    {
        $url = admin_route('media.index', [
            'disk' => $this->manager()->disk(),
            'path' => $this->path(),
        ]);

        $parentRender = parent::renderName();

        return "<a href=\"{$url}\">{$parentRender}</a>";
    }
}
