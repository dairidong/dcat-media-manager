<?php

namespace Jatdung\MediaManager\Files;

use Carbon\Carbon;
use Jatdung\MediaManager\Concerns\HasActions;
use Jatdung\MediaManager\Concerns\HasManager;
use Jatdung\MediaManager\MediaManager;
use League\Flysystem\StorageAttributes;

class File
{
    use HasManager, HasActions;

    const FILE_SIZE_UNITS = [
        'B', 'KB', 'MB', 'GB', 'TB', 'PB',
    ];

    protected $checbox = '';

    public function __construct(
        protected StorageAttributes $file,
        MediaManager                $manager
    )
    {
        $this->manager = $manager;

        $this->setUpDefaultActions();
    }

    /**
     * @param bool $htmlEncode
     * @return string
     */
    public function name(bool $htmlEncode = false)
    {
        $name = basename($this->path());

        if ($htmlEncode) {
            return htmlspecialchars($name);
        }
        return $name;
    }

    /**
     * @return string
     */
    public function path()
    {
        return DIRECTORY_SEPARATOR . ltrim($this->file->path(), DIRECTORY_SEPARATOR);
    }

    public function type()
    {
        return $this->file->type();
    }

    /**
     * @return string
     */
    public function fileSize()
    {
        if (!method_exists($this->file, 'fileSize')) {
            return '';
        }

        $bytes = $this->file->fileSize();
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . self::FILE_SIZE_UNITS[$i];
    }

    /**
     * @return Carbon
     */
    public function lastModified()
    {
        try {
            $timestamp = $this->file->lastModified();
            return $timestamp ? Carbon::createFromTimestamp($timestamp) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return string
     */
    public function preview()
    {
        return '<span class="file-icon"><i class="fa fa-file"></i></span>';
    }

    public function renderPreview()
    {
        return $this->preview();
    }

    public function renderName()
    {
        return '<span class="file-name" title="' . $this->name(true) . '" >' . $this->name() . '</span>';
    }

    public function setCheckbox(string $checkbox)
    {
        $this->checbox = $checkbox;

        return $this;
    }

    public function renderCheckbox()
    {
        return $this->checbox;
    }

    public static function make(...$params)
    {
        return new static(...$params);
    }
}
