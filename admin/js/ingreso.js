function btn_login(){

    var btn = $('#login');
    btn.prop("disabled", true);
    $.ajax({
        url: "../admin/ajax/login_back.php",
        type: "POST",
        data: "accion=login&user="+$('#user').val()+"&pass="+$('#pass').val(),
        success: function(data){

            //console.log(data);
            if(data.op == 1){
                bien(data.message);
                setTimeout(function(){
                    $(location).attr('href','');
                }, 2000);
            }
            if(data.op == 2){
                mal(data.message);
                btn.prop("disabled", false);
            }
            
        },
        error: function(e){
            btn.prop("disabled", false);
        }
    });

}
function btn_recuperar(){
                
    var btn = $('#recuperar');
    btn.prop("disabled", true );
    $.ajax({
        url: "../admin/ajax/login_back.php",
        type: "POST",
        data: "accion=recuperar_password&user="+$('#correo').val(),
        success: function(data){

            //console.log(data);
            if(data.op == 1){
                bien(data.message);
                setTimeout(function () {
                    $(location).attr("href","/admin");
                }, 5000);
            }
            if(data.op == 2){
                mal(data.message);
                btn.prop("disabled", false);
            }

        },
        error: function(e){
            btn.prop("disabled", false);
        }
    });

}
function btn_nueva(){
                
    var btn = $('#nueva');
    btn.prop("disabled", true );
    $.ajax({
        url: "../admin/ajax/login_back.php",
        type: "POST",
        data: "accion=nueva_password&pass_01="+$('#pass_01').val()+"&pass_02="+$('#pass_02').val()+"&id_usr="+$('#id_usr').val()+"&code="+$('#code').val(),
        success: function(data){

            //console.log(data);
            if(data.op == 1){
                bien(data.message);
                setTimeout(function () {
                    $(location).attr("href","/admin");
                }, 2000);
            }
            if(data.op == 2){
                mal(data.message);
                btn.prop("disabled", false);
            }

        },
        error: function(e){
            btn.prop("disabled", false);
        }
    });

}
function bien(msg){
                
    $('.msg').html(msg);
    $('.msg').css("color", "#666");    
    $('#user').css("border-color", "#ccc");
    $('#pass').css("border-color", "#ccc");
    $('#user').css("background-color", "#fcfcfc");
    $('#pass').css("background-color", "#fcfcfc");

}
function mal(msg){   
    
    $('#pass').val("");
    $('.msg').html(msg);
    $('.msg').css("color", "#E34A25");
    $('#user').css("border-color", "#E34A25");
    $('#pass').css("border-color", "#E34A25");
    $('#user').css("background-color", "#FCEFEB");
    $('#pass').css("background-color", "#FCEFEB");
    login1();
    login2();
    login3();
    login2();
    login3();
    login2();
    login3();
    login4();
    
}
function login1(){
    $(".login").animate({
        'padding-left': '+=15px'
    }, 200);
}
function login2(){
    $(".login").animate({
        'padding-left': '-=30px'
    }, 200);
}
function login3(){
    $(".login").animate({
        'padding-left': '+=30px'
    }, 200);
}
function login4(){
    $(".login").animate({
        'padding-left': '-=15px'
    }, 200);
}