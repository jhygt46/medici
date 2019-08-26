<?php 
    unset($_COOKIE);
    session_destroy();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='shortcut icon' type='image/x-icon' href='/images/favicon/<?php echo $info["favicon"]; ?>' />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script type="text/javascript" src="/salud_mental/admin/js/ingreso.js"></script>
        <link rel="stylesheet" href="/salud_mental/admin/css/login.css" type="text/css" media="all">
        <script>
            $(document).on('keypress',function(e){
                if(e.which == 13){
                    btn_login();
                }
            });
            $(document).ready(function(){
                document.getElementById('login').addEventListener('click', btn_login);
            })
        </script>
    </head>
    <body>
        <div class="cont_login">
            <div class='login vhalign'>
                <div class='titulo'>INGRESO</div>
                <div class='contlogin'>
                    <div class='us'>
                        <div class='txt'>Correo</div>
                        <div class='input'><input type='text' name='login_usuario' id='user' value=''></div>
                    </div>
                    <div class='pa'>
                        <div class='txt'>Contrase&ntilde;a</div>
                        <div class='input'><input type='password' name='login_password' id='pass'></div>
                    </div>
                    <div class='button clearfix'>
                        <div class='msg'></div>
                        <div class='btn'><input type='button' id='login' value='Entrar'></div>
                    </div>
                </div>
                <div class='ltpass'><a href='recuperar'>No tiene contrase&ntilde;a?</a></div>
            </div>
        </div>
    </body>
</html>