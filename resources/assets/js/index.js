(function (w, $) {
    function MediaManager(options) {
        this.options = $.extend({
            $el: $('.media-page'),
        }, options);

        this.init(this.options);
    }

    MediaManager.prototype = {

        _selector: null,

        init: function (options) {
            // 路径跳转
            Dcat.init('.goto-url button', function (btn) {
                btn.click(function () {
                    var url = new URL(window.location.href)

                    var path = $('.goto-url input').val();
                    url.searchParams.set('path', encodeURI(path))
                    Dcat.reload(url.href)
                })
            });

            batchDelete();
        },

        initSelector(checkboxSelector, selectAllSelector, background = 'primary') {
            this._selector = Dcat.RowSelector({
                checkboxSelector: checkboxSelector,
                selectAllSelector: selectAllSelector,
                clickRow: false,
                background: background
            });

            return this._selector;
        },

        selector() {
            return this._selector;
        }
    };

    function batchDelete() {
        Dcat.init('.file-batch-delete', function (btn) {
            btn.on('click', function () {
                let url = $(this).data('url'),
                    redirect = $(this).data('redirect'),
                    keys = Dcat.mediaManager.selector().getSelectedRows(),
                    lang = Dcat.lang,
                    data = {
                        disk: $(this).data('disk'),
                        path: $(this).data('path'),
                        files: [],
                    };
                console.log(data)

                if (!keys.length) {
                    return;
                }

                let msg = '';
                $.each(keys, function (index, val) {
                    msg += val.label + '<br>';
                    data.files.push(val.label);
                });
                console.log(data)

                Dcat.confirm(lang.delete_confirm, msg, function () {
                    Dcat.NP.start();
                    $.ajax({
                        method: 'DELETE',
                        url: url,
                        data: data,
                        success: function (response) {
                            Dcat.NP.done();

                            if (redirect && !response.data.then) {
                                response.data.then = {action: 'redirect', value: redirect}
                            }

                            Dcat.handleJsonResponse(response);
                        }
                    });
                });
            });
        });
    }

    $.fn.MediaManager = function (options) {
        options = options || {};
        options.$el = $(this);

        return new MediaManager(options);
    };
})(window, jQuery);
