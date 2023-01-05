<?php

namespace Jatdung\MediaManager\Tools;

use Jatdung\MediaManager\AbstractTool;

class SwitchPanelView extends AbstractTool
{
    public function render()
    {
        if (!$this->allowed()) {
            return '';
        }

        $content = '<div class="btn-group switch-view" role="group">';

        $views = $this->manager()->views();
        foreach ($views as $view) {
            $route = admin_route('media.index', [
                'view' => $view->name(),
                'path' => $this->manager()->path(),
                'disk' => $this->manager()->disk(),
            ]);
            $icon = $view->icon();

            $btnClass = $this->manager()->enabledView() === $view->name() ? 'btn-primary active' : 'btn-white';

            $content .= <<<HTML
<a href="$route" class="btn {$btnClass}"><i class="fa {$icon}"></i></a>
HTML;
        }

        $content .= '</div>';

        return $content;
    }
}
