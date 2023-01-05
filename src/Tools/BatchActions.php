<?php

namespace Jatdung\MediaManager\Tools;

use Dcat\Admin\Admin;
use Dcat\Admin\Traits\HasVariables;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Jatdung\MediaManager\AbstractTool;
use Jatdung\MediaManager\BatchAction;

class BatchActions extends AbstractTool
{
    use Macroable;
    use HasVariables;

    protected $view = 'jatdung.media-manager::_batch-actions';

    /**
     * @var Collection
     */
    protected $actions;

    /**
     * @var bool
     */
    protected $enableDelete = true;


    /**
     * BatchActions constructor.
     */
    public function __construct()
    {
        $this->actions = new Collection();

        $this->appendDefaultAction();
    }

    /**
     * Append default action(batch delete action).
     *
     * return void
     */
    protected function appendDefaultAction()
    {
        $this->add(new BatchDelete(trans('admin.delete')), '_delete_');
    }

    /**
     * Disable delete.
     *
     * @return $this
     */
    public function disableDelete(bool $disable = true)
    {
        $this->enableDelete = !$disable;

        return $this;
    }

    public function divider()
    {
        return $this->add(new ActionDivider());
    }

    /**
     * Add a batch action.
     *
     * @param BatchAction $action
     * @param  ?string $key
     * @return $this
     */
    public function add(BatchAction $action, ?string $key = null)
    {
        if ($key) {
            $this->actions->put($key, $action);
        } else {
            $this->actions->push($action);
        }

        return $this;
    }

    /**
     * Prepare batch actions.
     *
     * @return void
     */
    protected function prepareActions()
    {
        /** @var BatchAction $action */
        foreach ($this->actions as $action) {
            $action->setManager($this->manager());
        }
    }

    protected function defaultVariables()
    {
        $manager = $this->manager();
        return [
            'actions' => $this->actions,
            'selectAllName' => $manager->fileSelector()->selectAllSelector(),
            'manager' => $manager,
        ];
    }

    /**
     * Render BatchActions button groups.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->enableDelete) {
            $this->actions->forget('_delete_');
        }

        if ($this->actions->isEmpty()) {
            return '';
        }

        $this->prepareActions();

        return Admin::view($this->view, $this->variables());
    }
}
