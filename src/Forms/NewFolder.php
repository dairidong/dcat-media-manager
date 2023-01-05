<?php

namespace Jatdung\MediaManager\Forms;

use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Jatdung\MediaManager\MediaManagerServiceProvider;
use Jatdung\MediaManager\MediaService;

class NewFolder extends Form implements LazyRenderable
{
    use LazyWidget;

    public function handle(array $input)
    {
        $service = new MediaService($this->payload['path'], $this->payload['disk']);

        if ($service->newFolder($input['folder_name'])) {
            return $this->response()->success(MediaManagerServiceProvider::trans('media.new_folder_succeeded'))->refresh();
        } else {
            return $this->response()->error(MediaManagerServiceProvider::trans('media.new_folder_failed'))->refresh();
        }
    }

    public function form()
    {
        $this->text('folder_name', MediaManagerServiceProvider::trans('media.folder_name'))->rules('required')->required();
    }
}
