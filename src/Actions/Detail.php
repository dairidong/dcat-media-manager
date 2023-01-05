<?php

namespace Jatdung\MediaManager\Actions;

use Jatdung\MediaManager\FileAction;
use Jatdung\MediaManager\Files\Directory;
use Jatdung\MediaManager\MediaManagerServiceProvider;

class Detail extends FileAction
{
    protected $htmlClasses = ['file-detail-btn'];

    protected $iconClasses = ['feather', 'icon-link'];

    protected function setUpHtmlAttributes()
    {
        parent::setUpHtmlAttributes();

        $this->setHtmlAttribute([
            'data-type' => $this->file instanceof Directory ? 'directory' : 'file',
            'data-name' => $this->file->name(),
            'data-path' => $this->file->path(),
            'data-file-size' => $this->file->fileSize(),
            'data-last-modified' => $this->file->lastModified(),
            'data-url' => $this->manager()->service()->url($this->file->path()),
        ]);
    }

    public function title()
    {
        return trans(MediaManagerServiceProvider::trans('media.detail'));
    }
}
