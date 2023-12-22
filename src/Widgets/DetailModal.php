<?php

namespace Jatdung\MediaManager\Widgets;

class DetailModal extends ReusableModal
{
    public function __construct(string $selector, $title = null, $content = null)
    {
        parent::__construct($selector, $title, $content);

        $this->centered()
            ->lg()
            ->title('URL')
            ->content(new DetailShow())
            ->appendExtraScript($this->clipboardScript());
    }

    protected function clipboardScript()
    {
        $successText = trans('admin.copied');

        return <<<JS
var clipboard = new ClipboardJS(".copy-btn", {
    target: function (trigger) {
        return trigger.parentElement.parentElement.firstElementChild;
    },
    text: function(trigger) {
        return trigger.parentElement.parentElement.firstElementChild.textContent.trim();
    },
    container: document.getElementById("{$this->id()}")
});

clipboard.on("success", function (e) {
    Dcat.success("{$successText}");

    $(e.trigger).attr('class', 'copy-btn btn btn-success').find('i').attr('class', 'feather icon-check');
});

$(document).one('pjax:beforeReplace', function() {
  clipboard.destroy();
  clipboard = null;
});

JS;
    }
}
