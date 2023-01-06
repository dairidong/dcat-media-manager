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
            'data-disk' => $this->manager()->disk(),
            'data-path' => $this->file->path(),
        ]);
    }

    public function title()
    {
        return trans(MediaManagerServiceProvider::trans('media.detail'));
    }
}
