<?php

namespace Jatdung\MediaManager\Forms;

use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Jatdung\MediaManager\MediaManagerServiceProvider;
use Jatdung\MediaManager\MediaService;

class MoveFile extends Form implements LazyRenderable
{
    use LazyWidget;

    public function handle(array $input)
    {
        if (strcmp($input['path'], $input['old_path']) === 0) {
            goto response;
        }

        $service = new MediaService();
        if (!$service->setDisk($input['disk'])->move($input['old_path'], $input['path'])) {
            return $this->response()->error(MediaManagerServiceProvider::trans('media.move_failed'));
        }

        response:
        return $this->response()
            ->success(MediaManagerServiceProvider::trans('media.move_succeeded'))
            ->refresh();
    }

    public function form()
    {
        $this->text('path')->default($this->payload['path']);
        $this->hidden('old_path')->value($this->payload['path']);
        $this->hidden('disk')->value($this->payload['disk']);
    }
}
