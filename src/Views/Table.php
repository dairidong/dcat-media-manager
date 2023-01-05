<?php

namespace Jatdung\MediaManager\Views;

use Dcat\Admin\Actions\Action;

class Table extends View
{
    protected $icon = 'fa-list';

    protected $name = 'table';

    protected $view = 'jatdung.media-manager::_table';

    public function renderAction(Action $action)
    {
        $iconClasses = 'feather icon-circle';

        if (method_exists($action, 'icon')) {
            $iconClasses = $action->icon() ?: $iconClasses;
        }

        if (is_array($iconClasses)) {
            $iconClasses = implode(' ', array_unique($iconClasses));
        }

        $action->appendHtmlAttribute('class', 'btn btn-default')
            ->appendHtmlAttribute('title', $action->title());
        return "<a {$action->formatHtmlAttributes()}><i class='{$iconClasses}'></i></a>";
    }

    public function renderFileSelector(string $html)
    {
        return <<<HTML
<td class="pt-1">
    {$html}
</td>
HTML;

    }
}
