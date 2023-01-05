<div class="btn-group dropdown  {{$selectAllName}}-btn" style="display:none;margin-right: 3px;z-index: 100">
    <button type="button" class="btn btn-white dropdown-toggle btn-mini" data-toggle="dropdown">
        <span class="d-none d-sm-inline selected"></span>
        <span class="caret"></span>
        <span class="sr-only"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        @foreach($actions as $action)
            @if ($action instanceof \Jatdung\MediaManager\Tools\ActionDivider)
                <li class="dropdown-divider"></li>
            @else
                <li class="dropdown-item">
                    {!! $action->render() !!}
                </li>
            @endif
        @endforeach
    </ul>
</div>

<script>
    Dcat.init('.{{ $manager->fileSelector()->checkboxSelector() }}', function ($this) {
        $this.on('change', function () {
            var btn = $('.{{ $selectAllName }}-btn'),
                selected = Dcat.mediaManager.selector().getSelectedRows().length;

            if (selected) {
                btn.show()
            } else {
                btn.hide()
            }
            setTimeout(function () {
                btn.find('.selected').html("{!! trans('admin.grid_items_selected') !!}".replace('{n}', selected));
            }, 50)
        })
    });
</script>
