<div class="table-responsive" style="overflow: auto;">
    <table class="table table-hover file-table">
        <tbody>
        <tr>
            @if($manager->isEnableBatchActions())
                <th style="width: 3rem;">
                    {!! $manager->fileSelector()->renderHeader() !!}
                </th>
            @endif
            <th style="overflow: hidden">{{ trans('admin.name') }}</th>
            <th></th>
            <th style="width: 14rem;">{{ trans('admin.time') }}</th>
            <th style="width: 7rem;">{{ trans('admin.size') }}</th>
        </tr>
        @foreach($manager->files() as $file)
            <tr>
                {!! $file->renderCheckbox() !!}
                <td>
                    {{-- flex 包裹避免窗口宽度小时换行 --}}
                    <div class="d-flex">
                        {!! $file->renderPreview() !!}
                        {!! $file->renderName() !!}
                    </div>
                </td>

                <td class="action-row">
                    <div class="btn-group btn-group-xs invisible">
                        {!! $file->renderActions() !!}
                    </div>
                </td>
                <td>{{ $file->lastModified() }}</td>
                <td>{{ $file->fileSize() }}&nbsp;</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<style>
    .file-icon {
        text-align: left;
        font-size: 25px;
        color: #666;
        display: block;
        float: left;
    }

    .file-name {
        float: left;
        margin: 7px 0 0 10px;
    }

    .file-icon.has-img > img {
        max-height: 30px;
    }

    .file-table tr td, .file-table tr th {
        white-space: nowrap;
    }

</style>

<script>
    // 操作栏动画
    $('table>tbody>tr').mouseover(function () {
        $(this).find('.btn-group').removeClass('invisible');
    }).mouseout(function () {
        $(this).find('.btn-group').addClass('invisible');
    });

    $('.file-select-all input[type=checkbox]').on('change', function () {
        var checked = this.checked

        $('.file-select input[type=checkbox]').attr('checked', checked)
    });
</script>

