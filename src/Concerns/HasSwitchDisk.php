<?php

namespace Jatdung\MediaManager\Concerns;

use Jatdung\MediaManager\Tools\SwitchDisk;

trait HasSwitchDisk
{
    /**
     * @var SwitchDisk
     */
    protected $switchDisk;

    protected function setUpSwitchDisk()
    {
        $this->setSwitchDisk(SwitchDisk::make());
    }

    public function setSwitchDisk(SwitchDisk $switchDisk)
    {
        $this->switchDisk = $switchDisk->setManager($this);

        return $this;
    }

    public function disableSwitchDisk(bool $disable = true)
    {
        $this->switchDisk->disable($disable);

        return $this;
    }

    public function renderSwitchDisk()
    {
        if (!$this->option('switch_disk')) {
            return '';
        }

        return $this->switchDisk->render();
    }
}
