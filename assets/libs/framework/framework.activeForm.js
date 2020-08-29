
window.framework.activeForm = (function ($) {
    var defaults = {
        errorCssClass: 'has-error',
        successCssClass: 'has-success',
        isValid: true
    };
    var attributeDefaults = {
        id: undefined,
        container: undefined,
        validate: undefined,
        error: '.form-error',
        validateOnChange: true,
        validateOnBlur: true
    };
    var pub = {
        init: function (el, attributes, options) {
            return $(el).each(function () {
                var $form = $(this);
                if ($form.data('activeForm')) {
                    return;
                }
                $.each(attributes, function (i) {
                    attributes[i] = $.extend({}, attributeDefaults, this);
                    pub.watchAttribute($form, attributes[i]);
                });
                var settings = $.extend({}, defaults, options || {});
                $form.data('activeForm', {attributes, settings});
                $form.on('submit', pub.submitForm);
            });
        },
        submitForm: function () {
            var $form = $(this);
            var data = $form.data('activeForm');
            data.settings.isValid = true;
            $.each(data.attributes, function (i) {
                var attribute = data.attributes[i];
                var $input = $form.find(attribute.id);
                pub.validateAttribute($form, $input, attribute);
            });
            return data.settings.isValid;
        },
        validateAttribute: function ($form, $input, attribute) {
            if (typeof attribute.validate !== 'function') {
                return;
            }
            var data = $form.data('activeForm');
            var value = $input.val();
            var messages = [];
            attribute.validate(attribute, value, messages);
            var $container = $form.find(attribute.container);
            var $error = $container.find(attribute.error);
            data.settings.isValid = data.settings.isValid && messages.length === 0;
            if (messages.length === 0) {
                $error.text('');
                $container.removeClass(data.settings.errorCssClass).addClass(data.settings.successCssClass);
            } //
            else if (messages.length > 0) {
                $error.text(messages[0]);
                $container.removeClass(data.settings.successCssClass).addClass(data.settings.errorCssClass);
            }
        },
        watchAttribute: function ($form, attribute) {
            var $input = $form.find(attribute.id);
            if (attribute.validateOnChange) {
                $input.on('change', function () {
                    pub.validateAttribute($form, $input, attribute);
                });
            }
            if (attribute.validateOnBlur) {
                $input.on('blur', function () {
                    pub.validateAttribute($form, $input, attribute);
                });
            }
        }
    };
    return pub;
})(jQuery);