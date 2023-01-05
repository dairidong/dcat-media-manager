<?php

namespace Jatdung\MediaManager\Tools;

use Dcat\Admin\Widgets\Dropdown;
use Jatdung\MediaManager\ManagerAction;

class SwitchDisk extends ManagerAction
{
    public function html()
    {
        $disks = $this->manager()->service()->allDisks();

        $currentDisk = $this->manager()->disk();

        return Dropdown::make()
            ->options($disks)
            ->button($currentDisk)
            ->buttonClass('btn btn-white')
            ->map(function ($disk) use ($currentDisk) {
                $url = admin_route('media.index', ['disk' => $disk]);
                return "<a href=\"{$url}\">{$disk}</a>";
            });
    }
}
