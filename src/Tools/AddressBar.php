<?php

namespace Jatdung\MediaManager\Tools;

use Jatdung\MediaManager\ManagerAction;

class AddressBar extends ManagerAction
{
    protected $htmlClasses = ['input-group', 'goto-url'];

    protected $buttonClasses = ['btn', 'btn-primary'];

    protected function html()
    {
        $path = $this->renderPath();

        return <<<HTML
<div {$this->formatHtmlAttributes()}>
    <input type="text" name="path" class="form-control" value="$path" />

    <div class="input-group-append">
        <button type="submit" class="{$this->formatButtonClasses()}">
            <i class="fa fa-arrow-right"></i>
        </button>
    </div>
</div>
HTML;
    }

    protected function renderPath()
    {
        return htmlentities('/' . trim($this->manager()->path(), '/'));
    }

    protected function formatButtonClasses()
    {
        return implode(' ', array_unique($this->buttonClasses));
    }
}
