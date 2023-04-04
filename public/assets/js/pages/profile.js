$('#guncelle').click(function() {
    info("hide");
    let tihis = $(this);
    element_status(tihis,false);
    let form = $("#profil-form");
    if (form_required_control("profil-form")) {
        let email = form.find("input[name=email]").val();
        if (email_type_control(email)) {
            let values={
                name:form.find("input[name=ad]").val(),
                email:email,
                phone:form.find("input[name=tel]").val()
            }
            console.log(values);
            $.post("profile/update",values,function(data) {
                console.log(data);
                if (data === "1") {
                    info(true,"Bilgileriniz başarıyla güncellendi");
                    element_status(tihis,true);
                } else {
                    info(false,"Bilgileriniz güncellenemedi");
                    element_status(tihis,true);
                }
            });
        } else {
            element_status(tihis,true);
            info(false,"Email adresini doğru giriniz");
        }
    } else {
        element_status(tihis,true);
        info(false,"Lütfen bütün alanları doldurunuz");
    }
});