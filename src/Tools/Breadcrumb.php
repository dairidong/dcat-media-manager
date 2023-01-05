<?php

namespace Jatdung\MediaManager\Tools;

use Jatdung\MediaManager\ManagerAction;

class Breadcrumb extends ManagerAction
{
    protected $htmlClasses = ['breadcrumb', 'mb-1'];

    public function html()
    {
        $folders = explode('/', $this->manager()->path());

        $folders = array_filter($folders);

        $path = '';

        $rootUrl = admin_route('media.index', ['disk' => $this->manager()->disk()]);
        $content = <<<HTML
<ol {$this->formatHtmlAttributes()}>
    <li class="breadcrumb-item">
        <a href="$rootUrl"><i class="fa fa-th-large"></i></a>
    </li>
HTML;

        foreach ($folders as $folder) {
            $path = rtrim($path, '/') . '/' . $folder;
            $url = admin_route('media.index', ['disk' => $this->manager()->disk(), 'path' => $path]);

            $content .= <<<HTML
<li class="breadcrumb-item"><a href="$url"> {$folder}</a></li>
HTML;
        }

        $content .= '</ol>';

        return $content;
    }

    public function render()
    {
        if (!$this->allowed()) {
            return '';
        }

        $this->setUpHtmlAttributes();

        return $this->html();
    }
}
