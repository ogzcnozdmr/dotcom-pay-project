/* global Function */

let PageJS = {

    $modal : $('#modal'),

    load: function () {

        PageJS.initUI();

    },

    initUI: function () {

        /**
         * Ödeme detayını getirir
         */
        $(document).on('click','.pay-detail',function() {
            let body = PageJS.$modal.find('.modal-body');
            body.empty();
            PageJS.$modal.find('.modal-title').text('Ödeme detayları');

            $.post('/pay/postDetail', { id : $(this).data('id') }, function(data) {
                console.log(data);
                var orderDetail = `
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Ödeme Numarası
                        </label>
                        <div class="col-sm-10"><input class="form-control" type="text" disabled="" value="${data.order_number}"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Ödeme Tarihi
                        </label>
                        <div class="col-sm-10"><input class="form-control" type="text" disabled="" value="${data.pay_date}"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Banka Adı
                        </label>
                        <div class="col-sm-10"><input class="form-control" type="text" disabled="" value="${data.bank_name}"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Kart Sahibi
                        </label>
                        <div class="col-sm-10"><input class="form-control" type="text" disabled="" value="${data.pay_card_owner}"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Firma Adı
                        </label>
                        <div class="col-sm-10"><input class="form-control" type="text" disabled="" value="${data.seller_name}"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Firma Telefon
                        </label>
                        <div class="col-sm-10"><input class="form-control" type="text" disabled="" value="${data.user_phone}"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Firma Mail
                        </label>
                        <div class="col-sm-10"><input class="form-control" type="text" disabled="" value="${data.user_email}"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Ödeme Sonucu
                        </label>
                        <div class="col-sm-10"><input class="form-control" type="text" disabled="" value="${data.pay_result}"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Ödeme Mesajı
                        </label>
                        <div class="col-sm-10"><input class="form-control" type="text" disabled="" value="${data.pay_message}"></div>
                    </div>
                    <div class="form-group row">
                        <div class="btn-group mx-auto my-auto" role="group" aria-label="Basic example">
                            <button data-action="print" class="btn btn-xs btn-info">Yazdır</button>
                        </div>
                    </div>
                `;
                body.append(orderDetail);
                //sipariş detayı yazdırma butonu olayı
                $(document).on('click', '[data-action=print]',function() {
                    let popupWin;
                    popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
                    popupWin.document.open();
                    popupWin.document.write(`
                  <html>
                    <head>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
                    </head>
                    <style>
                        .detay{
                            text-align : center;
                            margin-bottom : 25px;
                        }
                    </style>
                    <body onload="window.print();window.close()">
                        <div class="detay">
                            <h6>ÖDEME DETAYI
                        </h6>
                        ${orderDetail}
                    </body>
                  </html>`
                    );
                    popupWin.document.close();
                });
                PageJS.$modal.modal('toggle');
            }, 'JSON');
        });




        $.post('/pay/postList', {}, function(data) {
            table = $('#datatable-buttons').DataTable({
                responsive: true,
                lengthChange: false,
                buttons: [
                    {
                        extend: 'copy',
                        text: 'Panoya Kopyala'
                    },{
                        extend: 'excel',
                        text: 'Excel Çktısı'
                    },{
                        extend: 'pdf',
                        text: 'Pdf Çıktısı'
                    }
                ],
                data:data,
                language: {
                    "emptyTable": "Gösterilecek veri yok.",
                    "processing": "Veriler yükleniyor",
                    "sDecimal": ".",
                    "sInfo": "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
                    "sInfoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "Sayfada _MENU_ kayıt göster",
                    "sLoadingRecords": "Yükleniyor...",
                    "sSearch": "Ara:",
                    "sZeroRecords": "Eşleşen kayıt bulunamadı",
                    "oPaginate": {
                        "sFirst": "İlk",
                        "sLast": "Son",
                        "sNext": "Sonraki",
                        "sPrevious": "Önceki"
                    },
                    "oAria": {
                        "sSortAscending": ": artan sütun sıralamasını aktifleştir",
                        "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
                    },
                    "select": {
                        "rows": {
                            "_": "%d kayıt seçildi",
                            "0": "",
                            "1": "1 kayıt seçildi"
                        }
                    }
                }
            });
            table.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        }, 'json');

    }

};

/*
 * Initialling
 */
$(function() {

    PageJS.load();

});