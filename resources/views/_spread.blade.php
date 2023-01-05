<ul class="files">
    @foreach($manager->files() as $file)
        <li>
            {!! $file->renderCheckbox() !!}

            {!! $file->renderPreview() !!}

            <div class="file-info">
                {!! $file->renderName() !!}
                <span class="file-size">
                    {{ $file->fileSize() }}&nbsp;
                    <div class="btn-group btn-group-sm pull-right">
                        <button type="button"
                                class="btn btn-default btn-sm dropdown-toggle dropdown-toggle-split file-dropdown"
                                data-toggle="dropdown" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            {!! $file->renderActions() !!}
                        </div>
                    </div>
                </span>
            </div>
        </li>
    @endforeach
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


