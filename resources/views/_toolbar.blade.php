<div class="row pb-0 justify-content-between flex-wrap" style="row-gap: 1em">
    <div class="d-flex justify-content-start flex-wrap col-md-9 col-12" style="gap: 0.4em">
        {!! $manager->renderTools() !!}
    </div>
    <div class="d-flex justify-content-end col-md-3 col-12" style="gap: 0.4em">
        {!! $manager->renderSwitchDisk() !!}

        {!! $manager->renderAddressBar() !!}
    </div>
</div>
