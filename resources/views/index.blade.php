<div class="media-page">
    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-primary">

                <div class="box-body no-padding">

                    <div class="mailbox-controls with-border">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default media-reload" title="Refresh">
                                <i class="fa fa-refresh"></i>
                            </button>
                            <button class="btn btn-default file-delete-multiple"
                                    title="Delete"
                                    data-confirm-message="{{ trans('admin.delete_confirm') }}"
                                    data-url="{{ admin_route('media-delete') }}"
                                    data-disk="{{ $currentDisk }}">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </div>
                        <!-- .btn-group -->
                        <label class="btn btn-default btn">
                            <i class="fa fa-upload"></i>&nbsp;&nbsp;{{ trans('admin.upload') }}
                            <form action="{{ admin_route('media-upload') }}"
                                  method="post"
                                  id="file-upload-form"
                                  enctype="multipart/form-data">
                                <input type="file" name="files[]" class="hidden file-upload" multiple>
                                <input type="hidden" name="dir" value="{{ $path }}" />
                                <input type="hidden" name="disk" value="{{ $currentDisk }}" />
                                {{ csrf_field() }}
                            </form>
                        </label>

                        <!-- /.btn-group -->
                        <a class="btn btn-default btn" data-toggle="modal" data-target="#newFolderModal">
                            <i class="fa fa-folder"></i>&nbsp;&nbsp;{{ trans('admin.new_folder') }}
                        </a>

                        <div class="btn-group">
                            <a href="{{ route('dcat.admin.media-index', ['path' => $path, 'view' => 'table', 'disk' => $currentDisk]) }}"
                               class="btn btn-default active"><i class="fa fa-list"></i></a>
                            <a href="{{ route('dcat.admin.media-index', ['path' => $path, 'view' => 'list', 'disk' => $currentDisk]) }}"
                               class="btn btn-default"><i class="fa fa-th"></i></a>
                        </div>

                        @if(count($disks) > 1)
                            <div class="btn-group">
                                <select class="form-control disk-select" style="min-width: 5rem;appearance: auto">
                                    @foreach($disks as $disk)
                                        <option @if($currentDisk === $disk) selected @endif>{{ $disk }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="input-group input-group-sm pull-right goto-url" style="width: 250px;">
                            <input type="text"
                                   name="path"
                                   class="form-control" value="{{ '/'.trim($path, '/') }}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-sm btn-outline">
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="progress mt-1 d-none">
                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width: 0;" aria-valuenow="0"
                                 aria-valuemin="0"
                                 aria-valuemax="100">0%
                            </div>
                        </div>
                    </div>

                    <!-- /.mailbox-read-message -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer" style="display: flex;flex-direction: column">
                    <ol class="breadcrumb" style="margin-bottom: 10px;">

                        <li class="breadcrumb-item"><a
                                    href="{{ route('dcat.admin.media-index',['view' => $view]) }}"><i
                                        class="fa fa-th-large"></i> </a></li>

                        @foreach($nav as $item)
                            <li class="breadcrumb-item"><a href="{{ $item['url'] }}"> {{ $item['name'] }}</a></li>
                        @endforeach
                    </ol>
                    @include('jatdung.media-manager::'.$view,['list' => $list])

                </div>
                <!-- /.box-footer -->
                <!-- /.box-footer -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>

    <div class="modal fade" id="moveModal" tabindex="-1" role="dialog" aria-labelledby="moveModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="moveModalLabel">
                        {{ \Jatdung\MediaManager\MediaManagerServiceProvider::trans('media.rename') }}
                        & {{ \Jatdung\MediaManager\MediaManagerServiceProvider::trans('media.move') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>

                </div>
                <form id="file-move" action="{{ admin_route('media-move') }}" method="post">
                    {{ method_field('put') }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">
                                {{ \Jatdung\MediaManager\MediaManagerServiceProvider::trans('media.path') }}:
                            </label>
                            <input type="text" class="form-control" name="new" />
                        </div>
                        <input type="hidden" name="path" />
                        <input type="hidden" name="disk" value="{{ $currentDisk }}" />
                        {{ csrf_field() }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm"
                                data-dismiss="modal">{{ trans('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-primary btn-sm">{{ trans('admin.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="urlModal" tabindex="-1" role="dialog" aria-labelledby="urlModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="urlModalLabel">Url</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control-plaintext text-dark" readonly />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-default btn-sm"
                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newFolderModal" tabindex="-1" role="dialog" aria-labelledby="newFolderModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="newFolderModalLabel">{{ trans('admin.new_folder') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ admin_route('media-new-folder') }}" method="post" id="new-folder">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" />
                        </div>
                        <input type="hidden" name="dir" value="{{ $path }}" />
                        <input type="hidden" name="disk" value="{{ $currentDisk }}" />
                        {{ csrf_field() }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm"
                                data-dismiss="modal">{{ trans('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-primary btn-sm">{{ trans('admin.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="file-delete-form"
          action="{{ admin_route("media-delete") }}"
          class="d-none"
          method="post"
          data-confirm-message="{{ trans('admin.delete_confirm') }}">
        {{ method_field("delete") }}
        {{ csrf_field() }}
        <input type="hidden" name="files[]">
        <input type="hidden" name="disk" value="{{ $currentDisk }}">
    </form>
</div>

<script require="@jatdung.media-manager">
    $('.media-page').MediaManager()
</script>

