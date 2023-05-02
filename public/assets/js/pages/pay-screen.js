/* global Function */

let PageJS = {

    load: function() {

        PageJS.initUI();

    },

    initUI: function() {

        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.get('result') !== null && urlParams.get('message') !== null) {

            Swal.fire(
                'Ödeme İşlemi',
                urlParams.get('message'),
                urlParams.get('result') === '1' ? 'success' : 'error'
            )

        }

        let min_taksit_miktar = $('#min_installment_count').data('value')
        console.log("min = "+min_taksit_miktar);

        $('#sanal-satis-bilgileri input[name=order_total]').on("input",function() {
            let val = parseInt($(this).val());
            console.log("val = "+val+" min taksit = "+min_taksit_miktar);
            if (val>=min_taksit_miktar) {
                $('select[name=order_installment]').parent().show();
            } else {
                $('select[name=order_installment]').parent().hide();
            }
        });

        $('select[name=bank]').change(function() {
            let val = $(this).find("option:selected").val();
            $.post("/installment/get", {bank:val}, function(data) {
                if (data.result) {
                    min_taksit_miktar = parseFloat(data.min);//yeni minimum taksit miktarını getiriyoruz
                    //yeni gelen değer taksit değerinden küçükse, taksiti gizliyoruz
                    if (parseFloat($('input[name=order_total]').val()) < min_taksit_miktar)
                        $('select[name=order_installment]').parent().hide();
                    else if (parseFloat($('input[name=order_total]').val()) >= min_taksit_miktar)
                        $('select[name=order_installment]').parent().show();
                    $('select[name=order_installment]').empty();
                    for (var i = 1;i <= data.max;i++) {
                        var text = i == 1 ? 'Tek Çekim' : i;
                        $('select[name=order_installment]').append(`
                        <option value="${i}">${text}</option>
                    `)
                    }
                }
            }, 'json');
        });

    }

};

/*
 * Initialling
 */
$(function() {

    PageJS.load();

});