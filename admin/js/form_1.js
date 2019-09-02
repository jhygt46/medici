function form(that){
    
    var inputs = new Array();
    var selects = new Array();
    var textareas = new Array();
    var data = new FormData();
    var require = "";
    var func = "";
    var send = true;
    
    $(that).parents('form').find('input').each(function(){
        
        if($(this).attr('require')){
            require = $(this).attr('require').split(" ");
            for(var i=0; i<require.length; i++){

                func = require[i].split("-");
                if(func[0] == "email"){
                    if(!email($(this).val())){
                        send = false;
                        $(this).parent('label').find('.mensaje').html("No es un correo electronico");
                    }else{
                        $(this).parent('label').find('.mensaje').html("");
                    }
                }
                if(func[0] == "distnada"){
                    if(!distnada($(this).val())){
                        send = false;
                        $(this).parent('label').find('.mensaje').html("Debe completar este campo");
                    }else{
                        $(this).parent('label').find('.mensaje').html("");
                    }
                }
                if(func[0] == "distzero"){
                    if(!distzero($(this).val())){
                        send = false;
                        $(this).parent('label').find('.mensaje').html("Debe seleccionar una opcion");
                    }else{
                        $(this).parent('label').find('.mensaje').html("");
                    }
                }
                if(func[0] == "textma"){
                    if(!textma($(this).val(), func[1])){
                        send = false;
                        $(this).parent('label').find('.mensaje').html("Debe tener a lo menos "+func[1]+" caracteres");
                    }else{
                        $(this).parent('label').find('.mensaje').html("");
                    }
                }
                if(func[0] == "textme"){
                    if(!textme($(this).val(), func[1])){
                        send = false;
                        $(this).parent('label').find('.mensaje').html("Debe tener a lo mas "+func[1]+" caracteres");
                    }else{
                        $(this).parent('label').find('.mensaje').html("");
                    }
                }
            }
        }
        
        if($(this).attr('type') == "password"){
            data.append($(this).attr('id'), $(this).val());
            //inputs.push($(this));
        }
        if($(this).attr('type') == "text"){
            data.append($(this).attr('id'), $(this).val());
            //inputs.push($(this));
        }
        if($(this).attr('type') == "date"){
            data.append($(this).attr('id'), $(this).val());
            //inputs.push($(this));
        }
        if($(this).attr('type') == "hidden"){
            data.append($(this).attr('id'), $(this).val());
            //inputs.push($(this));
        }
        if($(this).attr('type') == "checkbox" && $(this).is(':checked')){
            data.append($(this).attr('id'), "1");
            //inputs.push($(this));
        }
        if($(this).attr('type') == "checkbox" && !$(this).is(':checked')){
            data.append($(this).attr('id'), "0");
        }
        if($(this).attr('type') == "radio" && $(this).is(':checked')){
            data.append($(this).attr('id'), $(this).val());
            //inputs.push($(this));
        }
        if($(this).attr('type') == "file"){
            var inputFileImage = document.getElementById($(this).attr('id'));
            for(var i=0; i<inputFileImage.files.length; i++){
                var file = inputFileImage.files[i];
                data.append($(this).attr('id'), file);
            }
        }
    });
    $(that).parents('form').find('select').each(function(){
        data.append($(this).attr('id'), $(this).val());
        //selects.push($(this));
    });
    $(that).parents('form').find('textarea').each(function(){
        data.append($(this).attr('id'), $(this).val());
        //textareas.push($(this));
    });
    
    if(send){
        $('.loading').show();
        $.ajax({
            url: "ajax/index.php",
            type: "POST",
            contentType: false,
            data: data,
            dataType: 'json',
            processData: false,
            cache: false,
            success: function(data){
                console.log(data);
                if(data != null){
                    if(data.reload == 1)
                        navlink('pages/'+data.page);
                    if(data.op != null)
                        mensaje(data.op, data.mensaje);
                }
            },
            error: function(e){
                console.log(e);
            }
        });
    }
    return false;
    
}
function mensaje(op, mens){
    
    if(op == 1){
        var type = "success";
        var timer = 2000;
    }
    if(op == 2){
        var type = "error";
        var timer = 5000;
    }
    Swal.fire({
        title: "",
        text: mens,
        html: true,
        timer: timer,
        type: type
    });
    
}
function confirm(message){
    
    Swal.fire({
        title: message['title'],
        text: message['text'],
        type: 'error',
        footer: '',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, borrarlo!'
    }).then((result)=>{
        if(result.value){
            var send = {accion: message['accion'], id: message['id'], nombre: message['name']};
            $.ajax({
                url: "ajax/index.php",
                type: "POST",
                data: send,
                success: function(data){
                    setTimeout(function(){
                        Swal.fire({
                            title: data.titulo,
                            text: data.texto,
                            type: data.tipo,
                            timer: 2000
                        })
                        if(data.reload)
                            navlink('pages/'+data.page);
                    }, 10);
                }, error: function(e){
                    console.log(e);
                }
            });
        }
    });

}