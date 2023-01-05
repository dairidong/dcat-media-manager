<?php

namespace Jatdung\MediaManager\Concerns;

use Jatdung\MediaManager\Views\View;
use Jatdung\MediaManager\Widgets\Panel;

trait HasPanel
{
    /**
     * @var Panel
     */
    protected $panel;

    protected function setUpPanel()
    {
        $this->panel = Panel::make($this);
    }

    public function registerView(string $name, View $view)
    {
        $this->panel->registerView($name, $view);

        return $this;
    }

    public function enableView(string $name)
    {
        $this->panel->enableView($name);

        return $this;
    }

    public function enabledView(bool $instance = false)
    {
        return $this->panel->enabledView($instance);
    }

    public function views()
    {
        return $this->panel->views();
    }

    public function disableBreadcrumb(bool $disable = true)
    {
        $this->panel->disableBreadcrumb($disable);

        return $this;
    }

    public function panel()
    {
        return $this->panel;
    }

    public function renderPanel()
    {
        return $this->panel->render();
    }
}
