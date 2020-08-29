
window.framework = (function ($) {
    var pub = {
        init: function () {
            $('a').on('click', function (e) {

                var message = $(this).data('confirm');
                var method = $(this).data('method');
                var address = $(this).attr('href');

                if (message === undefined && method === undefined) {
                    return;
                }
                
                e.preventDefault();
                
                if (message === undefined) {
                    if (method === 'post') {
                        var $form = $('<form />');
                        $form.attr('action', address);
                        $form.attr('method', 'post');
                        $form.appendTo('body');
                        $form.submit();
                    } else {
                        window.location = address;
                    }
                } else {
                    pub.confirm(message, function () {
                        if (method === 'post') {
                            var $form = $('<form />');
                            $form.attr('action', address);
                            $form.attr('method', 'post');
                            $form.appendTo('body');
                            $form.submit();
                        } else {
                            window.location = address;
                        }
                    });
                }
            });
        },
        confirm: function (message, ok, cancel) {
            if (window.confirm(message)) {
                !ok || ok();
            } else {
                !cancel || cancel();
            }
        }
    };
    return pub;
})(jQuery);

window.framework.init();