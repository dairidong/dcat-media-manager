<?php

namespace Jatdung\MediaManager\Actions;

use Jatdung\MediaManager\FileAction;
use Jatdung\MediaManager\MediaManagerServiceProvider;

class Move extends FileAction
{
    protected $htmlClasses = ['file-move-btn'];

    protected $iconClasses = ['fa', 'fa-edit'];

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
        return sprintf(
            '%s & %s',
            MediaManagerServiceProvider::trans('media.rename'),
            MediaManagerServiceProvider::trans('media.move')
        );
    }
}
