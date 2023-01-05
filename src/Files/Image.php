<?php

namespace Jatdung\MediaManager\Files;

class Image extends File
{
    public function preview()
    {
        return sprintf(
            '<span class="file-icon has-img" data-action="preview-img" src="%s"><img src="%s" alt="%s"></span>',
            $this->manager()->service()->url(urlencode($this->file->path())),
            $this->manager()->service()->imagePreview(urlencode($this->file->path())),
            htmlspecialchars($this->name())
        );
    }
}
