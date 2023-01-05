<?php

namespace Jatdung\MediaManager\Tools;

use Dcat\Admin\Admin;
use Dcat\Admin\Support\Helper;
use Illuminate\Support\Str;
use Jatdung\MediaManager\Concerns\HasManager;
use Jatdung\MediaManager\Files\File;
use Jatdung\MediaManager\MediaManager;

class FileSelector
{
    use HasManager;

    protected $checkboxSelector = 'media-manager-file-checkbox';

    protected $selectAllSelector = 'media-manager-select-all';

    protected $style = 'primary';

    protected $background;

    public function __construct(MediaManager $manager)
    {
        $this->setManager($manager);
    }

    public function style(string $style)
    {
        $this->style = $style;

        return $this;
    }

    public function background(string $value)
    {
        $this->background = $value;

        return $this;
    }

    public function renderHeader()
    {
        return <<<HTML
<div class="vs-checkbox-con vs-checkbox-{$this->style} checkbox-grid checkbox-grid-header">
    <input type="checkbox" class="select-all {$this->selectAllSelector}">
    <span class="vs-checkbox"><span class="vs-checkbox--check"><i class="vs-icon feather icon-check"></i></span></span>
</div>
HTML;
    }

    public function renderFileCheckbox(File $file)
    {
        $view = $this->manager()->enabledView(true);
        $id = 'file-' . Str::random(6);

        return $view->renderFileSelector(<<<EOT
<div class="vs-checkbox-con vs-checkbox-{$this->style} checkbox-grid checkbox-grid-column">
    <input type="checkbox" class="{$this->checkboxSelector}" data-label="{$file->name(true)}" data-id="{$id}">
    <span class="vs-checkbox"><span class="vs-checkbox--check"><i class="vs-icon feather icon-check"></i></span></span>
</div>
EOT
        );
    }

    public function renderSelectorScript()
    {
        $background = $this->background ?: Admin::color()->dark20();

        return Helper::render(<<<JS
Dcat.mediaManager.initSelector(
  '.{$this->checkboxSelector}',
  '.{$this->selectAllSelector}',
  '{$background}'
  )
JS
        );
    }

    /**
     * @return string
     */
    public function checkboxSelector(): string
    {
        return $this->checkboxSelector;
    }

    /**
     * @return string
     */
    public function selectAllSelector(): string
    {
        return $this->selectAllSelector;
    }
}
