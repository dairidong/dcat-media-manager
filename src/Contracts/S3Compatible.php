<?php

namespace Jatdung\MediaManager\Contracts;

use Illuminate\Filesystem\FilesystemAdapter;

interface S3Compatible
{
    public function buildS3Disk(FilesystemAdapter $disk): FilesystemAdapter;

    public function originDisk(): FilesystemAdapter;
}
