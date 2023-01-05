<?php

namespace Jatdung\MediaManager;

abstract class BatchAction extends ManagerAction
{
    /**
     * {@inheritdoc}
     */
    protected function actionScript()
    {
        $warning = __('No data selected!');
        $disk = urlencode($this->manager()->disk());
        $path = urlencode($this->manager()->path());

        return <<<JS
function (data, target, action) {
    var selected = Dcat.mediaManager.selector().getSelectedRows()

    if (selected.length === 0) {
        Dcat.warning('{$warning}');
        return false;
    }

    const files = $.map(selected, function (val) {
      return val.label;
    });
    // 设置主键为复选框选中的行ID数组
    action.options.data = {
      ...action.options.data,
      disk: '{$disk}',
      path: '{$path}',
      files,
    };
}
JS;
    }
}
