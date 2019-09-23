var options = {
    x1: 5,
    y1: 2,
    x2: 10,
    y2: 1,
    x3: 25,
    y3: 0.05
}
var slidemenu = {
    activo: 0,
    tiempo: 0,
    startx: 0,
    starty: 0,
    posicionx: 0,
    posiciony: 0,
    moved: 0
}
var config = {
    total_dias: 14
}
var imap = false;
var semana = ["Lun", "Mar", "Mie", "Jue", "Vie", "Sab", "Dom"];
var mes = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
var fecha = new Date().getTime();

function inicio(){

    grecaptcha.ready(function(){
        grecaptcha.execute('6Lfor7kUAAAAABomMyYcaO0RhvHJBmPF85PrNP2v', {action: 'homepage'}).then(function(token){
            document.getElementById('token_contacto').value = token;
            document.getElementById('token_reserva').value = token;
        });
    });

    render_web();
    setTimeout(function(){ slider(0); }, 4000);

    document.addEventListener('dragstart', dragstart, false);
    document.addEventListener('drag', test, false);
    document.addEventListener('dragend', dragend, false);
    document.addEventListener('dragover', dragover, false);
    document.addEventListener('touchstart', dragstart, false);
    document.addEventListener('touchmove', test, false);
    document.addEventListener('touchend', dragend, false);
    document.getElementById('close').addEventListener('click', close);

    if(contacto > 0){
        show_map();
    }

    if(status == 2){
        ver_error();
    }
    if(status == 1){
        ver_success();
    }
    if(status == 0){
        var reserva = get_reserva();
        if(reserva.servicio == 0){
            ver_servicio();
        }else{
            seleccionar_servicio_id();
        }
        if(reserva.doctor == 0){
            ver_doctores();
        }else{
            seleccionar_doctor_id();
        }
        if(reserva.fecha == 0){
            ver_fechas();
        }else{
            seleccionar_fecha_id();
            if(reserva.hora == 0){
                ver_horas();
            }else{
                seleccionar_hora_id();
                //document.getElementById('pop_up').style.display = 'block';
            }
        }
    }

}
function render_web(){

    var nosotros = document.getElementById("nosotros");
    var nosotros2 = document.getElementById("nosotros2");
    var servicios = document.getElementById("servicios");

    for(var i=0, ilen=data.doctores.length; i<ilen; i++){
        nosotros.appendChild(create_nosotros_li(data.doctores[i]));
        nosotros2.appendChild(create_nosotros2_li(data.doctores[i]));
        servicios.appendChild(create_servicios_li(data.doctores[i]));
    }

}
function slider(n){

    var fotos = document.getElementById('fotos');
    var d = n % fotos.children.length;
    var j = (n + 1) % fotos.children.length;

    moveslider1(fotos.children[d], 15);
    moveslider2(fotos.children[j], 15);

    setTimeout(function(){ slider(j); }, 6000);

}
function moveslider1(div, v){

    var left = parseInt(div.style.left.replace("px", ""));
    if(left >= -850){

        var nleft = left - v;
        div.style.left = nleft + "px";
        setTimeout(function(){
            moveslider1(div, v);
        }, 10);

    }else{
        div.style.left = "850px";
    }

}
function moveslider2(div, v){
    
    var left = parseInt(div.style.left.replace("px", ""));
    if(left >= 0){

        var nleft = left - v;
        div.style.left = nleft + "px";
        setTimeout(function(){
            moveslider2(div, v);
        }, 10);

    }else{
        div.style.left = "0px";
    }

}
function dragover(e){
    e.preventDefault();
}
function dragstart(e){

    slidemenu.activo = 1;
    slidemenu.tiempo = new Date().getTime();

    if(e.touches){
        slidemenu.startx = e.touches[0].pageX;
        slidemenu.starty = e.touches[0].pageY;
    }else{
        slidemenu.startx = e.clientX;
        slidemenu.starty = e.clientY;
    }

}
function drag(e){

    e.preventDefault();

}
function btn_menu(that){

    var menu = that.parentElement.parentElement;
    var left = parseInt(menu.style.left.replace("px", ""));
    if(left < 0){
        abrir_x(menu, 'left', 10);
    }
    if(left == 0){
        cerrar_x(menu, 'left', 10);
    }
    
}
function dragend(){

    var searchEles = document.getElementById("contenedor").children;
    for(var i=0; i<searchEles.length; i++){
        if(!hasClass(searchEles[i], 'sitio')){
            if(searchEles[i].style.top == "0px"){
                // LATERALES
                if(searchEles[i].style.left != ""){
                    if(!hasClass(searchEles[i], 'blocked')){
                        accion_x(searchEles[i], 'left');
                    }
                }
                if(searchEles[i].style.right != ""){ 
                    //accion_x(searchEles[i], 'right');
                }
            }
            if(searchEles[i].style.left == "0px"){
                // SUPERIOR - INFERIOR
                if(searchEles[i].style.top != ""){ 
                    //console.log("Volver 1:");
                }
                if(searchEles[i].style.bottom != ""){ 
                    //console.log("Volver 2:");
                }
            }
        }
    }

}
function test(e){

    if(e.touches){
        slidemenu.posicionx = e.touches[0].pageX;
        slidemenu.posiciony = e.touches[0].pageY;
    }else{
        slidemenu.posicionx = e.clientX;
        slidemenu.posiciony = e.clientY;
    }

    slidemenu.velx = parseInt(Math.abs((slidemenu.startx - slidemenu.posicionx)*10/(new Date().getTime() - slidemenu.tiempo)));

    var searchEles = document.getElementById("contenedor").children;
    var move = 0;
    var movemen = 0;

    for(var i=0; i<searchEles.length; i++){
        if(!hasClass(searchEles[i], 'sitio')){
            if(searchEles[i].style.top == "0px"){
                // LATERAL - IZQUIERDO
                if(searchEles[i].style.left != ""){
                    if(!hasClass(searchEles[i], 'blocked')){
                        if(!hasClass(searchEles[i], 'open')){
                            move = move_left();
                            searchEles[i].style.left = move+"px";
                            movemen = 300 - ((280 + move)/280)*80;
                            document.getElementById("men").style.left = movemen+"px";
                        }else{
                            if(parseInt(-(slidemenu.startx - slidemenu.posicionx)) < -8){
                                addClass(searchEles[i], 'blocked');
                                cerrar_x(searchEles[i], 'left', 10);
                            }
                        }
                    }
                }
                if(searchEles[i].style.right != ""){
                    //move = move_right();
                    //searchEles[i].style.right = move+"px";
                }
            }
            if(searchEles[i].style.left == "0px"){
                // SUPERIOR - INFERIOR
                if(searchEles[i].style.top != ""){
                    //var top = parseInt(searchEles[i].style.top.replace("px", ""));
                    
                }
                if(searchEles[i].style.bottom != ""){
                    //var bottom = parseInt(searchEles[i].style.bottom.replace("px", ""));
                }
            }
        }
    }

}
function accion_x(div, dirx){

    var velocidad = parseInt(slidemenu.velx);
    if(velocidad < 3){
        velocidad = 3;
    }
    var por = slidemenu.velx - slidemenu.startx/window.innerWidth*10;
    var px = parseInt(div.style[dirx].replace("px", ""));
    if(px > -140 || por > 12){
        abrir_x(div, dirx, velocidad);
    }else{
        cerrar_x(div, dirx, velocidad);
    }

}
function abrir_x(div, dirx, x){

    var px = parseInt(div.style[dirx].replace("px", ""));
    if(px < 0){
        var l = px + x;
        if(l >= 0){
            l = 0;
            div.style[dirx] = l+"px";
            addClass(div, 'open');
            removeClass(div, 'blocked');
            document.getElementById("men").style.left = "220px";
        }else{
            div.style[dirx] = l+"px";
            var movemen = 300 - ((280 + l)/280)*80;
            document.getElementById("men").style.left = movemen+"px";
            setTimeout(function(){
                abrir_x(div, dirx, x);
            }, 10);
        }
    }else{
        addClass(div, 'open');
        removeClass(div, 'blocked');
    }

}
function cerrar_x(div, dirx, x){

    var px = parseInt(div.style[dirx].replace("px", ""));
    if(px > -280){
        var l = px - x;
        if(l <= -280){ 
            l = -280;
            div.style[dirx] = l+"px";
            removeClass(div, 'open');
            removeClass(div, 'blocked');
            document.getElementById("men").style.left = "300px";
        }else{
            div.style[dirx] = l+"px";
            var movemen = 300 - ((280 + l)/280)*80;
            document.getElementById("men").style.left = movemen+"px";
            setTimeout(function(){
                cerrar_x(div, dirx, x);
            }, 10);
        }
    }else{
        removeClass(div, 'open');
        removeClass(div, 'blocked');
    }

}
function addClass(div, classs){
    div.setAttribute("class", div.getAttribute("class")+" "+classs);
}
function removeClass(div, classs){
    var clase = div.getAttribute("class").split(" ");
    var aux = [];
    for(var i=0, ilen=clase.length; i<ilen; i++){
        if(clase[i] != classs){ 
            aux.push(clase[i]);
        }
    }
    div.setAttribute("class", aux.join(" "));
}
function hasClass(div, classs){
    var clase = div.getAttribute("class").split(" ");
    for(var i=0, ilen=clase.length; i<ilen; i++){
        if(clase[i] == classs){
            return true;
        }
    }
    return false;
}
function move_end_right(div){

    var px = parseInt(div.style.right.replace("px", ""));
    if(px > -280){
        var l = px - 10;
        if(l < -280){ l = -280; }
        div.style.right = l+"px";
        setTimeout(move_end_right(div), 10);
    }

}
function move_right(){

    var pon = 0;
    var dif = slidemenu.startx - slidemenu.posicionx;
    var por = 100 - slidemenu.startx/window.innerWidth*100;
    if(por < options.x1){
        pon = options.y1;
    }
    if(por >= options.x1 && por < options.x2){
        pon = ponderacion(por);
    }
    if(por >= options.x2){
        pon = options.y2;
    }
    var res = parseInt(-280 + ((dif) * pon));
    if(res <= 0){ 
        if(res >= -280){
            return res;
        }else{
            return -280;
        }
    }else{ 
        return 0;
    }

}
function move_left(){

    var pon = options.y2;
    var dif = - (slidemenu.startx - slidemenu.posicionx);
    var por = slidemenu.startx/window.innerWidth*100;
    if(por < options.x1){
        pon = options.y1;
    }
    if(por >= options.x1 && por < options.x2){
        pon = ponderacion(por);
    }
    if(por >= options.x2 && por < options.x3){
        pon = ponderacion2(por);
    }
    if(por >= options.x3){
        pon = options.y3;
    }
    var res = parseInt(-280 + ((dif) * pon));
    if(res <= 0){
        if(res >= -280){
            return res;
        }else{
            return -280;
        }
    }else{
        return 0;
    }

}
function ponderacion(x){
    return (((options.y2-options.y1)/(options.x2-options.x1))*(x-options.x1)) + options.y1;
}
function ponderacion2(x){
    return (((options.y3-options.y2)/(options.x3-options.x2))*(x-options.x2)) + options.y2;
}
function distacia(x1, y1, x2, y2){
    return Math.sqrt((x2-x1)*(x2-x1)+(y2-y1)*(y2-y1));
}
function close_hora(){
    
    document.getElementById("pre_hora_close").style.display = "none";
    document.getElementById("pre_hora_h1").innerHTML = "Hora";
    document.getElementById("pre_hora_h2").innerHTML = "";
    document.getElementsByName("f_hor")[0].value = 0;

    var reserva = get_reserva();
    reserva.hora = 0;
    set_reserva(reserva);

    ver_horas();

}
function close_fecha(){
    
    document.getElementById("pre_fecha_close").style.display = "none";
    document.getElementById("pre_fecha_h1").innerHTML = "Fecha";
    document.getElementById("pre_fecha_h2").innerHTML = "";
    document.getElementsByName("f_fec")[0].value = 0;

    document.getElementById("pre_hora_close").style.display = "none";
    document.getElementById("pre_hora_h1").innerHTML = "Hora";
    document.getElementById("pre_hora_h2").innerHTML = "";
    document.getElementsByName("f_hor")[0].value = 0;

    var reserva = get_reserva();
    reserva.fecha = 0;
    reserva.hora = 0;
    set_reserva(reserva);

    ver_fechas();

}
function close_servicio(){

    document.getElementById("pre_serv_close").style.display = "none";
    document.getElementById("pre_serv_h1").innerHTML = "Servicios";
    document.getElementById("pre_serv_h2").innerHTML = "";
    document.getElementsByName("id_ser")[0].value = 0;

    var reserva = get_reserva();
    reserva.servicio = 0;
    set_reserva(reserva);

    ver_servicio();

}
function close_doctor(){

    document.getElementById("pre_doc_close").style.display = "none";
    document.getElementById("pre_doc_h1").innerHTML = "Doctor";
    document.getElementById("pre_doc_h2").innerHTML = "";
    document.getElementsByName("id_usr")[0].value = 0;
    
    var reserva = get_reserva();
    reserva.doctor = 0;
    set_reserva(reserva);

    ver_doctores();

}
function seleccionar_servicio_id(){

    var reserva = get_reserva();
    for(var i=0, ilen=data.servicios.length; i<ilen; i++){
        if(data.servicios[i].id == reserva.servicio){
            document.getElementsByName("id_ser")[0].value = parseInt(reserva.servicio);
            document.getElementById("pre_serv_h1").innerHTML = data.servicios[i].nombre;
            document.getElementById("pre_serv_h2").innerHTML = "servicio";
            document.getElementById("pre_serv_close").style.display = "block";
        }
    }

}
function seleccionar_servicio(that){

    var id = that.getAttribute('id');
    for(var i=0, ilen=data.servicios.length; i<ilen; i++){
        if(data.servicios[i].id == id){

            var reserva = get_reserva();
            reserva.servicio = parseInt(id);
            set_reserva(reserva);

            document.getElementsByName("id_ser")[0].value = parseInt(id);
            document.getElementById("pre_serv_h1").innerHTML = data.servicios[i].nombre;
            document.getElementById("pre_serv_h2").innerHTML = "servicio";
            document.getElementById("pre_serv_close").style.display = "block";

            if(reserva.doctor == 0){
                ver_doctores();
            }else if(reserva.fecha == 0){
                ver_fechas();
            }else{
                ver_horas();
            }

        }
    }

}
function seleccionar_doctor_id(){

    var reserva = get_reserva();
    for(var i=0, ilen=data.doctores.length; i<ilen; i++){
        if(data.doctores[i].id == reserva.doctor){
            document.getElementsByName("id_usr")[0].value = parseInt(reserva.doctor);
            document.getElementById("pre_doc_h1").innerHTML = data.doctores[i].nombre;
            document.getElementById("pre_doc_h2").innerHTML = "doctor";
            document.getElementById("pre_doc_close").style.display = "block";
        }
    }
    
}
function seleccionar_doctor(that){

    var id = that.getAttribute('id');
    for(var i=0, ilen=data.doctores.length; i<ilen; i++){
        if(data.doctores[i].id == id){

            var reserva = get_reserva();
            reserva.doctor = parseInt(id);
            set_reserva(reserva);

            document.getElementsByName("id_usr")[0].value = parseInt(id);
            document.getElementById("pre_doc_h1").innerHTML = data.doctores[i].nombre;
            document.getElementById("pre_doc_h2").innerHTML = "doctor";
            document.getElementById("pre_doc_close").style.display = "block";

            if(reserva.servicio == 0){
                ver_servicio();
            }else if(reserva.fecha == 0){
                ver_fechas();
            }else{
                ver_horas();
            }

        }
    }

}
function seleccionar_fecha(y, m, d){
    
    document.getElementById("pre_fecha_h1").innerHTML = d+" "+mes[m]+" "+y;
    document.getElementById("pre_fecha_h2").innerHTML = "fecha";
    document.getElementById("pre_fecha_close").style.display = "block";

    var aux_mes = (m + 1 < 10) ? "0"+(m+1) : (m+1) ;
    var aux_dia = (d < 10) ? "0"+d : d ;

    var reserva = get_reserva();
    reserva.fecha = d+"-"+(m+1)+"-"+y;
    set_reserva(reserva);

    document.getElementsByName("f_fec")[0].value = y+"-"+aux_mes+"-"+aux_dia;
    ver_horas();

}
function seleccionar_fecha_id(){
    
    var reserva = get_reserva();
    var fecha = reserva.fecha.split("-");
    var d = parseInt(fecha[0]);
    var m = parseInt(fecha[1]) - 1;
    var y = parseInt(fecha[2]);

    document.getElementById("pre_fecha_h1").innerHTML = d+" "+mes[m]+" "+y;
    document.getElementById("pre_fecha_h2").innerHTML = "fecha";
    document.getElementById("pre_fecha_close").style.display = "block";
    document.getElementsByName("f_fec")[0].value = y+"-"+(m+1)+"-"+d;

}
function seleccionar_hora(that){

    var hora = that.getAttribute('id');

    var reserva = get_reserva();
    reserva.hora = parseInt(hora);
    set_reserva(reserva);

    var cero = (parseInt(reserva.hora%60) < 10) ? "0"+(reserva.hora%60) : (reserva.hora%60) ;
    document.getElementById("pre_hora_h1").innerHTML = parseInt(reserva.hora/60)+":"+cero;
    document.getElementById("pre_hora_h2").innerHTML = "Hora";
    document.getElementById("pre_hora_close").style.display = "block";
    document.getElementsByName("f_hor")[0].value = parseInt(hora);
    document.getElementById('pop_up').style.display = 'block';

}
function seleccionar_hora_id(){

    var reserva = get_reserva();

    var cero = (parseInt(reserva.hora%60) < 10) ? "0"+(reserva.hora%60) : (reserva.hora%60) ;
    document.getElementById("pre_hora_h1").innerHTML = parseInt(reserva.hora/60)+":"+cero;
    document.getElementById("pre_hora_h2").innerHTML = "Hora";
    document.getElementById("pre_hora_close").style.display = "block";
    document.getElementsByName("f_hor")[0].value = parseInt(reserva.hora);

}
function close(){
    document.getElementById('pop_up').style.display = 'none';
}
function close2(){
    document.getElementById('pop_up2').style.display = 'none';
}
function sitio_contacto(){

    var sitios = document.getElementsByClassName("sitio_pagina");
    sitios[0].style.display = 'none';
    sitios[1].style.display = 'none';
    sitios[2].style.display = 'none';
    sitios[3].style.display = 'block';
    sitios[4].style.display = 'none';
    show_map();

}
function show_map(){

    if(!imap){
        
        var myLatLng = { lat: -33.439834, lng: -70.616914 };
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: myLatLng
        });
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Hello World!'
        });
        imap = true;
        
    }

}
function sitio_contacto_(that){

    var menu = that.parentElement.parentElement.parentElement.parentElement.parentElement;
    cerrar_x(menu, 'left', 15);
    var sitios = document.getElementsByClassName("sitio_pagina");
    sitios[0].style.display = 'none';
    sitios[1].style.display = 'none';
    sitios[2].style.display = 'none';
    sitios[3].style.display = 'block';
    sitios[4].style.display = 'none';

    if(!imap){
        
        var myLatLng = { lat: -33.439834, lng: -70.616914 };
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: myLatLng
        });
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Hello World!'
        });
        imap = true;
        
    }

}
function sitio_reservar(){

    var sitios = document.getElementsByClassName("sitio_pagina");
    sitios[0].style.display = 'block';
    sitios[1].style.display = 'none';
    sitios[2].style.display = 'none';
    sitios[3].style.display = 'none';
    sitios[4].style.display = 'none';

}
function sitio_reservar_(that){

    var menu = that.parentElement.parentElement.parentElement.parentElement.parentElement;
    cerrar_x(menu, 'left', 15);
    var sitios = document.getElementsByClassName("sitio_pagina");
    sitios[0].style.display = 'block';
    sitios[1].style.display = 'none';
    sitios[2].style.display = 'none';
    sitios[3].style.display = 'none';
    sitios[4].style.display = 'none';

}
function sitio_nosotros(){

    var sitios = document.getElementsByClassName("sitio_pagina");
    sitios[0].style.display = 'none';
    sitios[1].style.display = 'none';
    sitios[2].style.display = 'block';
    sitios[3].style.display = 'none';
    sitios[4].style.display = 'none';

}
function sitio_nosotros_(that){

    var menu = that.parentElement.parentElement.parentElement.parentElement.parentElement;
    cerrar_x(menu, 'left', 15);
    var sitios = document.getElementsByClassName("sitio_pagina");
    sitios[0].style.display = 'none';
    sitios[1].style.display = 'none';
    sitios[2].style.display = 'block';
    sitios[3].style.display = 'none';
    sitios[4].style.display = 'none';

}
function sitio_inicio(){

    var sitios = document.getElementsByClassName("sitio_pagina");
    sitios[0].style.display = 'none';
    sitios[1].style.display = 'block';
    sitios[2].style.display = 'none';
    sitios[3].style.display = 'none';
    sitios[4].style.display = 'none';

}
function sitio_inicio_(that){

    var menu = that.parentElement.parentElement.parentElement.parentElement.parentElement;
    cerrar_x(menu, 'left', 15);
    var sitios = document.getElementsByClassName("sitio_pagina");
    sitios[0].style.display = 'none';
    sitios[1].style.display = 'block';
    sitios[2].style.display = 'none';
    sitios[3].style.display = 'none';
    sitios[4].style.display = 'none';

}
function sitio_servicios(){

    var sitios = document.getElementsByClassName("sitio_pagina");
    sitios[0].style.display = 'none';
    sitios[1].style.display = 'none';
    sitios[2].style.display = 'none';
    sitios[3].style.display = 'none';
    sitios[4].style.display = 'block';

}
function sitio_servicios_(that){

    var menu = that.parentElement.parentElement.parentElement.parentElement.parentElement;
    cerrar_x(menu, 'left', 15);
    var sitios = document.getElementsByClassName("sitio_pagina");
    sitios[0].style.display = 'none';
    sitios[1].style.display = 'none';
    sitios[2].style.display = 'none';
    sitios[3].style.display = 'none';
    sitios[4].style.display = 'block';

}
function ver_success(){

    var reserva = get_reserva();
    if(reserva.servicio == 0 && reserva.doctor == 0 && reserva.fecha == 0 && reserva.hora == 0){
        window.location.href = "./";
    }

    if(reserva.servicio > 0){ seleccionar_servicio_id(); }
    if(reserva.doctor > 0){ seleccionar_doctor_id(); }
    if(reserva.fecha != 0){ seleccionar_fecha_id(); }
    if(reserva.hora > 0){ seleccionar_hora_id(); }

    set_reserva(reserva_blank());

    document.getElementById("info").innerHTML = "";
    var aux = create_element_class('cont_info vhalign');

    var fecha = reserva.fecha.split("-");
    var dia = (parseInt(fecha[0]) < 10) ? "0"+parseInt(fecha[0]) : parseInt(fecha[0]) ;
    var m = mes[fecha[1]-1];

    var st = create_element_class_inner('success_titulo', 'Reserva realizada!');
    var sd = create_element_class_inner('success_descripcion', 'Reserva para la fecha: '+dia+' de '+m+' de '+fecha[2]+' a las '+str_hora(reserva.hora)+' con el medico '+str_doctor(reserva.doctor));
    var sb = create_element_class_inner('success_bajada', 'Le hemos enviado un correo para confirmar reserva');

    aux.appendChild(st);
    aux.appendChild(sd);
    aux.appendChild(sb);

    document.getElementById("info").appendChild(aux);

}
function ver_error(){
        
    var reserva = get_reserva();
    reserva.fecha = 0;
    reserva.hora = 0;
    set_reserva(reserva);

    document.getElementsByClassName("m_error")[0].innerHTML = "Lo sentimos, se ha producido un error al reservar la hora, porfavor vuelva a intentarlo";
    document.getElementsByClassName("m_error")[0].style.display = "block";

    if(reserva.servicio > 0){ seleccionar_servicio_id(); }
    if(reserva.doctor > 0){ seleccionar_doctor_id(); }
    if(reserva.fecha > 0){ seleccionar_fecha_id(); ver_horas(); }else{ ver_fechas(); }

}
function ver_servicio(){

    document.getElementById("info").innerHTML = "";
    var aux = create_element_class('cont_info');
    var item = "";
    var titulo = create_element_class_inner('info_titulo', 'Seleccionar servicio:');
    aux.appendChild(titulo);
    var subtitulo = create_element_class_inner('info_subtitulo', 'Elija entre alguna de nuestras alternativas');
    aux.appendChild(subtitulo);
    var lista = create_element_class('list_servicios');
    var reserva = get_reserva();

    if(reserva.doctor == 0){
        var servicios = data.servicios;
    }else{
        for(var i=0, ilen=data.doctores.length; i<ilen; i++){
            if(data.doctores[i].id == reserva.doctor){
                var servicios = data.doctores[i].lista_servicios;
            }
        }
    }

    for(var i=0, ilen=servicios.length; i<ilen; i++){
        if(servicios[i].id == reserva.servicio){
            item = create_element_class_inner('servicio selected', servicios[i].nombre);
        }else{
            item = create_element_class_inner('servicio', servicios[i].nombre);
        }
        item.setAttribute('id', servicios[i].id);
        item.onclick = function(){ seleccionar_servicio(this) };
        lista.appendChild(item);
    }

    aux.appendChild(lista);
    document.getElementById("info").appendChild(aux);

}
function ver_doctores(){

    document.getElementById("info").innerHTML = "";
    var aux = create_element_class('cont_info');
    var item = "";
    var titulo = create_element_class_inner('info_titulo', 'Seleccionar doctor:');
    aux.appendChild(titulo);
    var subtitulo = create_element_class_inner('info_subtitulo', 'Elija entre alguna de nuestras alternativas');
    aux.appendChild(subtitulo);
    var lista = create_element_class('list_doctores');
    var reserva = get_reserva();

    if(reserva.servicio == 0){
        var doctores = data.doctores;
    }else{
        for(var i=0, ilen=data.servicios.length; i<ilen; i++){
            if(data.servicios[i].id == reserva.servicio){
                var doctores = data.servicios[i].lista_doctores;
            }
        }
    }

    for(var i=0, ilen=doctores.length; i<ilen; i++){

        if(doctores[i].id == reserva.doctor){
            item = create_element_class_inner('doctor selected', doctores[i].nombre);
        }else{
            item = create_element_class_inner('doctor', doctores[i].nombre);
        }
        item.setAttribute('id', doctores[i].id);
        item.onclick = function(){ seleccionar_doctor(this) };
        lista.appendChild(item);

    }
    aux.appendChild(lista);
    document.getElementById("info").appendChild(aux);

}
function ver_fechas(){

    var reserva = get_reserva();
    if(reserva.servicio > 0 && reserva.doctor > 0){

        document.getElementById("info").innerHTML = "";
        var aux = create_element_class('cont_fecha clearfix');
        aux.appendChild(html_calendario());
        aux.appendChild(html_detalle());
        document.getElementById("info").appendChild(aux);

    }else{

        if(reserva.servicio == 0){
            ver_servicio();
        }else{
            if(reserva.doctor == 0){
                ver_doctores();
            }
        }

    }

}
function ver_horas(){

    document.getElementById("info").innerHTML = "";
    var aux = create_element_class('cont_fecha clearfix');
    aux.appendChild(html_horas());
    aux.appendChild(html_detalle());
    document.getElementById("info").appendChild(aux);

}
function tiene_excepcion(date){

    var y = date.getFullYear()+"-";
    var aux_m = date.getMonth() + 1;
    y+= (aux_m < 10) ? "0"+aux_m+"-" : aux_m+"-" ;
    y+= (date.getDate() < 10) ? "0"+date.getDate() : date.getDate() ;

    var obj = {
        op: false,
        excepciones: []
    }

    var reserva = get_reserva();

    if(Array.isArray(data.excepciones)){
        for(var i=0, ilen=data.excepciones.length; i<ilen; i++){
            if(data.excepciones[i].fecha == y && data.excepciones[i].id_usr == reserva.doctor && in_array(data.excepciones[i].lista_servicios, reserva.servicio)){
                obj.op = true;
                obj.excepciones.push(data.excepciones[i]);
            }
        }
    }
    return obj;

}
function dia_reglas(regla){

    var hi = [], hf = [], aux_ini = [], horas = [], lista_servicios = [];
    var h_ini = 0, h_fin = 0, aux_i = 0, aux_f = 0, last = 0;
    var reserva = get_reserva();

    for(var x=0, xlen=regla.length; x<xlen; x++){

        hi = regla[x].hora_ini.split(":");
        hf = regla[x].hora_fin.split(":");

        h_ini = parseInt(hi[0]) * 60 + parseInt(hi[1]);
        h_fin = parseInt(hf[0]) * 60 + parseInt(hf[1]);

        for(var j=0, jlen=data.doctores.length; j<jlen; j++){
            if(data.doctores[j].id == reserva.doctor){

                lista_servicios = data.doctores[j].lista_servicios;
                for(var i=0, ilen=lista_servicios.length; i<ilen; i++){
                    if(lista_servicios[i].id == reserva.servicio){
                        tiempo = parseInt(lista_servicios[i].tiempo_min);
                    }
                }
                horas = data.doctores[j].horas;
                if(Array.isArray(horas)){
                    for(var i=0, ilen=horas.length; i<ilen; i++){
                        
                        aux_ini = horas[i].fecha.split(" ")[1].split(":");
                        aux_i = parseInt(aux_ini[0] * 60) + parseInt(aux_ini[1]);
                        aux_f = aux_i + parseInt(horas[i].tiempo);

                        if(aux_i >= h_ini && aux_f <= h_fin){
                            if(i == 0){
                                if(aux_i > h_ini + tiempo){ return true }
                            }
                            if(i > 0){
                                if(aux_i >= last + tiempo){ return true }   
                            }
                            if(i == ilen - 1){
                                if(aux_f + tiempo < h_fin){ return true }
                            }
                            last = aux_f;
                        }

                    }
                }else{
                    return true;
                }
            }
        }
    }
    return false;

}
function horas_disponibles(y, m, d){

    var date = new Date(y, m, d);
    var exc = tiene_excepcion(date);
    var reserva = get_reserva();

    if(exc.op){
        return dia_reglas(exc.excepciones);
    }else{
        var semana = date.getDay();
        var rangos = [];
        for(var i=0, ilen=data.rangos.length; i<ilen; i++){
            if(data.rangos[i].dia_ini <= semana && data.rangos[i].dia_fin >= semana && data.rangos[i].id_usr == reserva.doctor && in_array(data.rangos[i].lista_servicios, reserva.servicio)){
                rangos.push(data.rangos[i]);
            }
        }
        return dia_reglas(rangos);
    }

}
function in_array(arr, x){
    for(var i=0, ilen=arr.length; i<ilen; i++){
        if(arr[i] == x){
            return true;
        }
    }
    return false;
}
function html_horas(){

    var reserva = get_reserva();
    var html_hora = "";
    var fecha = reserva.fecha.split("-");
    fecha[0] = (parseInt(fecha[0]) < 10) ? "0"+fecha[0] : fecha[0] ;
    fecha[1] = (parseInt(fecha[1]) < 10) ? "0"+fecha[1] : fecha[1] ;
    var date = new Date(fecha[2], (fecha[1] - 1), fecha[0]);
    var exc = tiene_excepcion(date);
    var horas = [];

    if(exc.op){
        horas = horas_reglas(exc.excepciones, fecha);
    }else{
        var semana = date.getDay();
        var rangos = [];
        for(var i=0, ilen=data.rangos.length; i<ilen; i++){
            if(data.rangos[i].dia_ini <= semana && data.rangos[i].dia_fin >= semana && data.rangos[i].id_usr == reserva.doctor && in_array(data.rangos[i].lista_servicios, reserva.servicio)){
                rangos.push(data.rangos[i]);
            }
        }
        horas = horas_reglas(rangos, fecha);
    }

    var html_horas = create_element_class('horas');
    var cont_hrs = create_element_class('cont_hrs');
    var titulo_hrs = create_element_class_inner('titulo_hrs', 'Seleccione hora');
    cont_hrs.appendChild(titulo_hrs);
    var lista_hrs = create_element_class('lista_hrs');

    for(var i=0, ilen=horas.length; i<ilen; i++){
        
        var min = (parseInt(horas[i].m%60) < 10) ? "0"+parseInt(horas[i].m%60) : parseInt(horas[i].m%60) ;
        var hr = (parseInt(horas[i].m/60) < 10) ? "0"+parseInt(horas[i].m/60) : parseInt(horas[i].m/60) ;       
        if(horas[i].p == 0){
            
            html_hora = create_element_class('hora');
            var dtl = create_element_class_inner('dtl valign', hr+':'+min);
            var reserv = create_element_class_inner('reserva valign', 'reservar');
            
            html_hora.appendChild(dtl);
            html_hora.appendChild(reserv);
            html_hora.setAttribute('id', horas[i].m);
            html_hora.onclick = function(){ seleccionar_hora(this) };

            lista_hrs.appendChild(html_hora);

        }
        if(horas[i].p == 1){

            html_hora = create_element_class('hora');
            var dtl = create_element_class_inner('dtl valign', hr+':'+min);
            var reserv = create_element_class_inner('reserva valign', 'Reservado..');
            
            html_hora.appendChild(dtl);
            html_hora.appendChild(reserv);
            lista_hrs.appendChild(html_hora);

        }
        if(horas[i].p == 2){

            html_hora = create_element_class('hora');
            var dtl = create_element_class_inner('dtl valign', hr+':'+min);
            var reserv = create_element_class_inner('reserva valign', 'Fuera de Rango..');
            
            html_hora.appendChild(dtl);
            html_hora.appendChild(reserv);
            lista_hrs.appendChild(html_hora);

        }

    }

    cont_hrs.appendChild(lista_hrs);
    html_horas.appendChild(cont_hrs);
    return html_horas;

}
function in_regla(reglas, min, time){

    var min = min;
    var max = min + time;
    var aux = 0;
    if(reglas.length > 0){
        for(var x=0, xlen=reglas.length; x<xlen; x++){
            hi = reglas[x].hora_ini.split(":");
            hf = reglas[x].hora_fin.split(":");
            h_ini = parseInt(hi[0]) * 60 + parseInt(hi[1]);
            h_fin = parseInt(hf[0]) * 60 + parseInt(hf[1]);
            if(h_ini <= min && h_fin >= max){
                return 1;
            }else{
                aux = aux + 1;
            }
        }
        if(reglas.length == aux){
            return 2;
        }
    }

}
function str_doctor(id){
    for(var i=0, ilen=data.doctores.length; i<ilen; i++){
        if(data.doctores[i].id == id){
            return data.doctores[i].nombre;
        }
    }
}
function str_hora(hora){
    var hr = (parseInt(hora/60) < 10) ? "0"+parseInt(hora/60) : parseInt(hora/60) ;
    var min = (parseInt(hora%60) < 10) ? "0"+parseInt(hora%60) : parseInt(hora%60) ;
    return hr+":"+min;
}
function horas_dia(horas, fecha){

    var ret = [];
    if(Array.isArray(horas)){
        for(var i=0, ilen=horas.length; i<ilen; i++){
            var dia = horas[i].fecha.split(" ")[0].split("-");
            if(dia[0] == fecha[2] && dia[1] == fecha[1] && dia[2] == fecha[0]){
                ret.push(horas[i]);
            }
        }
    }
    return ret;

}
function horas_reglas(reglas, fecha){

    var min=9999999, max=0, tiempo=30, hr_ini=0, hr_fin=0, aux=[], lista_servicios=[], hr_last=0, res=[], tiempo_servicio=0, dia=0;
    var reserva = get_reserva();

    if(reglas.length > 0){
        for(var x=0, xlen=reglas.length; x<xlen; x++){
            hi = reglas[x].hora_ini.split(":");
            hf = reglas[x].hora_fin.split(":");
            h_ini = parseInt(hi[0]) * 60 + parseInt(hi[1]);
            h_fin = parseInt(hf[0]) * 60 + parseInt(hf[1]);
            if(h_ini < min){ min = h_ini; }
            if(h_fin > max){ max = h_fin; }
        }
    }

    for(var j=0, jlen=data.doctores.length; j<jlen; j++){
        if(data.doctores[j].id == reserva.doctor){
            
            lista_servicios = data.doctores[j].lista_servicios;
            for(var i=0, ilen=lista_servicios.length; i<ilen; i++){
                if(lista_servicios[i].id == reserva.servicio){
                    tiempo_servicio = parseInt(lista_servicios[i].tiempo_min);
                }
            }

            var horas = horas_dia(data.doctores[j].horas, fecha);
            if(horas.length > 0){

                for(var i=0, ilen=horas.length; i<ilen; i++){

                    aux = horas[i].fecha.split(" ")[1].split(":");

                    hr_ini = parseInt(aux[0] * 60) + parseInt(aux[1]);
                    hr_fin = hr_ini + parseInt(data.doctores[j].horas[i].tiempo);
                    
                    if(i == 0){
                        while(min <= hr_ini - tiempo){
                            var inregla = in_regla(reglas, min, tiempo_servicio);
                            if(inregla == 1){ res.push({ m: min, p: 0 }); }
                            if(inregla == 2){ res.push({ m: min, p: 2 }); }
                            min += tiempo;
                        }
                        res.push({ m: hr_ini, p: 1 });
                    }
                    if(i > 0){
                        aux_ini = hr_last;
                        while(hr_ini - aux_ini >= tiempo){
                            var inregla = in_regla(reglas, aux_ini, tiempo_servicio);
                            if(inregla == 1){ res.push({ m: aux_ini, p: 0 }); }
                            if(inregla == 2){ res.push({ m: aux_ini, p: 2 }); }
                            aux_ini += tiempo;
                        }
                        res.push({ m: hr_ini, p: 1 });
                    }
                    if(i == ilen - 1){
                        while(hr_fin <= max - tiempo){
                            var inregla = in_regla(reglas, hr_fin, tiempo_servicio);
                            if(inregla == 1){ res.push({ m: hr_fin, p: 0 }); }
                            if(inregla == 2){ res.push({ m: hr_fin, p: 2 }); }
                            hr_fin += tiempo;
                        }
                    }
                    hr_last = hr_fin;

                }

            }else{

                // MOSTRAR TODAS LAS HORAS
                while(min <= max - tiempo){
                    var inregla = in_regla(reglas, min, tiempo_servicio);
                    if(inregla == 1){ res.push({ m: min, p: 0 }); }
                    if(inregla == 2){ res.push({ m: min, p: 2 }); }
                    min += tiempo;
                }

            }
        }
    }
    return res;

}
function html_calendario(){

    var now = new Date(n_year, n_month, n_day);
    var then = new Date(now.getTime()+1000*60*60*24*config.total_dias);
    var calendario = create_element_class('calendario');

    if(now.getMonth() == then.getMonth()){
        calendario.innerHTML = calendario_completo(now);
    }else{
        calendario.innerHTML = calendario_por_mitades(now, then);
    }
    return calendario;

}
function html_detalle(){
    
    var detalle = create_element_class('detalle');
    var cont_dtl = create_element_class('cont_dtl');
    var reserva = get_reserva();

    for(var i=0, ilen=data.servicios.length; i<ilen; i++){
        if(data.servicios[i].id == reserva.servicio){
            var dtl_titulo = create_element_class_inner('dtl_titulo', data.servicios[i].nombre);
            var dtl_descripcion = create_element_class_inner('dtl_descripcion', data.servicios[i].descripcion);
            cont_dtl.appendChild(dtl_titulo);
            cont_dtl.appendChild(dtl_descripcion);
            for(var j=0, jlen=data.servicios[i].lista_doctores.length; j<jlen; j++){
                if(data.servicios[i].lista_doctores[j].id == reserva.doctor){
                    var dtl_doctor = create_element_class_inner('dtl_doctor', data.servicios[i].lista_doctores[j].nombre);
                    var dtl_precio = create_element_class_inner('dtl_precio', data.servicios[i].lista_doctores[j].precio);
                    var dtl_tiempo = create_element_class_inner('dtl_tiempo', data.servicios[i].lista_doctores[j].tiempo_min);
                    cont_dtl.appendChild(dtl_doctor);
                    cont_dtl.appendChild(dtl_precio);
                    cont_dtl.appendChild(dtl_tiempo);
                }
            }
        }
    }

    detalle.appendChild(cont_dtl);
    return detalle;

}
function calendario_por_mitades(now, then){
    
    var p_dia_m1 = (new Date(now.getFullYear(), now.getMonth()).getDay() == 0) ? 6 : new Date(now.getFullYear(), now.getMonth()).getDay() - 1 ;
    var aux_month = now.getMonth();
    var count = 0;
    var day_count = 0;
    var aux_cont = 0;
    var aux_blanc = 0;
    var total = 0;
    var data = "<div class='table_calendar'><div class='calendar_titulo'>"+mes[now.getMonth()]+" - "+now.getFullYear()+"</div><div class='row_calendar clearfix'>";
    for(var i=0, ilen=semana.length; i<ilen; i++){
        data += "<div class='calendar_semana'><div class='semana_info vhalign'>"+semana[i]+"</div></div>";
    }
    data += "</div><div class='row_calendar clearfix'>";
    while(now.getMonth() == aux_month){
        if(p_dia_m1 > count){
            data += "<div class='calendar_dia2'></div>";
        }else{
            day_count++;
            if(day_count < now.getDate()){
                if(aux_blanc == 0){
                    data += "<div class='calendar_dia2'></div>";
                }
                if(aux_blanc == 1){
                    data += "<div class='calendar_dia3'></div>";
                }
            }else{
                if(horas_disponibles(now.getFullYear(), now.getMonth(), day_count)){
                    data += "<div class='calendar_dia selected' onclick='seleccionar_fecha("+now.getFullYear()+","+now.getMonth()+","+day_count+")'><div class='dia_info vhalign'>"+day_count+"</div></div>";
                }else{
                    data += "<div class='calendar_dia'><div class='dia_info vhalign'>"+day_count+"</div></div>";
                }
                total++;
            }
            aux_cont++;
        }
        count++;
        if(count % 7 == 0 && count > 0){
            data += "</div><div class='row_calendar clearfix'>";
            aux_cont = 0;
            aux_blanc = (now.getDate() - day_count <= 7) ? 1 : 0 ;
        }
        var aux_date = new Date(new Date(now.getFullYear(), now.getMonth(), 1).getTime()+1000*60*60*24*day_count);
        aux_month = aux_date.getMonth();
    }
    for(var i=0, ilen=7-aux_cont; i<ilen; i++){
        data += "<div class='calendar_dia'></div>";
    }
    data += "</div></div>";
    
    var p_dia_m2 = (new Date(then.getFullYear(), then.getMonth()).getDay() == 0) ? 6 : new Date(then.getFullYear(), then.getMonth()).getDay() - 1 ;
    var next_month = then.getMonth();
    var count = 0;
    var day_count = 0;
    var aux_aux = 0;

    data += "<div class='table_calendar'><div class='calendar_titulo'>"+mes[then.getMonth()]+" - "+then.getFullYear()+"</div><div class='row_calendar clearfix'>";
    for(var i=0, ilen=semana.length; i<ilen; i++){
        data += "<div class='calendar_semana'><div class='semana_info vhalign'>"+semana[i]+"</div></div>";
    }
    data += "</div><div class='row_calendar clearfix'>";
    while(then.getMonth() == next_month){

        if(p_dia_m2 > count){
            data += "<div class='calendar_dia'></div>";
        }else{
            day_count++;
            if(aux_aux < 2){
                if(total <= config.total_dias){
                    if(horas_disponibles(then.getFullYear(), then.getMonth(), day_count)){
                        data += "<div class='calendar_dia selected' onclick='seleccionar_fecha("+then.getFullYear()+","+then.getMonth()+","+day_count+")'><div class='dia_info vhalign'>"+day_count+"</div></div>";
                    }else{
                        data += "<div class='calendar_dia'><div class='dia_info vhalign'>"+day_count+"</div></div>";
                    }
                }else{
                    data += "<div class='calendar_dia'><div class='dia_info vhalign'>"+day_count+"</div></div>";
                }
                total++;
            }else{
                data += "<div class='calendar_dia2'></div>";
            }
        }
        count++;
        if(count % 7 == 0 && count > 0){
            data += "</div><div class='row_calendar clearfix'>";
            if(total >= config.total_dias){
                aux_aux++;
            }
        }
        var next_date = new Date(new Date(then.getFullYear(), then.getMonth(), 1).getTime()+1000*60*60*24*day_count);
        next_month = next_date.getMonth();

    }
    data += "</div></div>";
    return data;
    

}
function calendario_completo(now){
    
    var year = now.getFullYear();
    var month = now.getMonth();
    var day = now.getDate();

    var ft = new Date(year, month, 1);
    var p_dia_m1 = (new Date(year, month).getDay() == 0) ? 6 : new Date(year, month).getDay() - 1 ;
    var aux_month = month;
    var count = 0;
    var day_count = 0;
    var aux_cont = 0;
    var total = 0;
    var data = "<div class='table_calendar'><div class='calendar_titulo'>"+mes[month]+" - "+year+"</div><div class='row_calendar clearfix'>";
    for(var i=0, ilen=semana.length; i<ilen; i++){
        data += "<div class='calendar_semana'><div class='semana_info vhalign'>"+semana[i]+"</div></div>";
    }
    data += "</div><div class='row_calendar clearfix'>";
    while(month == aux_month){
        if(p_dia_m1 > count){
            data += "<div class='calendar_dia'></div>";
        }else{
            if(day > day_count + 1){
                data += "<div class='calendar_dia'><div class='dia_info vhalign'>"+(day_count + 1)+"</div></div>";
            }else{
                if(total < config.total_dias){
                    if(horas_disponibles(year, month, day_count + 1)){
                        data += "<div class='calendar_dia selected' onclick='seleccionar_fecha("+year+","+month+","+(day_count + 1)+")'><div class='dia_info vhalign'>"+(day_count + 1)+"</div></div>";
                    }else{
                        data += "<div class='calendar_dia'><div class='dia_info vhalign'>-"+(day_count + 1)+"</div></div>";
                    }
                }else{
                    data += "<div class='calendar_dia'><div class='dia_info vhalign'>*"+(day_count + 1)+"</div></div>";
                }
                total++;
            }
            day_count++;
            aux_cont++;
        }
        count++;
        if(count % 7 == 0 && count > 0){
            data += "</div><div class='row_calendar clearfix'>";
            aux_cont = 0;
        }
        aux_month = new Date(ft.getTime()+1000*60*60*24*day_count).getMonth();
    }
    for(var i=0, ilen=7-aux_cont; i<ilen; i++){
        data += "<div class='calendar_dia'></div>";
    }
    data += "</div></div>";
    return data;

}
function ver_servicio_pop(that){

    document.getElementById('pop_up2').style.display = 'block';
    var ver = that.getAttribute('id');
    var valores = ver.split(" ");

    for(var i=0, ilen=data.doctores.length; i<ilen; i++){
        if(data.doctores[i].id == valores[0]){
            for(var j=0, jlen=data.doctores[i].lista_servicios.length; j<jlen; j++){
                if(data.doctores[i].lista_servicios[j].id == valores[1]){
                    document.getElementById('p2_html').innerHTML = data.doctores[i].lista_servicios[j].html_2;
                }
            }
        }
    }

}
function create_servicios_li(doctor){

    var li = document.createElement("LI");
    li.className = "clearfix";
    
    var img = create_element_class("ser_img");
    var cont_img = create_element_class("cont_ser_img vhalign");
    var foto = create_element_class_inner("ser_foto", "<img src='/images/"+doctor.imagen+"' alt='' />");
    var nombre = create_element_class_inner("ser_nombre", doctor.nombre);
    cont_img.appendChild(foto);
    cont_img.appendChild(nombre);
    img.appendChild(cont_img);
    li.appendChild(img);

    var data = create_element_class("ser_data");
    var cont_data = create_element_class("cont_ser_data valign");
    var titulo = create_element_class_inner("ser_titulo", doctor.titulo);
    var cont_ser_doc = create_element_class("cont_ser_doc clearfix");
    for(var i=0, ilen=doctor.lista_servicios.length; i<ilen; i++){
        var aux = create_element_class("ser_doc_titulo w"+doctor.lista_servicios.length);
        aux.setAttribute("id", doctor.id+" "+doctor.lista_servicios[i].id);
        aux.onclick = function(){ ver_servicio_pop(this); };
        var aux1 = create_element_class_inner("ser_doc_img", "<img src='/images/"+doctor.lista_servicios[i].imagen+"' alt='' />");
        var aux2 = create_element_class_inner("ser_doc_nm", doctor.lista_servicios[i].nombre);
        aux.appendChild(aux1);
        aux.appendChild(aux2);
        cont_ser_doc.appendChild(aux);
    }

    cont_data.appendChild(titulo);
    cont_data.appendChild(cont_ser_doc);
    data.appendChild(cont_data);
    li.appendChild(data);
    
    return li;

}
function create_nosotros_li(doctor){

    var li = document.createElement("LI");
    var ndata = create_element_class("ndata vhalign");
    var ndatafoto = create_element_class_inner("ndatafoto", "<img src='/images/"+doctor.imagen+"' alt='' />");
    var ndatainfo = create_element_class_inner("ndatainfo", doctor.nombre);
    ndata.appendChild(ndatafoto);
    ndata.appendChild(ndatainfo);
    li.appendChild(ndata);
    return li;

}
function create_nosotros2_li(doctor){

    var li = document.createElement("LI");
    var ndata = create_element_class("nos clearfix");
    var ndatafoto = create_element_class_inner("nos_foto", "<img src='/images/"+doctor.imagen+"' alt='' />");
    var ndatainfo = create_element_class("nos_desc");

    var ndatadocn = create_element_class_inner("nos_tit", doctor.nombre);
    var ndatadocd = create_element_class_inner("nos_info", doctor.html_descripcion);

    ndatainfo.appendChild(ndatadocn);
    ndatainfo.appendChild(ndatadocd);

    ndata.appendChild(ndatafoto);
    ndata.appendChild(ndatainfo);
    li.appendChild(ndata);
    return li;

}
function create_element_class(clase){
    var Div = document.createElement('div');
    Div.className = clase;
    return Div;
}
function create_element_class_inner(clase, value){
    var Div = document.createElement('div');
    Div.className = clase;
    Div.innerHTML = value;
    return Div;
}
function send(){
    return true;
}
function send2(){
    return true;
}
function get_reserva(){
    return JSON.parse(localStorage.getItem("reserva")) || reserva_blank();
}
function reserva_blank(){
    return { servicio: 0, doctor: 0, sucursal: 0, fecha: 0, hora: 0 }
}
function set_reserva(reserva){
    localStorage.setItem("reserva", JSON.stringify(reserva));
}