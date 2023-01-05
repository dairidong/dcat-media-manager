<?php

namespace Jatdung\MediaManager\Forms;

use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Form\Field;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

class UploadFile extends Form implements LazyRenderable
{
    use LazyWidget;

    public function handle()
    {
        // 无用，不定义会报错
    }

    public function form()
    {
        $allowedExtensions = $this->allowedExtensions();

        $this->multipleFile('files')
            ->when(!empty($allowedExtensions), function (Field $field) use ($allowedExtensions) {
                $field->accept($allowedExtensions)
                    ->when($this->serverValidate(), function (Field $field) use ($allowedExtensions) {
                        $field->rules(['mimes:' . $allowedExtensions], [
                            'mimes' => trans('admin.uploader.Q_TYPE_DENIED'),
                        ]);
                    });
            })
            ->disk($this->payload['disk'])
            ->move($this->payload['path'])
            ->on('startUpload', $this->startUploadScript())
            ->on('uploadFinished', $this->uploadFinishedScript())
            ->on('uploadSuccess', $this->uploadSuccess())
            ->setLabelClass('d-none')
            ->setFieldClass('offset-md-2');

        $this->disableSubmitButton();
    }

    /**
     * StartUpload 事件，传递 payload 参数到 Uploader 的表单
     *
     * @return string
     */
    protected function startUploadScript(): string
    {
        // 传递 payload 参数
        $payloadKey = "#{$this->getElementId()} input[name=_payload_]";

        return <<<"JS"
function (){
    let payload = $('$payloadKey')
    this.uploader.options.formData['_payload_'] = payload.val()
}
JS;
    }

    /**
     * UploadFinished 事件，所有文件上传成功重载页面
     *
     * @return string
     */
    protected function uploadFinishedScript(): string
    {
        return <<<'JS'
function (){
    if(this.fileCount === this.addUploadedFile.uploadedFiles.length){
        Dcat.reload()
    }
}
JS;
    }

    /**
     * UploadSuccess 事件，单个文件上传完毕去除无用的删除按钮
     *
     * @return string
     */
    protected function uploadSuccess()
    {
        return <<<'JS'
function (file, reason){
    let li = this.getFileView(file.id);
    li.find('[data-file-act="delete"]').hide()
}
JS;
    }

    /**
     * @return bool
     */
    protected function serverValidate(): bool
    {
        return config('admin.extension.media-manager.uploader_use_server_validate') ?: false;
    }

    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected function allowedExtensions()
    {
        return config('admin.extension.media-manager.allowed_ext') ?: '';
    }
}
