/* global Function */

let PageJS = {

    $form: $('form'),

    $button: $('form button'),

    load: function () {

        PageJS.initUI();

    },

    initUI: function () {

        PageJS.$form.submit(function(e) {

            e.preventDefault();

            let values = {
                name : PageJS.$form.find('input[name=ad]').val(),
                email : PageJS.$form.find('input[name=email]').val(),
                phone : PageJS.$form.find('input[name=tel]').val()
            };

            let interval = Function.interval('start', PageJS.$button);

            $.post('/profile/update', values, function(data) {

                Function.interval('stop', PageJS.$button, interval, 'Güncelle');

                Function.info(data.result, data.message, PageJS.$button);

            }, 'JSON');

        });

    }

};

/*
 * Initialling
 */
$(function() {

    PageJS.load();

});