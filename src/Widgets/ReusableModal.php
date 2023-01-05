<?php

namespace Jatdung\MediaManager\Widgets;

use Dcat\Admin\Exception\RuntimeException;
use Dcat\Admin\Widgets\Modal;

class ReusableModal extends Modal
{
    /**
     * @var string
     */
    protected $buttonSelector;

    /**
     * @var string
     */
    protected $extraScript;

    public function __construct(string $selector, $title = null, $content = null)
    {
        parent::__construct($title, $content);

        $this->setButtonSelector($selector);
    }

    /**
     * @return string
     */
    public function buttonSelector(): string
    {
        return $this->buttonSelector;
    }

    public function setButtonSelector(string $selector)
    {
        if (!$selector) {
            throw new RuntimeException('selector could not be empty');
        }

        $this->buttonSelector = $selector;

        return $this;
    }

    public function appendExtraScript(string $script)
    {
        $this->extraScript = <<<JS
{$this->extraScript}
{$script}
JS;

        return $this;
    }

    protected function addScript()
    {
        $script = '';

        foreach ($this->events as $v) {
            $script .= "target.on('{$v['event']}', function (event) {
                {$v['script']}
            });";
        }

        $this->script = <<<JS
(function () {
    var target = $('#{$this->id()}'), body = target.find('.modal-body');
    var payload = {};

    $('{$this->buttonSelector}').on('click', function () {
        payload = $(this).data();
        target.modal('toggle');
    });
    {$this->getRenderableScript()}
    {$script}
    {$this->extraScript}
})();
JS;
    }

    protected function getRenderableScript()
    {
        if (!$this->getRenderable()) {
            return;
        }

        $url = $this->renderable->getUrl();

        return <<<JS
target.on('{$this->target}:load', function () {
    var url = new URL("{$url}");
    $.each(payload, function (key, value) {
        url.searchParams.set(key, value);
    });

    Dcat.helpers.asyncRender(url.href, function (html) {
        body.html(html);

        {$this->loadScript}

        target.trigger('{$this->target}:loaded');

        payload = {}
    });
});
JS;
    }

    public function button($button)
    {

    }
}
