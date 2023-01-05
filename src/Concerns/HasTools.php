<?php

namespace Jatdung\MediaManager\Concerns;

use Jatdung\MediaManager\Tools\NewFolder;
use Jatdung\MediaManager\Tools\RefreshButton;
use Jatdung\MediaManager\Tools\SwitchPanelView;
use Jatdung\MediaManager\Tools\UploadFile;
use Jatdung\MediaManager\Tools;

trait HasTools
{
    /**
     * @var Tools
     */
    protected $tools;

    /**
     * Setup manager tools.
     */
    public function setUpTools()
    {
        $this->tools = new Tools($this);
        $this->appendDefaultTools();
    }

    /**
     * Append default tools
     *
     * @return void
     */
    public function appendDefaultTools()
    {
        if ($this->isEnableBatchActions()) {
            $this->tools->append(new Tools\BatchActions());
        }

        $this->tools->append(new RefreshButton())
            ->append(UploadFile::make())
            ->append(NewFolder::make())
            ->append(SwitchPanelView::make());
    }

    /**
     * If show toolbar
     *
     * @return bool
     */
    public function allowToolbar()
    {
        return (bool)$this->option('toolbar');
    }

    /**
     * @param bool $value
     * @return bool|\Jatdung\MediaManager\MediaManager|mixed|null
     */
    public function disableToolbar(bool $value = true)
    {
        return $this->option('toolbar', !$value);
    }

    /**
     * @param bool $value
     * @return bool|\Jatdung\MediaManager\MediaManager|mixed|null
     */
    public function showToolbar(bool $value = true)
    {
        return $this->option('toolbar', $value);
    }

    /**
     * Disable refresh button.
     *
     * @param bool $disable
     * @return $this
     */
    public function disableRefreshButton(bool $disable = true)
    {
        $this->tools->disableRefreshButton($disable);

        return $this;
    }

    /**
     * Show refresh button.
     *
     * @param bool $val
     * @return $this
     */
    public function showRefreshButton(bool $val = true)
    {
        return $this->disableRefreshButton(!$val);
    }

    /**
     * @param bool $disable
     * @return $this
     */
    public function disableUploadFileButton(bool $disable = true)
    {
        $this->tools->disableUploadFileButton($disable);

        return $this;
    }

    /**
     * @param bool $val
     * @return \Jatdung\MediaManager\MediaManager
     */
    public function showUploadFileButton(bool $val = true)
    {
        return $this->disableUploadFileButton(!$val);
    }

    /**
     * @param bool $disable
     * @return $this
     */
    public function disableNewFolderButton(bool $disable = true)
    {
        $this->tools->disableNewFolderButton($disable);

        return $this;
    }

    /**
     * @param bool $val
     * @return \Jatdung\MediaManager\MediaManager
     */
    public function showNewFolderButton(bool $val = true)
    {
        return $this->disableNewFolderButton(!$val);
    }

    /**
     * @param bool $disable
     * @return $this
     */
    public function disableSwitchViewButton(bool $disable = true)
    {
        $this->tools->disableSwitchViewButton($disable);

        return $this;
    }

    /**
     * @param bool $val
     * @return \Jatdung\MediaManager\MediaManager
     */
    public function shoSwitchViewButton(bool $val)
    {
        return $this->disableSwitchViewButton(!$val);
    }

    /**
     * Render custom tools.
     *
     * @return string
     */
    public function renderTools()
    {
        return $this->tools->render();
    }

    abstract public function option($key, $value = null);
}
