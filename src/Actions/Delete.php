<?php

namespace Jatdung\MediaManager\Actions;

use Jatdung\MediaManager\FileAction;

class Delete extends FileAction
{
    protected $iconClasses = ['fa', 'fa-trash'];

    protected function setUpHtmlAttributes()
    {
        parent::setUpHtmlAttributes();

        $filePath = $this->file->path();
        $this->setHtmlAttribute([
            'data-action' => 'delete',
            'data-message' => $filePath,
            'data-url' => admin_route('media.destroy', [
                'disk' => $this->manager()->disk(),
                'file' => $filePath,
            ]),
        ]);
    }

    public function title()
    {
        return trans('admin.delete');
    }
}
