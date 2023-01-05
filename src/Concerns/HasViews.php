<?php

namespace Jatdung\MediaManager\Concerns;

use Illuminate\Support\Arr;
use Jatdung\MediaManager\Views\Spread;
use Jatdung\MediaManager\Views\Table;
use Jatdung\MediaManager\Views\View;

trait HasViews
{
    /**
     * @var array<string, View>
     */
    protected $panelViews = [];

    /**
     * @var string
     */
    protected $enabledView;

    protected function setUpDefaultViews()
    {
        $this->registerView(new Table())
            ->registerView(new Spread());
    }

    public function registerView(View $view)
    {
        $view->setManager($this->manager());
        $this->panelViews[$view->name()] = $view;

        return $this;
    }

    public function views()
    {
        return $this->panelViews;
    }

    public function enableView(string $name)
    {
        if (array_key_exists($name, $this->panelViews)) {
            $this->enabledView = $name;
        }

        return $this;
    }

    public function enabledView(bool $instance = false)
    {
        $enabledView = $this->enabledView ?: array_key_first($this->panelViews);

        return $instance ? $this->panelViews[$enabledView] : $enabledView;
    }

    public function renderView()
    {
        if ($this->enabledView) {
            $view = $this->panelViews[$this->enabledView];
        } else {
            $view = Arr::first($this->panelViews);
        }

        return $view->render();
    }
}
