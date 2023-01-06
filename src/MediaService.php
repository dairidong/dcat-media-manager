<?php

namespace Jatdung\MediaManager;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jatdung\MediaManager\Adapters\Adapter;
use Jatdung\MediaManager\Adapters\OvertrueQiniuAdapter;
use Jatdung\MediaManager\Exceptions\AdapterException;
use Jatdung\MediaManager\Exceptions\DriverException;
use League\Flysystem\DirectoryListing;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;

class MediaService
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
     * @var array
     */
    protected $config = [
        'disks' => 'public',
        'allowed_ext' => '',
        'show_hidden_files' => false,
        // 'allowed_ext' => 'jpg,jpeg,png,pdf,doc,docx,zip',
        'adapters' => [
            QiniuAdapter::class => OvertrueQiniuAdapter::class,
        ],
    ];

    /**
     * @var Adapter
     */
    protected $adapter;

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
     * MediaManager constructor.
     *
     * @param string|null $path
     * @param string|null $disk
     * @param array $config
     *
     * @throws DriverException
     */
    public function __construct(string $path = null, string $disk = null, array $config = [])
    {
        $this->initConfig($config);

        if (!is_null($disk)) {
            $this->setDisk($disk);
        }

        if (!is_null($path)) {
            $this->setPath($path);
        }
    }

    protected function initConfig(array $config = [])
    {
        $this->config = array_merge_recursive(
            $this->config,
            config('admin.extension.media-manager'),
            $config,
        );

        return $this;
    }

    protected function initAdapter()
    {
        $disk = Storage::disk($this->disk);
        if (!($disk instanceof FilesystemAdapter)) {
            throw new DriverException(sprintf('This disk must be an instance of [%s]', FilesystemAdapter::class));
        }
        $this->adapter = $this->resolveAdapter($disk);
    }

    /**
     * @param FilesystemAdapter $disk
     * @return Adapter
     *
     * @throws AdapterException|DriverException
     */
    protected function resolveAdapter(FilesystemAdapter $disk)
    {
        $adapters = $this->config['adapters'] ?? [];
        $diskDriver = get_class($disk->getAdapter());

        if (array_key_exists($diskDriver, $adapters)) {
            $adapter = $adapters[$diskDriver];
            if (!is_subclass_of($adapter, Adapter::class) && $adapter !== Adapter::class) {
                throw new AdapterException(sprintf(
                    'This adapter [%s] is not an instance of [%s]',
                    $adapter,
                    Adapter::class
                ));
            }

            return new $adapter($disk);
        }

        if (in_array($diskDriver, $this->originalDrivers())) {
            return new Adapter($disk);
        }

        throw new DriverException(sprintf(
            'This driver [%s] can not found adapter',
            $diskDriver
        ));
    }

    protected function initDisk(string $disk = '')
    {
        $diskConfig = $this->allDisks();

        if ($disk === '') {
            $this->disk = $diskConfig[0];

            return $diskConfig[0];
        }

        if (!in_array($disk, $diskConfig)) {
            throw new DriverException(sprintf('disk [%s] is not in config [admin.extension.disks].', $disk));
        }

        $this->disk = $disk;
    }

    /**
     * @return \League\Flysystem\DirectoryListing
     *
     * @throws AdapterException
     * @throws \League\Flysystem\FilesystemException
     */
    public function ls()
    {
        $list = $this->adapter->list($this->path);
        if (!$this->isShowHiddenFiles()) {
            $list = $list->filter(fn($file) => !Str::startsWith(basename($file['path']), '.'));
        }
        return $list;
    }

    /**
     * @param string $path
     * @return array
     * @throws FileNotFoundException
     */
    public function metadata(string $path)
    {
        if (!$this->adapter->disk()->fileExists($path)) {
            throw new FileNotFoundException();
        }

        return $this->adapter->metadata($path);
    }

    /**
     * Delete Single file
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path)
    {
        return $this->adapter->directoryExists($path)
            ? $this->adapter->deleteDirecotry($path)
            : $this->adapter->delete($path);
    }

    /**
     * Batch Delete
     *
     * @param array $names
     * @return bool
     * @throws AdapterException
     * @throws FilesystemException
     */
    public function batchDelete(array $names)
    {
        $list = $this->ls()->filter(fn($file) => in_array(basename($file['path']), $names));

        /**
         * @var Collection $files
         * @var Collection $directories
         */
        [$files, $directories] = $this->partitionFileType($list);

        $deleteFile = true;
        if ($files->isNotEmpty()) {
            $deleteFile = $this->adapter->delete($files->pluck('path')->toArray());
        }

        $deleteDirectories = true;
        if ($directories->isNotEmpty()) {
            $directories->each(function ($directory) use (&$deleteDirectories) {
                if (!$this->adapter->deleteDirecotry($directory['path'])) {
                    $deleteDirectories = false;
                }
            });
        }

        return $deleteFile && $deleteDirectories;
    }

    /**
     * 区分文件和目录
     *
     * @param DirectoryListing $list
     * @return Collection
     */
    protected function partitionFileType(DirectoryListing $list)
    {
        return Collection::make($list->toArray())
            ->partition(fn($file) => $file['type'] === StorageAttributes::TYPE_FILE);
    }

    public function move(string $source, string $destination)
    {
        $ext = pathinfo($destination, PATHINFO_EXTENSION);
        // todo 检查
        if ($this->allowed && !in_array($ext, $this->allowed)) {
            throw new \Exception('File extension ' . $ext . ' is not allowed');
        }

        return $this->adapter->move($source, $destination);
    }

    /**
     * @param string $name
     * @param bool $fullPath
     * @return bool
     *
     * @throws DriverException
     */
    public function newFolder(string $name, bool $fullPath = false)
    {
        $path = $fullPath ? $name : rtrim($this->path, '/') . '/' . trim($name, '/');

        return $this->adapter->makeDirectory($path);
    }

    /**
     * @param string|null $path
     * @return bool
     * @throws \League\Flysystem\FilesystemException
     */
    public function exists(string $path = null)
    {
        if (is_null($path)) {
            $path = $this->path;
        }

        if ($path === '/') {
            return true;
        }

        // 隐藏文件跳过判断
        if (!$this->isShowHiddenFiles() && Str::startsWith(basename($path), '.')) {
            return false;
        }

        return $this->adapter->exists($path);
    }

    public function url(string $path)
    {
        return $this->adapter->url($path);
    }

    public function imagePreview(string $path)
    {
        return $this->adapter->imageThumbnail($path);
    }

    /**
     * @return $this|string
     *
     * @throws DriverException
     */
    public function disk()
    {
        if (empty($this->disk)) {
            $this->setDisk('');
        }

        return $this->disk;
    }

    /**
     * @param string $disk
     * @return $this
     * @throws DriverException
     */
    public function setDisk(string $disk)
    {
        $this->initDisk($disk);
        $this->initAdapter();

        return $this;
    }

    /**
     * @return $this|string
     */
    public function path()
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return Adapter
     */
    public function adapter()
    {
        return $this->adapter;
    }

    public function allDisks()
    {
        if ($this->allDisks === []) {
            $disks = $this->config['disks'];
            if (empty($disks)) {
                throw new DriverException('config [admin.extension.disks] is empty.');
            }
            $this->allDisks = array_unique(Arr::wrap($disks));
        }

        return $this->allDisks;
    }

    public function allowedExtensions()
    {
        // todo
        // $this->allowed = explode(',', config('admin.extension.media-manager.allowed_ext'));
        return $this->config['allowed_ext'];
    }

    public function isShowHiddenFiles()
    {
        return (bool)$this->config['show_hidden_files'];
    }

    /**
     * @return string[]
     */
    protected function originalDrivers()
    {
        return [
            \League\Flysystem\Local\LocalFilesystemAdapter::class,
            \League\Flysystem\Ftp\FtpAdapter::class,
            \League\Flysystem\PhpseclibV3\SftpAdapter::class,
            \League\Flysystem\AwsS3V3\AwsS3V3Adapter::class,
        ];
    }
}
