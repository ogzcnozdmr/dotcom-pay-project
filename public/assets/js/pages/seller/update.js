$(document).ready(function(){
    let id = new URLSearchParams(window.location.search).get('id');
    $('#guncelle').click(function(event){
        let tihis = $(this);
        element_status(tihis,false);

        let form = $("#bayi-guncelle-form");
        if(form_required_control("bayi-guncelle-form")){//zorunlu alanlar doldurulduysa
            let email = form.find("input[name=email]").val();
            if(email_type_control(email)){//email doğru
                let ksifre = form.find("input[name=ksifre]").val();
                let ksifre2 = form.find("input[name=ksifre2]").val();
                let dogrulama = 0;

                if(ksifre!=="" || ksifre2!==""){
                    if(ksifre.length<8 || ksifre2.length<8){//şifre 8 karakterden küçükse
                        element_status(tihis,true);
                        info(false,"Şifreniz 8 karakterden küçük olamaz");
                    }else{
                        if(ksifre===ksifre2){
                            dogrulama=1;
                        }else{
                            element_status(tihis,true);
                            info(false,"Şifreler eşleşmiyor");
                        }
                    }
                }else{
                    dogrulama=1;
                }

                if(dogrulama === 1){
                    let values={
                        post:'Bayi-guncelle',
                        id:id,
                        ad:form.find("input[name=ad]").val(),
                        email:email,
                        kad:form.find("input[name=kad]").val(),
                        tel:form.find("input[name=tel]").val(),
                        ksifre:ksifre,
                        ksifre2:ksifre2,
                        yetki:form.find("select option:selected").val(),
                        yetkili_bayi:$("form").find("#yetkili_bayi:checked").length
                    }
                    console.log(values);
                    console.log("geldi");
                    $.post("settings/process.php",values,function(data){
                        console.log("geldi2");
                        console.log(data);
                        if(data.result===0){
                            element_status(tihis,true);
                            info(false,data.message);
                        }else if(data.result===1){
                            info(true,data.message);
                            setTimeout(function(){
                                $(location).attr('href','/panel/bayi-list.php')
                            }, 2000);
                        }
                    },"json");
                }
            }else{
                element_status(tihis,true);
                info(false,"Email adresini doğru giriniz");
            }
        }else{
            element_status(tihis,true);
            info(false,"Lütfen bütün alanları doldurunuz");
        }
        event.preventDefault();
    });
});