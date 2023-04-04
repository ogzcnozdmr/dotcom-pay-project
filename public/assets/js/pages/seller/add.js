$(document).ready(function(){
    $('#ekle').click(function(event){
        let tihis = $(this);
        element_status(tihis,false);

        let form = $("#bayi-ekle-form");
        if(form_required_control("bayi-ekle-form")){//zorunlu alanlar doldurulduysa
            let email = form.find("input[name=email]").val();
            if(email_type_control(email)){//email doğru
                //info(true,"Email doğru");
                let ksifre = form.find("input[name=ksifre]").val();
                let ksifre2 = form.find("input[name=ksifre2]").val();

                if(ksifre.length<8 || ksifre2.length<8){//şifre 8 karakterden küçükse
                    element_status(tihis,true);
                    info(false,"Şifreniz 8 karakterden küçük olamaz");
                }else{//şifre 8 karakterden büyükse
                    let values={
                        name:form.find("input[name=ad]").val(),
                        email:email,
                        username:form.find("input[name=kad]").val(),
                        phone:form.find("input[name=tel]").val(),
                        ksifre:ksifre,
                        ksifre2:ksifre2,
                        authority:form.find("select option:selected").val(),
                        authority_seller:$("form").find("input[type=checkbox]:checked").length
                    }
                    console.log(values);
                    $.post("settings/process.php",values,function(data){
                        console.log(data);
                        if(data.result===0){
                            element_status(tihis,true);
                            info(false,data.message);
                        }else if(data.result===1){
                            info(true,data.message);
                            setTimeout(function(){
                                $(location).attr('href','/seller/list')
                            }, 2000);
                        }
                    }, 'json');
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