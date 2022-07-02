@if (!empty($list))
    <table class="table table-hover">
        <tbody>
        <tr>
            <th width="40px;">
                <span class="file-select-all">
                    <input type="checkbox" value="" />
                </span>
            </th>
            <th>{{ trans('admin.name') }}</th>
            <th></th>
            <th width="200px;">{{ trans('admin.time') }}</th>
            <th width="100px;">{{ trans('admin.size') }}</th>
        </tr>
        @foreach($list as $item)
            <tr>
                <td style="padding-top: 15px;">
                    <span class="file-select">
                        <input type="checkbox" value="{{ $item['name'] }}" />
                    </span>
                </td>
                <td>
                    {!! $item['preview'] !!}

                    <a @if(!$item['isDir'])target="_blank" @endif href="{{ $item['link'] }}"
                       class="file-name" title="{{ $item['name'] }}">
                        {{ $item['icon'] }} {{ basename($item['name']) }}
                    </a>
                </td>

                <td class="action-row">
                    <div class="btn-group btn-group-xs d-none">
                        <a class="btn btn-default file-rename" data-toggle="modal" data-target="#moveModal"
                           data-name="{{ $item['name'] }}">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-default file-delete" data-path="{{ $item['name'] }}">
                            <i class="fa fa-trash"></i>
                        </a>
                        @unless($item['isDir'])
                            <a target="_blank" href="{{ $item['download'] }}" class="btn btn-default">
                                <i class="fa fa-download"></i>
                            </a>
                        @endunless
                        <a class="btn btn-default" data-toggle="modal" data-target="#urlModal"
                           data-url="{{ $item['url'] }}">
                            <i class="fa fa-internet-explorer"></i>
                        </a>
                    </div>

                </td>
                <td>{{ $item['time'] }}&nbsp;</td>
                <td>{{ $item['size'] }}&nbsp;</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

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
        margin: 7px 0px 0px 10px;
    }

    .file-icon.has-img > img {
        max-height: 30px;
    }

</style>

<script>
    // 操作栏动画
    $('table>tbody>tr').mouseover(function () {
        $(this).find('.btn-group').removeClass('d-none');
    }).mouseout(function () {
        $(this).find('.btn-group').addClass('d-none');
    });

    $('.file-select-all input[type=checkbox]').on('change', function () {
        var checked = this.checked

        $('.file-select input[type=checkbox]').attr('checked', checked)
    })
</script>

