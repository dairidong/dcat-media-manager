<?php

namespace Jatdung\MediaManager;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Local\LocalFilesystemAdapter;

class MediaManager
{
    /**
     * @var string
     */
    protected $path = '/';

    /**
     * @var string
     */
    protected $disk = '';

    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    protected $storage;

    /**
     * List of allowed extensions.
     *
     * @var string|array
     */
    protected $allowed = [];

    /**
     * List of allowed allDisks
     *
     * @var array
     */
    protected $allDisks = [];

    /**
     * @var array
     */
    protected $fileTypes = [
        'image' => 'png|jpg|jpeg|tmp|gif',
        'word'  => 'doc|docx',
        'ppt'   => 'ppt|pptx',
        'pdf'   => 'pdf',
        'code'  => 'php|js|java|python|ruby|go|c|cpp|sql|m|h|json|html|aspx',
        'zip'   => 'zip|tar\.gz|rar|rpm',
        'txt'   => 'txt|pac|log|md',
        'audio' => 'mp3|wav|flac|3pg|aa|aac|ape|au|m4a|mpc|ogg',
        'video' => 'mkv|rmvb|flv|mp4|avi|wmv|rm|asf|mpeg',
    ];

    /**
     * @var string[]
     */
    protected $fileUnits = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

    /**
     * MediaManager constructor.
     *
     * @param string $path
     * @param string $disk
     */
    public function __construct(string $path = '/', string $disk = '')
    {
        $this->path = $path;

        if (!empty(config('admin.extension.media-manager.allowed_ext'))) {
            $this->allowed = explode(',', config('admin.extensions.media-manager.allowed_ext'));
        }

        $this->initDisk($disk);
        $this->initStorage();
    }

    private function initStorage()
    {
        $this->storage = Storage::disk($this->disk);

        if (!$this->storage->getAdapter() instanceof LocalFilesystemAdapter) {
            throw new \Exception('[jatdung/media-manager] only works for local storage.');
        }
    }

    protected function initDisk(string $disk = '')
    {
        $diskConfig = $this->getAllDisks();

        if (empty($disk)) {
            $this->disk = $diskConfig[0];
            return $diskConfig[0];
        }

        if (!in_array($disk, $diskConfig)) {
            throw new \Exception(sprintf('[jatdung/media-manager] disk [%s] is not in config [admin.extension.disk].', $disk));
        }

        $this->disk = $disk;
        return $disk;
    }

    public function ls()
    {
        if (!$this->exists()) {
            throw new \Exception("File or directory [$this->path] not exists");
        }

        $files = $this->storage->files($this->path);

        $directories = $this->storage->directories($this->path);

        return $this->formatDirectories($directories)
            ->merge($this->formatFiles($files))
            ->filter(function ($file) {
                // 忽略隐藏文件
                return !str_starts_with($file['name'], '.');
            })->sort(function ($item) {
                return $item['name'];
            })->all();
    }

    /**
     * Get full path for a giving fiel path.
     *
     * @param string $path
     *
     * @return string
     */
    protected function getFullPath($path)
    {
        $fullPath = $this->storage->path($path);
        if (str_contains($fullPath, '..')) {
            throw new \Exception('Incorrect path');
        }

        return $fullPath;
    }

    public function download()
    {
        $fullPath = $this->getFullPath($this->path);

        if (File::isFile($fullPath)) {
            return response()->download($fullPath);
        }

        return response('', 404);
    }

    public function delete($path)
    {
        $paths = is_array($path) ? $path : func_get_args();

        foreach ($paths as $path) {
            $fullPath = $this->getFullPath($path);

            if (File::isFile($fullPath)) {
                $this->storage->delete($path);
            } else {
                $this->storage->deleteDirectory($path);
            }
        }

        return true;
    }

    public function move($new)
    {
        $ext = pathinfo($new, PATHINFO_EXTENSION);
        if ($this->allowed && !in_array($ext, $this->allowed)) {
            throw new \Exception('File extension ' . $ext . ' is not allowed');
        }

        return $this->storage->move($this->path, $new);
    }

    /**
     * @param UploadedFile[] $files
     * @param string $dir
     *
     * @return mixed
     */
    public function upload($files = [])
    {
        foreach ($files as $file) {
            if ($this->allowed && !in_array($file->getClientOriginalExtension(), $this->allowed)) {
                throw new \Exception('File extension ' . $file->getClientOriginalExtension() . ' is not allowed');
            }

            $this->storage->putFileAs($this->path, $file, $file->getClientOriginalName());
        }

        return true;
    }

    public function newFolder($name)
    {
        $path = rtrim($this->path, '/') . '/' . trim($name, '/');

        return $this->storage->makeDirectory($path);
    }

    public function exists()
    {
        if ($this->path === '/') {
            return true;
        }
        return $this->storage->exists($this->path);
    }

    public function formatFiles($files = [])
    {
        $files = array_map(function ($file) {
            return [
                'download' => admin_route('media-download', compact('file')),
                'icon'     => '',
                'name'     => $file,
                'preview'  => $this->getFilePreview($file),
                'isDir'    => false,
                'size'     => $this->getFilesize($file),
                'link'     => admin_route('media-download', ['file' => $file, 'disk' => $this->disk]),
                'url'      => $this->storage->url($file),
                'time'     => $this->getFileChangeTime($file),
            ];
        }, $files);

        return collect($files);
    }

    public function formatDirectories($dirs = [])
    {
        $url = admin_route('media-index', ['path' => '__path__', 'view' => request('view')]);

        $preview = "<a href=\"$url\"><span class=\"file-icon text-aqua\"><i class=\"fa fa-folder\"></i></span></a>";

        $dirs = array_map(function ($dir) use ($preview) {
            return [
                'download' => '',
                'icon'     => '',
                'name'     => $dir,
                'preview'  => str_replace('__path__', $dir, $preview),
                'isDir'    => true,
                'size'     => '',
                'link'     => admin_route('media-index', ['path' => '/' . trim($dir, '/'), 'view' => request('view'), 'disk' => $this->disk]),
                'url'      => $this->storage->url($dir),
                'time'     => $this->getFileChangeTime($dir),
            ];
        }, $dirs);

        return collect($dirs);
    }

    public function navigation(string $view = 'table')
    {
        $folders = explode('/', $this->path);

        $folders = array_filter($folders);

        $path = '';

        $navigation = [];

        foreach ($folders as $folder) {
            $path = rtrim($path, '/') . '/' . $folder;

            $navigation[] = [
                'name' => $folder,
                'url'  => admin_route('media-index', ['path' => $path, 'view' => $view]),
            ];
        }

        return $navigation;
    }

    public function getFilePreview($file)
    {
        switch ($this->detectFileType($file)) {
            case 'image':

                if ($this->storage->getConfig()['url']) {
                    $url = $this->storage->url($file);
                    $preview = "<span class=\"file-icon has-img\"><img src=\"$url\" alt=\"Attachment\"></span>";
                } else {
                    $preview = '<span class="file-icon"><i class="fa fa-file-image-o"></i></span>';
                }
                break;

            case 'pdf':
                $preview = '<span class="file-icon"><i class="fa fa-file-pdf-o"></i></span>';
                break;

            case 'zip':
                $preview = '<span class="file-icon"><i class="fa fa-file-zip-o"></i></span>';
                break;

            case 'word':
                $preview = '<span class="file-icon"><i class="fa fa-file-word-o"></i></span>';
                break;

            case 'ppt':
                $preview = '<span class="file-icon"><i class="fa fa-file-powerpoint-o"></i></span>';
                break;

            case 'xls':
                $preview = '<span class="file-icon"><i class="fa fa-file-excel-o"></i></span>';
                break;

            case 'txt':
                $preview = '<span class="file-icon"><i class="fa fa-file-text-o"></i></span>';
                break;

            case 'code':
                $preview = '<span class="file-icon"><i class="fa fa-code"></i></span>';
                break;

            default:
                $preview = '<span class="file-icon"><i class="fa fa-file"></i></span>';
        }

        return $preview;
    }

    protected function detectFileType($file)
    {
        $extension = File::extension($file);

        foreach ($this->fileTypes as $type => $regex) {
            if (preg_match("/^($regex)$/i", $extension) !== 0) {
                return $type;
            }
        }

        return false;
    }

    public function getFilesize($file)
    {
        $bytes = $this->storage->size($file);

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $this->fileUnits[$i];
    }

    public function getFileChangeTime($file)
    {
        return date('Y-m-d H:i:s', $this->storage->lastModified($file));
    }

    public function getAllDisks()
    {
        if ($this->allDisks === []) {
            $diskConfig = config('admin.extension.media-manager.disk');
            if (empty($diskConfig)) {
                throw new \Exception('[jatdung/media-manager] config [admin.extension.disk] is empty.');
            }
            $this->allDisks = (array)$diskConfig;
        }

        return $this->allDisks;
    }
}

