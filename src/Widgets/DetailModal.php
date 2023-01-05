<?php

namespace Jatdung\MediaManager\Widgets;

use Jatdung\MediaManager\MediaManagerServiceProvider;

class DetailModal extends ReusableModal
{
    public function __construct(string $selector, $title = null, $content = null)
    {
        parent::__construct($selector, $title, $content);

        $this->centered()
            ->lg()
            ->title('URL')
            ->appendExtraScript($this->clipboardScript())
            ->onShow($this->onShowScript());
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
        return trigger.textContent;
    },
    container: document.getElementById("{$this->id()}")
});

clipboard.on("success", function (e) {
    Dcat.success("{$successText}");

    $(e.trigger).attr('class', 'copy-btn btn btn-success').find('i').attr('class', 'feather icon-check');
});

function renderDetailLine(label, value) {
    return (
        '<div class="form-group row align-items-baseline">' +
        '<label class="col-sm-2 control-label">' +
        label +
        "</label>" +
        '<div class="col-sm-9">' +
        '<div class="input-group">' +
        '<div class="form-control">' +
        value +
        "</div>" +
        '<div class="input-group-append">' +
        '<button class="copy-btn btn btn-secondary">' +
        '<i class="feather icon-clipboard"></i>' +
        "</button>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>"
    );
}

JS;
    }

    protected function onShowScript()
    {
        $name = trans('admin.name');
        $path = MediaManagerServiceProvider::trans('media.path');
        $size = trans('admin.size');
        $lastModified = trans('admin.updated_at');
        $url = MediaManagerServiceProvider::trans('media.url');

        return <<<JS
body.html('<div style="min-height:150px"></div>').loading();

var html =
    '<div class="mt-1">' +
    renderDetailLine("{$name}", payload.name) +
    renderDetailLine("{$path}", payload.path);

if (payload.type === "file") {
    var url = '<a href="' + payload.url +'" target="_blank">' + payload.url +"</a>";
    html +=
        renderDetailLine('{$url}', url ) +
        renderDetailLine("{$size}", payload.fileSize);
}

html += renderDetailLine("{$lastModified}", payload.lastModified) + "</div>";

body.html(html);
JS;
    }
}
