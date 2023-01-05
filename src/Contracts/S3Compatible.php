<?php

namespace Jatdung\MediaManager\Contracts;

use Illuminate\Filesystem\FilesystemAdapter;

interface S3Compatible
{
    public function s3Disk(FilesystemAdapter $disk): FilesystemAdapter;
}
