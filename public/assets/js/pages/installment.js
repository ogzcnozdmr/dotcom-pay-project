$('select[name=banka]').change(function(){
    let val = $(this).val();
    let path = `/installment/${val}`;
    window.location.href = path;
});

$('#onayla').click(function(){
    info("hide");
    let tihis = $(this);
    element_status(tihis,false);
    let value = {
        id:$("form").find("select[name=banka] option:selected").val(),
        taksit:$("form").find("select[name=taksit] option:selected").val()
    };
    $.post("/installment/set", value, function(data) {
        if (data === 1) {
            info(true,"Başarıyla Güncellendi");
        } else if(data === 0) {
            info(false,"Güncellenemedi");
        }
        element_status(tihis,true);
    });
});