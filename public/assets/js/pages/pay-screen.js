$(document).ready(function(){
    let min_taksit_miktar = "<?php echo $banka[0]['min_taksit_miktar']; ?>;"
    console.log("min = "+min_taksit_miktar);

    $('#sanal-satis-bilgileri input[name=tutar]').on("input",function(){
        let val = parseInt($(this).val());
        console.log("val = "+val+" min taksit = "+min_taksit_miktar);
        if(val>=min_taksit_miktar){
            $('#sanal-satis-bilgileri select[name=taksit]').parent().show();
        }else{
            $('#sanal-satis-bilgileri select[name=taksit]').parent().hide();
        }
    });

    $('select[name=banka]').change(function(){
        let val = $(this).find("option:selected").val();
        let values={
            post:'Max-taksit',
            banka:val
        }
        console.log(values);
        $.post("settings/process.php", values, function(data) {
            console.log(data);
            if(data.result==1){
                min_taksit_miktar = parseFloat(data.min);//yeni minimum taksit miktarını getiriyoruz

                //yeni gelen değer taksit değerinden küçükse, taksiti gizliyoruz
                if(parseFloat($('#sanal-satis-bilgileri input[name=tutar]').val())<min_taksit_miktar)
                    $('#sanal-satis-bilgileri select[name=taksit]').parent().hide();
                else if(parseFloat($('#sanal-satis-bilgileri input[name=tutar]').val())>=min_taksit_miktar)
                    $('#sanal-satis-bilgileri select[name=taksit]').parent().show();


                console.log(data.max);
                $('select[name=taksit]').empty();
                for(var i=1;i<=(data.max);i++){
                    var text = i == 1 ? 'Tek Çekim' : i;
                    $('select[name=taksit]').append(`
                            <option value="${i}">${text}</option>
                        `)
                }
            }
        }, 'json');
    });

    //$('#odeme-yap').click(function(){
    $(document).on('click','.odeme-yap',function(){
        info("hide");
        let tihis = $(this);
        element_status(tihis,false);

        let form_sanal_satis_bilgileri = $('#sanal-satis-bilgileri');
        let form_sanal_kart_bilgileri = $('#sanal-kart-bilgileri');
        let form_sanal_musteri_bilgileri = $('#sanal-musteri-bilgileri');

        if(form_required_control("sanal-satis-bilgileri") && form_required_control("sanal-kart-bilgileri") && form_required_control("sanal-musteri-bilgileri")){
            let tutar = form_sanal_satis_bilgileri.find("input[name=tutar]").val();
            let taksit = (parseFloat(tutar)>=parseFloat(min_taksit_miktar))?form_sanal_satis_bilgileri.find("select[name=taksit] option:selected").val():1;
            console.log("tutar "+tutar+" min taksit miktar ="+min_taksit_miktar+" taksit = "+taksit);
            let values={
                post:'Sanal-pos',
                satis:{
                    banka:form_sanal_satis_bilgileri.find("select[name=banka] option:selected").val(),
                    tutar:tutar,
                    taksit:taksit
                },
                kart:{
                    numara:form_sanal_kart_bilgileri.find("input[name=kart_no]").val(),
                    ad_soyad:form_sanal_kart_bilgileri.find("input[name=kart_ad_soyad]").val(),
                    son_kullanma:form_sanal_kart_bilgileri.find("input[name=kart_son_kul]").val(),
                    cvc:form_sanal_kart_bilgileri.find("input[name=kart_cvc]").val()
                },
                musteri:{
                    ad_soyad:form_sanal_musteri_bilgileri.find("input[name=musteri_ad]").val(),
                    email:form_sanal_musteri_bilgileri.find("input[name=musteri_email]").val(),
                    tel:form_sanal_musteri_bilgileri.find("input[name=musteri_tel]").val()
                }
            }

            let button_text = "İşlem Yapılıyor ";
            let sayac = 0;
            let loading = setInterval(() => {
                sayac++;
                if(sayac == 1) nokta = ".";
                else if(sayac == 2) nokta = "..";
                else if(sayac == 3){
                    nokta = "..."; sayac = 0;
                }
                $(".odeme-yap").text(button_text+nokta);
            },200);

            $.post("settings/process.php",values,function(data){
                console.log(data);

                clearInterval(loading);
                $(".odeme-yap").text("Ödeme Yap");

                if(data.result===1){
                    Swal.fire(
                        "Başarılı",
                        "Ödemeniz başarıyla gerçekleştirildi",
                        "success"
                    ).then(function(){
                        setTimeout(function(){
                            location.href="/panel/odemelerim.php";
                        },1500);
                    });
                }else if(data.result===0){
                    element_status(tihis,true);
                    Swal.fire(
                        "Başarısız",
                        data.message,
                        "error"
                    )
                }
            },"json");
            console.log(values);
        }else{
            info(false,"Lütfen bütün alanları doldurunuz");
            element_status(tihis,true);
        }
    });
});