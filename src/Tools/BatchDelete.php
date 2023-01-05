<?php

namespace Jatdung\MediaManager\Tools;

use Jatdung\MediaManager\BatchAction;

class BatchDelete extends BatchAction
{
    protected $selector = 'file-batch-delete';

    public function __construct($title)
    {
        $this->title = $title;
    }

    public function render()
    {
        $redirect = request()->fullUrl();
        $url = admin_route('media.batch-destroy');
        $disk = htmlspecialchars($this->manager()->disk());
        $path = htmlspecialchars($this->manager()->path());

        return <<<HTML
    <a  class="file-batch-delete"
        data-disk="{$disk}"
        data-path="{$path}"
        data-redirect="{$redirect}"
        data-url="{$url}"><i class="feather icon-trash"></i> {$this->title}</a>
    HTML;
    }
}
