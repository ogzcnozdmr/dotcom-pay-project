$('#select_banka').change(function(){
    let val = $(this).val();
    let path = window.location.pathname+"?select="+val;
    window.location.href = path;
});

$('#onayla').click(function(){
    info("hide");
    let tihis = $(this);
    element_status(tihis,false);

    let value = {
        id:$("form").find("#select_banka option:selected").val(),
        option:$("form").find("input[type=checkbox]:checked").length,
        name:$("form").find("input[name=name]").val(),
        password:$("form").find("input[name=password]").val(),
        client_id:$("form").find("input[name=client_id]").val(),
        user_prov_id:$("form").find("input[name=user_prov_id]").val(),
        max_taksit:$("form").find("#max_taksit option:selected").val(),
        min_taksit_miktar:$("form").find("input[name=min_taksit_miktar]").val()
    };
    $.post( "/bank/settings", value, function(data) {
        element_status(tihis,true);
        info(true,"Başarıyla Güncellendi");
    });
});