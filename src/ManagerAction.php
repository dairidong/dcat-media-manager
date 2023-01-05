<?php

namespace Jatdung\MediaManager;

use Dcat\Admin\Actions\Action;
use Jatdung\MediaManager\Concerns\HasManager;

abstract class ManagerAction extends Action
{
    use HasManager;
}
