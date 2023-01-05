<div class="media-page">
    @if($manager->allowToolbar())
        @include('jatdung.media-manager::_toolbar')
    @endif

    {!! $manager->renderPanel() !!}
</div>

<script require="@jatdung.media-manager" init=".media-page">
    Dcat.mediaManager = $this.MediaManager();
    @if($manager->isEnableBatchActions())
        {!! $manager->fileSelector()->renderSelectorScript() !!}
    @endif
</script>

