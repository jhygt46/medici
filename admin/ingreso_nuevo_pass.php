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
        <script type="text/javascript" src="js/ingreso.js"></script>
        <link rel="stylesheet" href="css/login.css" type="text/css" media="all">
        <script>
            $(document).on('keypress',function(e){
                if(e.which == 13){
                    btn_nueva();
                }
            });
            $(document).ready(function(){
                document.getElementById('nueva').addEventListener('click', btn_nueva);
            });
        </script>
    </head>
    <body>
        <div class="cont_login">
            <div class='login vhalign'>
                <div class='titulo'>NUEVA CONTRASEÑA</div>
                <div class='contlogin'>
                    <input type='hidden' id='id_usr' value='<?php echo $_GET['id_usr']; ?>'>
                    <input type='hidden' id='code' value='<?php echo $_GET['code']; ?>'>
                    <div class='us'>
                        <div class='txt'>Password</div>
                        <div class='input'><input type='password' name="pass_01" id='pass_01' value=''></div>
                    </div>
                    <div class='us'>
                        <div class='txt'>Repetir Password</div>
                        <div class='input'><input type='password' name="pass_02" id='pass_02' value=''></div>
                    </div>
                    <div class='button clearfix'>
                        <div class='msg'></div>
                        <div class='btn'><input type='button' id='nueva' value='Entrar'></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>