$('select').change(function(){
    let val = $(this).val();
    let path = `/authority/${val}`;
    window.location.href = path;
});

$('#onayla').click(function(){
    info("hide");
    let tihis = $(this);
    element_status(tihis,false);

    let value = {
        id:$("form").find("select option:selected").val(),
        option:$("form").serialize()
    };

    $.post( "/authority/transactionConstraint", value, function(data) {
        element_status(tihis,true);
        info(true,"Başarıyla Güncellendi");
    });
});