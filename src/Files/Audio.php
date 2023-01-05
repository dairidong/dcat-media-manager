<?php

namespace Jatdung\MediaManager\Files;

class Audio extends File
{
    public function preview()
    {
        return '<span class="file-icon"><i class="fa fa-music"></i></span>';
    }
}
