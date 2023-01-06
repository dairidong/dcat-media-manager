<?php

namespace Jatdung\MediaManager\Adapters;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jatdung\MediaManager\Concerns\S3Adapter;
use Jatdung\MediaManager\Contracts\S3Compatible;
use Jatdung\MediaManager\Exceptions\AdapterException;
use Illuminate\Support\Facades\File as FileFacade;

class OvertrueQiniuAdapter extends Adapter implements S3Compatible
{
    use S3Adapter {
        setUpDisk as S3SetUpDisk;
    }

    protected $imageExtensions = ['psd', 'jpeg', 'png', 'gif', 'webp', 'tiff', 'bmp', 'avif', 'heif'];

    protected function setUpDisk(FilesystemAdapter $disk)
    {
        $this->directorySupport['move'] = false;

        return $this->S3SetUpDisk($disk);
    }

    /**
     * {@inheritDoc}
     * @see https://developer.qiniu.com/dora/1279/basic-processing-images-imageview2
     */
    public function imageThumbnail(string $path)
    {
        $url = parent::imageThumbnail($path);
        if (!in_array(FileFacade::extension($path), $this->imageExtensions)) {
            return $url;
        }
        return $url . '?imageView2/2/h/90/format/jpg/interlace/1/ignore-error/1';
    }

    public function buildS3Disk(FilesystemAdapter $disk): FilesystemAdapter
    {
        $config = $disk->getConfig();
        // 校验补充设置
        if (empty($config['s3_endpoint']) || empty($config['s3_region'])) {
            throw new AdapterException(sprintf(
                'Config of the disk need to supply extra options [s3_endpoint] and [s3_region]. See %s',
                'https://developer.qiniu.com/kodo/4088/s3-access-domainname'
            ));
        }

        // url 配置校验
        if (!Str::startsWith($config['domain'], 'http')) {
            throw new AdapterException('Config [domain] of the disk need to start with "http/https"');
        }

        /** @var FilesystemAdapter $s3Disk */
        $s3Disk = Storage::build([
            'driver' => 's3',
            'key' => $config['access_key'],
            'secret' => $config['secret_key'],
            'bucket' => $config['bucket'],
            'url' => $config['domain'],
            'endpoint' => $config['s3_endpoint'],
            'region' => $config['s3_region'],
            'use_path_style_endpoint' => true,
        ]);

        return $s3Disk;
    }
}
