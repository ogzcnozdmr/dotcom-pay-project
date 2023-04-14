/* global Function */

let PageJS = {

    $form: $('form'),

    $authority : null,

    load: function() {

        this.$authority = this.$form.find('select');

        PageJS.initUI();

    },

    initUI: function () {

        PageJS.$authority.change(function() {
            let val = $(this).val();
            window.location.href = `/authority/${val}`;
        });

        PageJS.$form.submit(function (e) {

            e.preventDefault();
            let $tihis = $(this);

            $.post('/authority/set', {
                id : PageJS.$form.find("select option:selected").val(),
                option : PageJS.$form.serialize()
            }, function(data) {

                Function.elementStatus($tihis.find('[type=submit]'), true);
                Function.info(data.result, data.message, PageJS.$form.find('button'));

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