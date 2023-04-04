$(document).on("click", "#notification_approve", function() {
	let tihis = $(this);
    $.post( "/notification/approve", {}, function() {
    	console.log("Bildirimler silindi");
    });
});

function width_height_control(width,height){
    console.log(width+" - "+height);
    let new_width = 500;
    let new_height = 500;

    if(width>height){
        new_height = Math.ceil(height*new_width/width);
    }else if(height>width){
        new_width = Math.ceil(width*new_height/height);
    }
    console.log(new_width+" - "+new_height);
    var array = {                                
        width: new_width,
        height: new_height
    }
    return array;
}

function form_required_control(id){
	let required = 0;
	$("#"+id).find('input[required]').each(function(index,element){
	    var $element = $(element);
	    if($element.val()===''){
	      required = 1;
	      return false;
	    }
	});

	if(required==0) return true;
	else return false;
}

function element_status(id,type){
	console.log(typeof id);
	if(typeof id==="string"){
		if(type===true){
			$('#'+id).prop("disabled", false);
		}else if(type===false){
			$('#'+id).prop("disabled", true);
		}
	}else if(typeof id==="object"){
		if(type===true){
			id.prop("disabled", false);
		}else if(type===false){
			id.prop("disabled", true);
		}
	}
}

function info(result,message="",id="info"){
	if(result==="hide"){
		$("."+id).hide();
	}else if(result===true){
	    $("."+id).show(300).find("p").css("color","green").text(message);
	}else if(result===false){
	    $("."+id).show(300).find("p").css("color","red").text(message);
	}
}

function email_type_control(email){
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	var control =  re.test(String(email).toLowerCase());
	return control;
}