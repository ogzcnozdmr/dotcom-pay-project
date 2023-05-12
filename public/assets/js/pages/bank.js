/* global Function */

let PageJS = {

    $form : $('form'),

    $bank : $('#select_bank'),

    load: function() {

        PageJS.initUI();

    },

    initUI: function() {

        PageJS.$bank.change(function(){
            let val = $(this).val();
            window.location.href = `/bank/${val}`;
        });

        PageJS.$form.submit(function (e) {

            e.preventDefault();
            let $tihis = $(this);

            $.post('/bank/settings', {
                id : PageJS.$bank.find("option:selected").val(),
                option : $tihis.find("input[type=checkbox]:checked").length,
                name : $tihis.find("input[name=name]").val(),
                password : $tihis.find("input[name=password]").val(),
                client : $tihis.find("input[name=client]").val(),
                storekey : $tihis.find("input[name=storekey]").val(),
                storetype : $tihis.find("input[name=storetype]").val(),
                storetype3d : $tihis.find("input[name=storetype3d]").val(),
                max_installment : $tihis.find("#max_installment option:selected").val(),
                min_installment_amount : $tihis.find("input[name=min_installment_amount]").val()
            }, function(data) {

                Function.elementStatus($tihis.find('[type=submit]'), true);
                Function.info(data.result, data.message, $tihis.find('[type=submit]'));

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