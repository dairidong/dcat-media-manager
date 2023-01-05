<?php

namespace Jatdung\MediaManager\Files;

class Zip extends File
{
    public function preview()
    {
        return '<span class="file-icon"><i class="fa fa-file-zip-o"></i></span>';
    }
}
