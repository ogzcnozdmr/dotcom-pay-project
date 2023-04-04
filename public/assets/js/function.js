var Function = {

    csrf : $('#csrf_token'),

    load: function() {

        Function.initUI();

    },

    initUI: function() {

        $.ajaxSetup({
            data: {
                [Function.csrf.data('name')] : Function.csrf.data('hash')
            }
        });

        $('.notify-details').click(function(e){
            e.preventDefault();

            let $tihis = $(this);

            let $item = $tihis.closest('.notify-item');

            let id      = $item.data('id');

            let title   = $item.data('title');

            let content = $item.data('content');

            let result  = $item.data('result');

            Swal.fire({
                title: `<strong><u>${title}</u></strong>`,
                type: result == '1'?'success':'error',
                html: content,
                focusConfirm: false
            });

            if($item.hasClass('active')){

                Function.elementStatus($tihis, false);

                $.post( '/process/bildirim', { id:id }, function( data ) {

                    Function.elementStatus($tihis, true);

                    $item.removeClass('active');

                    let revalue = parseInt($('.noti-icon-badge').text())-1;

                    if(revalue>0){

                        $('.noti-icon-badge').text(revalue);

                    }else{

                        $('.noti-icon-badge').remove();

                    }

                }, 'JSON');


            }

        });

        console.log('Function loader');
    },

    interval: function(type = 'start', button = null, interval = null , intervalText = '' , location = false, result = false){

        if(type === 'start'){

            Function.elementStatus(button,false);

            let buttonText = 'İşlem Yapılıyor ';

            let nokta = '';

            let sayac = 0;

            return setInterval(() => {

                sayac++;

                if(sayac === 1){

                    nokta = '.';

                }else if(sayac === 2){

                    nokta = '..';

                }else if(sayac === 3){

                    nokta = '...';

                    sayac = 0;

                }

                button.text(buttonText+nokta);

            },200);

        }else if(type === 'stop'){

            if(!location || !result){

                Function.elementStatus(button,true);

            }

            clearInterval(interval);

            button.text(intervalText);

        }

    },

    widthHeightControl: function(width, height, newWidth = 500, newHeight = 500){

        if(width > height){

            newHeight = Math.ceil(height * newWidth / width);

        }else if(height > width){

            newWidth = Math.ceil(width * newHeight / height);

        }

        return array = {

            width: newWidth,

            height: newHeight

        };

    },

    elementStatus: function(selector,type){

        let element = (typeof selector) === 'string' ? $(selector) : selector ;

        element.prop('disabled', !type);

    },

    info: function(result, message = '', selector = null, href = null){

        if(selector === null){

            $.notify(message, result ? 'success' : 'error');

        }else{

            let element = (typeof selector) === 'string' ? $(selector) : selector ;

            element.notify(message, result ? 'success' : 'error');

        }

        //varsa ve olumluysa yönlendirme yapar
        if(result && href !== null){

            setTimeout(function(){

                $(location).attr('href', href);

            }, 2000);

        }

    },

    appendFormData: function(element = null, resim = null, ek = false){
        var form_data = new FormData();

        form_data.append(Function.csrf.data('name'), Function.csrf.data('hash'));

        if(element!==null){

            element.find('[name]').each(function(){

                form_data.append($(this).attr('name'), $(this).val());

            });

        }

        if(resim !== null){

            if(typeof resim === 'object' && !ek){

                $.each(resim, function( key, value ) {

                    form_data.append(value.name, value.file);

                });

            }else{

                form_data.append('resim', resim);

            }

        }

        return form_data;

    },

    dataTable: function(data, datatable, extraButtons = [], visibleId = false, addCheckbox = false, initComplete = null, hiddenColumns = []){
        let buttons = [
            {
                extend: 'pageLength',
                className : 'table-btn-secondary'
            },
            /*{
                extend: 'copy',
                text: 'Panoya Kopyala'
            },*/
            {
                extend: 'excel',
                text: 'Excel Çıktısı'
            },
            {
                extend: 'pdf',
                text: 'Pdf Çıktısı'
            },
        ];

        jQuery.merge(buttons, extraButtons);

        let columnDefs = [];

        //gizlenecek sütunlar varsa
        if (hiddenColumns.length > 0) {
            columnDefs.push({
                targets: hiddenColumns,
                visible: false,
                searchable: false
            });
        }

        if (visibleId) {
            columnDefs.push({
                visible: false,
                targets: 0,
            });
        }



        //checkbox eklenecekse
        if (addCheckbox) {
            columnDefs.push({
                data: null,
                defaultContent: '',
                orderable: false,
                className: 'select-checkbox',
                targets: 1
            });
        }

        let table = datatable.DataTable({
            responsive: true,
            lengthChange: false,
            buttons: buttons,//,'colvis'
            data:data,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 kayıt listele', '25 kayıt listele', '50 kayıt listele', 'Tümünü listele' ]
            ],
            columnDefs: columnDefs,
            order: [[ 1, 'asc' ]],
            select: {
                style:    'multi',//os
                selector: 'td:first-child'
            },
            "initComplete" : initComplete,
            //pageLength: 20,
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
                },
                buttons: {
                    pageLength: {
                        _: "%d kayıt listele",
                        '-1': "Tümünü listele"
                    }
                }
            }
        });

        table.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

        return table;

    },

    removeElement: function(url, val, type, element , tihis){

        Swal.fire({

            type : 'warning',

            title: 'Kayıt(lar) silinmek üzere!',

            text : 'Silmek istediğinizden emin misiniz?',

            //icon : 'warning',

            showCancelButton  : true,

            confirmButtonColor: '#3085d6',

            cancelButtonColor : '#d33',

            cancelButtonText  : 'Hayır',

            confirmButtonText : 'Evet, sil!'

        }).then((result) => {

            if(result.value){

                $.post(url, val, function(data){

                    if(data.result){

                        if(type === 'table'){

                            element.row( tihis.parents('tr')).remove().draw();

                        }else if(type === 'multitable'){

                            element.rows( { selected: true } ).remove().draw();

                        }else if(type === 'tag' || type === 'brand' || type === 'categorySub' || type === 'seller' ||type === 'variantDetail'){

                            element.remove();

                        }

                    }

                    Swal.fire(

                        data.result?'Başarılı!':'Başarısız!',

                        data.message,

                        data.result?'success':'danger'

                    );

                },'JSON');

            }

        });

    },

    fileChange: function(dosya, resim){

        dosya.change(function(){

            var input = this;

            if(input.files && input.files[0]){

                var reader = new FileReader();

                reader.onload = function (e) {

                    var image = new Image();

                    image.src = e.target.result;

                    image.onload = function() {

                        let css_array = Function.widthHeightControl(this.width,this.height);

                        resim
                            .css("width",css_array['width']+"px")
                            .css("height",css_array['height']+"px")
                            .css('background-image', 'url(' + this.src + ')');

                    };

                };

                reader.readAsDataURL(input.files[0]);

            }

        });
    },

    summernoteStart: function(element){

        element.summernote({

            height: 300,                 // set editor height

            minHeight: null,             // set minimum height of editor

            maxHeight: null,             // set maximum height of editor

            focus: false,                // set focus to editable area after initializing summernote

            callbacks: {

                onImageUpload: function(files) {

                    Function.summernoteUploadFile(element,files[0]);

                }

            }

        });

    },

    summernoteUploadFile: function(element, file) {
        let formData = new FormData();
        formData.append('file', file);
        $.ajax({
            url: '/process/summernoteResimEkle',
            dataType: 'JSON',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'POST',
            success: function(data) {

                if(data.result){

                    element.summernote('insertImage', data.path);

                }

                Function.info(data.result, data.message);

            }

        });

    },

    downloadURL: function(url){

        window.open(url, "_blank");
    },

    saveData: function(blob, filename){

        var a = document.createElement('a');

        document.body.appendChild(a);

        a.style = 'display: none';

        var url = window.URL.createObjectURL(blob);

        a.href = url;

        a.download = filename;

        a.click();

        window.URL.revokeObjectURL(url);
    },

    formControl: function($form, $resim = $('#resim')){

        let errMsg = '';
        $form.find('input[required]').each(function( index ) {
            let tihisReqiured = $(this);
            let type = tihisReqiured.attr('type');

            if(type === "file"){
                let bg_image = $resim.css('background-image').replace(/.*\s?url\([\'\"]?/, '').replace(/[\'\"]?\).*/, '');
                if(bg_image.substr(0,10) !== 'data:image'){
                    errMsg = 'Lütfen resmi boş bırakmayınız';
                    return false;
                }
            }else{
                if(tihisReqiured.val() === '' || typeof tihisReqiured.val() === 'undefined'){
                    errMsg = 'Lütfen zorunlu alanları doldurunuz '+tihisReqiured.data('description');
                    return false;
                }
            }
        });

        return errMsg;

    },

    /**
     * Random barcode
     */
    randBarcode: function(){

        return "869" + "1111" + (Math.floor(Math.random() * (99999 - 10000 + 1)) + 10000) +"1";

    },

};

//initialling
$(function(){

    Function.load();

});
