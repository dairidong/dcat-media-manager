<?php

namespace Jatdung\MediaManager\Concerns;

use Dcat\Admin\Support\Helper;
use Illuminate\Support\Collection;
use Jatdung\MediaManager\FileAction;

trait HasActions
{
    /**
     * @var Collection
     */
    protected $actions;

    protected function setUpDefaultActions()
    {
        $this->actions = new Collection();
    }

    public function appendAction(FileAction $action)
    {
        $this->actions->push($action->setFile($this)->setManager($this->manager()));
    }

    public function renderActions()
    {
        return $this->actions->map(function (FileAction $action) {
            if (!$action->allowed()) {
                return;
            }

            return Helper::render($action);
        })->implode(' ');
    }
}
