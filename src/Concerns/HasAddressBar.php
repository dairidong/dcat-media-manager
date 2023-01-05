<?php

namespace Jatdung\MediaManager\Concerns;

use Jatdung\MediaManager\Tools\AddressBar;

trait HasAddressBar
{
    /**
     * @var AddressBar
     */
    protected $addressBar;

    protected function setUpAddressBar()
    {
        $this->setAddressBar(AddressBar::make());
    }

    public function setAddressBar(AddressBar $addressBar)
    {
        $this->addressBar = $addressBar->setManager($this);

        return $this;
    }

    public function disableAddressBar(bool $disable = true)
    {
        $this->addressBar->disable($disable);

        return $this;
    }

    public function renderAddressBar()
    {
        if (!$this->option('address_bar')) {
            return '';
        }

        return $this->addressBar->render();
    }
}
