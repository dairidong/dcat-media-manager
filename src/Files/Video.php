<?php

namespace Jatdung\MediaManager\Files;

class Video extends File
{
    public function preview()
    {
        return '<span class="file-icon"><i class="fa fa-video-camera"></i></span>';
    }
}
