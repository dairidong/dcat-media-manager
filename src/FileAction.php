<?php

namespace Jatdung\MediaManager;

use Jatdung\MediaManager\Files\File;
use Jatdung\MediaManager\Views\View;

abstract class FileAction extends ManagerAction
{
    /**
     * @var File
     */
    protected $file;

    /**
     * @var array
     */
    protected $iconClasses = [];

    /**
     * Get primary key value of current file.
     *
     * @return mixed
     */
    public function getKey()
    {
        if ($this->file) {
            return $this->file->path();
        }

        return parent::getKey();
    }

    /**
     * @param File $file
     * @return mixed
     */
    public function setFile(File $file)
    {
        $this->file = $file;

        return $this;
    }

    public function icon()
    {
        return $this->iconClasses;
    }


    public function html()
    {
        /** @var View $view */
        $view = $this->manager()->enabledView(true);

        return $view->renderAction($this);
    }
}
