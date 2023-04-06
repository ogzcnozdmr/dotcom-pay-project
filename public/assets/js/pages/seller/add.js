$(document).ready(function(){
    $('#ekle').click(function(event){
        let tihis = $(this);
        element_status(tihis,false);
        let form = $("#bayi-ekle-form");
        if (form_required_control("bayi-ekle-form")) {//zorunlu alanlar doldurulduysa
            let email = form.find("input[name=email]").val();
            if (email_type_control(email)) {//email doğru
                let password = form.find("input[name=password]").val();
                let password2 = form.find("input[name=password2]").val();
                /*
                 * Şifre 8 karakterden küçükse
                 */
                if (password.length < 8 || password2.length < 8) {
                    element_status(tihis,true);
                    info(false,"Şifreniz 8 karakterden küçük olamaz");
                } else {
                    let values = {
                        name:form.find("input[name=name]").val(),
                        email,
                        username:form.find("input[name=username]").val(),
                        phone:form.find("input[name=phone]").val(),
                        password,
                        password2,
                        authority:form.find("select option:selected").val(),
                        authority_seller:$("form").find("input[type=checkbox]:checked").length
                    }
                    $.post("/seller/post/add", values, function(result) {
                        console.log(result);
                        if (!result.result) {
                            element_status(tihis,true);
                            info(false, result.message);
                        } else {
                            info(true, result.message);
                            setTimeout(function(){
                                $(location).attr('href','/seller')
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