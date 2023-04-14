/* global Function */

let PageJS = {

    $form : $('form'),

    $bank : null,

    load: function() {

        this.$bank = this.$form.find('select[name=bank]');

        PageJS.initUI();

    },

    initUI: function() {

        PageJS.$bank.change(function() {
            let val = $(this).val();
            window.location.href = `/installment/${val}`;
        });

        PageJS.$form.submit(function (e) {

            e.preventDefault();
            let $tihis = $(this);

            $.post('/installment/set', {
                id : PageJS.$bank.find('option:selected').val(),
                installment : $tihis.find('select[name=installment] option:selected').val()
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