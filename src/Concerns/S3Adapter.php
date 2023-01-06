<?php

namespace Jatdung\MediaManager\Concerns;

use Illuminate\Filesystem\FilesystemAdapter;

trait S3Adapter
{
    /**
     * @var FilesystemAdapter
     */
    protected $originDisk;

    public function setUpDisk(FilesystemAdapter $disk)
    {
        $this->originDisk = $disk;
        $disk = $this->buildS3Disk($disk);

        return parent::setUpDisk($disk);
    }

    public function originDisk(): FilesystemAdapter
    {
        return $this->originDisk;
    }
}