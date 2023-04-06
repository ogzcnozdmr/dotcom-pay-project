$(document).ready(function(){
    $(document).on('click','.cikti',function() {
        let tihis = $(this);
        let id = tihis.parent().parent().attr("o_id");
        var content = "Text for the generated pdf";
        var request = new XMLHttpRequest();
        request.open('POST', `pay/pdf/${id}`, true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        request.responseType = 'blob';
        request.onload = function() {
            if(request.status === 200) {
                var disposition = request.getResponseHeader('content-disposition');
                var matches = /"([^"]*)"/.exec(disposition);
                var filename = (matches != null && matches[1] ? matches[1] : 'file.pdf');
                var blob = new Blob([request.response], { type: 'application/pdf;charset=UTF-8' });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        };
        request.send();
    });
});