(function (w, $) {
    function MediaManager(options) {
        this.options = $.extend({
            $el: $('.media-page'),
        }, options);

        this.init(this.options);
    }

    MediaManager.prototype = {
        init: function (options) {
            // 单文件删除
            $('.file-delete').on('click', function () {
                var $form = $('#file-delete-form')
                var input = $form.find('input[name="files[]"]').first()
                var path = $(this).data('path')
                var confirm = $form.data('confirm-message') ? $form.data('confirm-message') : '确认删除？'

                input.val(path)

                Dcat.Form({
                    form: $form,
                    success: requestSuccess,
                    confirm: {title: confirm, content: null}
                })

                return false
            });

            // 文件移动
            $('#file-move').on('submit', function (event) {
                event.preventDefault();

                var $form = $(this);

                Dcat.Form({
                    form: $form,
                    success: requestSuccess,
                })

                closeModal();
            });

            // 关闭模态框
            function closeModal() {
                $("#moveModal").modal('toggle');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            }

            // 文件上传
            $('.file-upload').on('change', function () {
                var $form = $('#file-upload-form')
                var progressBar = $('.progress-bar')

                $form.ajaxSubmit({
                    beforeSubmit: function () {
                        Dcat.loading()
                        $('.progress').removeClass('d-none')
                        progressBar.width('0')
                            .html('0%')
                            .attr('aria-valuenow', 0)
                    },
                    success: function (response) {
                        progressBar.width('100%')
                        requestSuccess(response)
                        Dcat.loading(false)
                    },
                    error: function () {
                        Dcat.loading(false)
                        Dcat.error('系统异常')
                        Dcat.reload()
                    },
                    uploadProgress: function (event, position, total, percentComplete) {
                        var percentVal = percentComplete + '%';
                        progressBar.width(percentVal)
                            .html(percentVal)
                            .attr('aria-valuenow', percentComplete)
                    },
                })
            });

            // 新建文件夹
            $('#new-folder').form({
                success: requestSuccess,
                after: function () {
                    closeModal()
                }
            });

            $('.media-reload').click(function () {
                Dcat.reload()
            });


            // 文件移动表单框
            $('#moveModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var name = button.data('name');

                var modal = $(this);
                modal.find('[name=path]').val(name)
                modal.find('[name=new]').val(name)
            });

            // 文件 URL 显示框
            $('#urlModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var url = button.data('url');

                $(this).find('input').val(url)
            });

            // 路径跳转
            $('.goto-url button').click(function () {
                var url = new URL(window.location.href)

                var path = $('.goto-url input').val();
                url.searchParams.set('path', encodeURI(path))
                Dcat.reload(url.href)
            });

            // 多文件删除
            $('.file-delete-multiple').click(function () {
                var files = $(".file-select input:checked").map(function () {
                    return $(this).val();
                }).toArray();

                if (!files.length) {
                    Dcat.error('没有文件被选中.')
                    return;
                }

                var confirm = $(this).data('confirm-message')
                var url = $(this).data('url')
                var disk = $(this).data('disk')

                Dcat.confirm(
                    confirm ? confirm : "确认删除?",
                    null,
                    function () {
                        $.ajax({
                            method: 'delete',
                            url: url,
                            data: {
                                'files[]': files,
                                _token: Dcat.token,
                                disk: disk,
                            },
                            success: requestSuccess,
                            error: function () {
                                Dcat.error('系统异常')
                                return false
                            }
                        })
                    },
                    {showLoaderOnConfirm: true}
                );
            });

            // 切换 disk
            $('.disk-select').on('change', function () {
                var url = new URL(window.location.href)
                url.search = ''
                url.searchParams.set('disk', encodeURI($(this).val()))
                Dcat.reload(url.href)
            })

            function requestSuccess(response) {
                Dcat.reload()

                if (response.status) {
                    Dcat.success(response.message);
                } else {
                    Dcat.error(response.message);
                }
                return false
            }
        },
    };

    $.fn.MediaManager = function (options) {
        options = options || {};
        options.$el = $(this);

        return new MediaManager(options);
    };
})(window, jQuery);
