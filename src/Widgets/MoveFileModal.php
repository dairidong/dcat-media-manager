<?php

namespace Jatdung\MediaManager\Widgets;

use Jatdung\MediaManager\Forms\MoveFile;
use Jatdung\MediaManager\MediaManagerServiceProvider;

class MoveFileModal extends ReusableModal
{
    public function __construct(string $selector, $title = null, $content = null)
    {
        parent::__construct($selector, $title, $content);

        $this->centered()->lg()
            ->content(MoveFile::make())
            ->title(sprintf(
                '%s & %s',
                MediaManagerServiceProvider::trans('media.rename'),
                MediaManagerServiceProvider::trans('media.move')
            ));
    }
}
