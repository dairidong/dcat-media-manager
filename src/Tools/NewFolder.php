<?php

namespace Jatdung\MediaManager\Tools;

use Dcat\Admin\Widgets\Modal;
use Jatdung\MediaManager\AbstractTool;
use Jatdung\MediaManager\Forms\NewFolder as NewFolderForm;
use Jatdung\MediaManager\MediaManagerServiceProvider;

class NewFolder extends AbstractTool
{
    protected $htmlClasses = ['btn', 'btn-white', 'new-folder-btn'];

    public function html()
    {
        $title = trans('admin.new_folder');

        if (!$supportNewFolder = $this->manager()->isSupportNewFolder()) {
            // 去除默认的 style 样式 cursor: pointer
            $this->forgetHtmlAttribute('style')
                ->appendHtmlAttribute('class', 'disabled')
                ->setHtmlAttribute(
                    'onclick',
                    'Dcat.swal.warning("' . MediaManagerServiceProvider::trans('media.not_support_new_folder') . '");'
                );
        }

        $button = <<<HTML
<button {$this->formatHtmlAttributes()}>
    <i class="feather icon-folder"></i> <span class="d-none d-sm-inline">&nbsp;{$title}</span>
</button>
HTML;

        if ($supportNewFolder) {
            $button = Modal::make()
                ->centered()
                ->lg()
                ->title($title)
                ->body(NewFolderForm::make()->payload([
                    'disk' => $this->manager()->disk(),
                    'path' => $this->manager()->path(),
                ]))
                ->button($button);
        }

        return $button;
    }
}
