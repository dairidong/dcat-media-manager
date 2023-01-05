<?php

namespace Jatdung\MediaManager;

use Dcat\Admin\Actions\Action;
use Dcat\Admin\Support\Helper;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Jatdung\MediaManager\Tools\NewFolder;
use Jatdung\MediaManager\Tools\RefreshButton;
use Jatdung\MediaManager\Tools\SwitchPanelView;
use Jatdung\MediaManager\Tools\UploadFile;

class Tools implements Renderable
{
    use Macroable;

    /**
     * Collection of tools.
     *
     * @var Collection
     */
    protected $tools;

    /**
     * Create a new Tools instance.
     *
     * @param  MediaManager  $manager
     */
    public function __construct(protected MediaManager $manager)
    {
        $this->tools = new Collection();
    }

    // protected function makeBatchActions()
    // {
    //     $class = $this->grid->option('batch_actions_class') ?: (config('admin.grid.batch_action_class') ?: BatchActions::class);
    //
    //     return new $class();
    // }

    /**
     * Append tools.
     *
     * @param  Action|string|\Closure|Renderable|Htmlable  $tool
     * @return $this
     */
    public function append($tool)
    {
        $this->prepareAction($tool);

        $this->tools->push($tool);

        return $this;
    }

    /**
     * Prepend a tool.
     *
     * @param  Action|string|\Closure|Renderable|Htmlable  $tool
     * @return $this
     */
    public function prepend($tool)
    {
        $this->prepareAction($tool);

        $this->tools->prepend($tool);

        return $this;
    }

    /**
     * @param  mixed  $tool
     * @return void
     */
    protected function prepareAction($tool)
    {
        if ($tool instanceof ManagerAction) {
            $tool->setManager($this->manager);
        }
    }

    /**
     * @return bool
     */
    public function has()
    {
        return !$this->tools->isEmpty();
    }

    /**
     * Disable refresh button.
     *
     * @return void
     */
    public function disableRefreshButton(bool $disable = true)
    {
        $this->tools = $this->tools->map(function ($tool) use ($disable) {
            if ($tool instanceof RefreshButton) {
                return $tool->display(!$disable);
            }

            return $tool;
        });
    }

    public function disableUploadFileButton(bool $disable)
    {
        $this->tools->transform(function ($tool) use ($disable) {
            if ($tool instanceof UploadFile) {
                return $tool->disable($disable);
            }

            return $tool;
        });
    }

    public function disableNewFolderButton(bool $disable)
    {
        $this->tools->transform(function ($tool) use ($disable) {
            if ($tool instanceof NewFolder) {
                return $tool->disable($disable);
            }

            return $tool;
        });
    }

    public function disableSwitchViewButton(bool $disable)
    {
        $this->tools->transform(function ($tool) use ($disable) {
            if ($tool instanceof SwitchPanelView) {
                return $tool->disable($disable);
            }

            return $tool;
        });
    }

    // /**
    //  * Disable batch actions.
    //  *
    //  * @return void
    //  */
    // public function disableBatchActions(bool $disable = true)
    // {
    //     $this->tools = $this->tools->map(function ($tool) use ($disable) {
    //         if ($tool instanceof BatchActions) {
    //             return $tool->disable($disable);
    //         }
    //
    //         return $tool;
    //     });
    // }

    // /**
    //  * @param  \Closure|BatchAction|BatchAction[]  $value
    //  */
    // public function batch($value)
    // {
    //     /* @var BatchActions $batchActions */
    //     $batchActions = $this->tools->first(function ($tool) {
    //         return $tool instanceof BatchActions;
    //     });
    //
    //     if ($value instanceof \Closure) {
    //         $value($batchActions);
    //
    //         return;
    //     }
    //
    //     if (! is_array($value)) {
    //         $value = [$value];
    //     }
    //
    //     foreach ($value as $action) {
    //         $batchActions->add($action);
    //     }
    // }

    /**
     * Render header tools bar.
     *
     * @return string
     */
    public function render()
    {
        return $this->tools->map(function ($tool) {
            if ($tool instanceof Action && !$tool->allowed()) {
                return;
            }

            return Helper::render($tool);
        })->implode(' ');
    }
}
