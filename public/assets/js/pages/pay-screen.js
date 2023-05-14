/* global Function */

let PageJS = {

    load: function() {

        PageJS.initUI();

    },

    initUI: function() {

        const urlParams = new URLSearchParams(window.location.search);

        /**
         * Ödeme işlemi sonucu
         */
        if (urlParams.get('result') !== null && urlParams.get('message') !== null) {

            Swal.fire(
                'Ödeme İşlemi',
                urlParams.get('message'),
                urlParams.get('result') === '1' ? 'success' : 'error'
            )

        }

        let min_taksit_miktar = $('#min_installment_count').data('value')

        $('input[name=order_total]').on('input', function() {
            let val = parseInt($(this).val());
            if (val >= min_taksit_miktar) {
                $('select[name=order_installment]').parent().show();
            } else {
                $('select[name=order_installment]').parent().hide();
            }
        });

        $('select[name=bank]').change(function() {
            let val = $(this).find("option:selected").val();
            $.post('/installment/get', {bank:val}, function(data) {
                if (data.result) {
                    min_taksit_miktar = parseFloat(data.min);//yeni minimum taksit miktarını getiriyoruz
                    //yeni gelen değer taksit değerinden küçükse, taksiti gizliyoruz
                    if (parseFloat($('input[name=order_total]').val()) < min_taksit_miktar)
                        $('select[name=order_installment]').parent().hide();
                    else if (parseFloat($('input[name=order_total]').val()) >= min_taksit_miktar)
                        $('select[name=order_installment]').parent().show();
                    $('select[name=order_installment]').empty();
                    for (var i = 1;i <= data.max;i++) {
                        $('select[name=order_installment]').append(`
                            <option value="${i}">${i === 1 ? 'Tek Çekim' : i}</option>
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