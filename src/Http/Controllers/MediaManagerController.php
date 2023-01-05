<?php

namespace Jatdung\MediaManager\Http\Controllers;

use Dcat\Admin\Actions\Response;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Content;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jatdung\MediaManager\MediaManager;
use Jatdung\MediaManager\MediaManagerServiceProvider;
use Jatdung\MediaManager\MediaService;

class MediaManagerController extends Controller
{
    /**
     * @var Response
     */
    protected $response;

    public function index(Content $content, Request $request)
    {
        Admin::requireAssets('@jatdung.media-manager');

        $path = $request->input('path') ?: '/';
        $disk = $request->input('disk') ?: '';

        if ($request->has('view')) {
            $view = $request->input('view');
        } elseif ($request->hasCookie('view')) {
            $view = $request->cookie('view');
        } else {
            $view = 'table';
        }

        $service = $this->service()->setDisk($disk)->setPath($path);
        $manager = $this->manager($service)->enableView($view);

        $content->title(MediaManagerServiceProvider::trans('media.title'))
            ->description(MediaManagerServiceProvider::trans('media.description'))
            ->body($manager);

        return response($content)->cookie('view', $view);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|string',
            'disk' => 'required|string',
        ]);

        try {
            $this->service()
                ->setDisk($validated['disk'])
                ->delete($validated['file']);
        } catch (Exception $e) {
            return $this->response()->error($e->getMessage());
        }

        return $this->response()->success(trans('admin.delete_succeeded'))->refresh();
    }

    public function batchDestroy(Request $request)
    {
        $validated = $request->validate([
            'path' => 'required|string',
            'disk' => 'required|string',
            'files' => 'array'
        ]);

        $path = $validated['path'];
        $disk = $validated['disk'];
        $files = $validated['files'];
        if (empty($files)) {
            goto response;
        }

        try {
            $this->service()
                ->setDisk($disk)
                ->setPath($path)
                ->batchDelete($files);
        } catch (Exception $e) {
            return $this->response()->error($e->getMessage());
        }

        response:
        return $this->response()->success(trans('admin.delete_succeeded'))->refresh();
    }

    protected function service()
    {
        return new MediaService();
    }

    protected function manager(MediaService $service)
    {
        return new MediaManager($service);
    }

    protected function response()
    {
        if (is_null($this->response)) {
            $this->response = new Response();
        }

        return $this->response;
    }
}
