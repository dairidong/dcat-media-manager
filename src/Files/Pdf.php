<?php

namespace Jatdung\MediaManager\Files;

class Pdf extends File
{
    public function preview()
    {
        return '<span class="file-icon"><i class="fa fa-file-pdf-o"></i></span>';
    }
}
