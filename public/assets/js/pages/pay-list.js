/* global Function */

let PageJS = {

    load: function () {

        PageJS.initUI();

    },

    initUI: function () {

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