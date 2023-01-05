<?php

namespace Jatdung\MediaManager\Files;

class Text extends File
{
    public function preview()
    {
        return '<span class="file-icon"><i class="fa fa-file-text-o"></i></span>';
    }
}
