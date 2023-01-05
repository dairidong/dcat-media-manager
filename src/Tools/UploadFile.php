<?php

namespace Jatdung\MediaManager\Tools;

use Dcat\Admin\Widgets\Modal;
use Jatdung\MediaManager\AbstractTool;
use Jatdung\MediaManager\Forms\UploadFile as UploadFileForm;

class UploadFile extends AbstractTool
{
    public function html()
    {
        return Modal::make()
            ->centered()
            ->lg()
            ->title($title = trans('admin.upload'))
            ->body(UploadFileForm::make()->payload([
                'disk' => $this->manager()->disk(),
                'path' => $this->manager()->path(),
            ]))
            ->button("<button class='btn btn-primary'><i class=\"feather icon-upload\"></i><span class='d-none d-sm-inline'> &nbsp;{$title}</span></button>");
    }
}
