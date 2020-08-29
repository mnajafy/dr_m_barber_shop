
window.framework.validation = (function ($) {
    var pub = {
        addMessage: function (messages, message, value) {
            messages.push(message.replace(/\{value\}/g, value));
        },
        required: function (attribute, value, messages, options) {
            var valid = false;
            if (options.requiredValue === undefined) {
                var isString = typeof value == 'string' || value instanceof String;
                if (options.strict && value !== undefined || !options.strict && (isString ? $.trim(value) : value)) {
                    valid = true;
                }
            } else if (!options.strict && value == options.requiredValue || options.strict && value === options.requiredValue) {
                valid = true;
            }
            if (!valid) {
                pub.addMessage(messages, options.message, value);
            }
        },
        string: function (attribute, value, messages, options) {
            if (typeof value !== 'string') {
                pub.addMessage(messages, options.message, value);
                return;
            }
            if (options.is !== undefined && value.length != options.is) {
                pub.addMessage(messages, options.notEqual, value);
                return;
            }
            if (options.min !== undefined && value.length < options.min) {
                pub.addMessage(messages, options.tooShort, value);
            }
            if (options.max !== undefined && value.length > options.max) {
                pub.addMessage(messages, options.tooLong, value);
            }
        }
    };
    return pub;
})(jQuery);