<ul class="files clearfix">

    @if (empty($list))
        <li style="height: 200px;border: none;"></li>
    @else
        @foreach($list as $item)
            <li>
            <span class="file-select">
                <input type="checkbox" class="file-checkbox" value="{{ $item['name'] }}" />
            </span>

                {!! $item['preview'] !!}

                <div class="file-info">
                    <a @if(!$item['isDir'])target="_blank" @endif href="{{ $item['link'] }}" class="file-name"
                       title="{{ $item['name'] }}">
                        {{ $item['icon'] }} {{ basename($item['name']) }}
                    </a>
                    <span class="file-size">
                    {{ $item['size'] }}&nbsp;
                    <div class="btn-group btn-group-sm pull-right">
                        <button type="button"
                                class="btn btn-default btn-sm dropdown-toggle dropdown-toggle-split file-dropdown"
                                data-toggle="dropdown" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a href="#"
                               class="file-rename dropdown-item"
                               data-toggle="modal"
                               data-target="#moveModal"
                               data-name="{{ $item['name'] }}">Rename & Move</a>
                            <a href="#"
                               class="file-delete dropdown-item"
                               data-path="{{ $item['name'] }}">Delete</a>
                            @unless($item['isDir'])
                                <a target="_blank"
                                   class="dropdown-item"
                                   href="{{ $item['download'] }}">Download</a>
                            @endunless
                            <div class="dropdown-divider"></div>
                            <a href="#"
                               class="dropdown-item"
                               data-toggle="modal"
                               data-target="#urlModal"
                               data-url="{{ $item['url'] }}">Url</a>
                        </div>
                    </div>
                </span>
                </div>
            </li>
        @endforeach
    @endif
</ul>


<style>
    .file-icon {
        text-align: center;
        font-size: 65px;
        color: #666;
        display: block;
        height: 100px;
    }

    .file-icon.has-img > img {
        max-height: 92px;
    }

    .file-dropdown {
        padding: 1px 5px !important;
        height: 20px !important;
    }

</style>

<script>

</script>


