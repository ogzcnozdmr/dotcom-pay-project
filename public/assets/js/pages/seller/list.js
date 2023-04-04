$(document).ready(function() {
    $(document).on('click', '.bayi-sil-button', function() {
        var tihis = $(this);
        var id = tihis.attr('bayi_id');
        Swal.fire({
            title: 'Firmayı silmek üzeresiniz!',
            text: 'Silmek istediğinizden emin misiniz?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Hayır',
            confirmButtonText: 'Evet, sil!'
        }).then((result) => {
            if (result.value) {
                $.post( "/seller/post/remove", {id:id}).done(function(data) {
                    if (data === '1') {
                        swal_title = "Başarılı!";
                        swal_description = "Bayi başarıyla silindi."
                        swal_type = "success";
                        table.row( tihis.parents('tr')).remove().draw();
                    } else {
                        swal_title = "Başarısız!";
                        swal_description = "Bilinmedik bir hata oluştu."
                        swal_type = "danger";
                    }
                    Swal.fire(
                        swal_title,
                        swal_description,
                        swal_type
                    )
                });
            }
        })
    });

    $.post("/seller/post/list", {}, function(data) {
        table = $('#bayi-list-datatable').DataTable({
            responsive:true,
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
    }, 'json');
});