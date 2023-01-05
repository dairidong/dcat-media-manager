<?php

namespace Jatdung\MediaManager\Views;

use Dcat\Admin\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Jatdung\MediaManager\Concerns\HasManager;

abstract class View implements Renderable
{
    use HasManager;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $view;

    public function render()
    {
        return view($this->view, [
            'manager' => $this->manager(),
        ]);
    }

    public function icon()
    {
        return $this->icon;
    }

    public function view()
    {
        return $this->view;
    }

    /**
     * @return string
     */
    public function name()
    {
        if ($this->name) {
            return $this->name;
        }

        $this->name = mb_strtolower(class_basename($this));
        return $this->name;
    }

    /**
     * @return Renderable|string|Htmlable
     */
    abstract public function renderAction(Action $action);

    /**
     * @param string $html
     * @return mixed
     */
    abstract public function renderFileSelector(string $html);
}
