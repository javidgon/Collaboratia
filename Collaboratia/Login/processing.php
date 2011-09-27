<?
include '../Functions/authentification/user_auth.php';
include '../Functions/db/session_db.php';

if (isset($_POST['Auth'])) {    // Si queremos entrar en la aplicación.

    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1.- Validacion.
    // Campo nombre.

    if ($email == "") {

        $errores[] = 'You didnt enter anything in the "email" field.';
    }
    if ($password == "") {

        $errores[] = 'You didnt enter anything in the "password" field.';
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if ($email !== null && $email !== false) {
        //El parametro ha sido enviado y es un EMAIL.
    } else {
        $errores[] = 'Your email hasnt a proper format.';
    }

    if (!isset($errores)) { // Si no se produce ningún error.
        $res = chequearAutenticacion($email, $password);    // Miramos si el usuario está en el sistema o no.
        if ($res) {
            session_start();
            createSession('localhost', $email); // Creamos la sesión.
            $_SESSION['email'] = $email;    // Establecemos el email del usuario en la sesión.
            header('Location:../User/index.php');   // Dirigimos al usuario a la página principal.
        }else{

          header('Location:error.php'); // Cuando no encuentra al usuario, lo dirigimos a la siguiente página.

        }
    } else {
?>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
        <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <title>Processing</title>
                <style type="text/css">
                    @import "../CSS/index.css";
                    @import "../CSS/menus.css";
                    @import "../CSS/alerts.css";
                    @import "../CSS/texts.css";
                </style>
            <title>Processing</title>
            </head>
            <body>
<div class="header">
    <img style="float:right;margin-right:11em; "src="../Img/logo.png" width="150" alt="Imagen" />
    <? if (isset($_SESSION['email']) && $flag_end != true) {
    ?><p>Welcome

        <?
        echo '<b>' . $_SESSION['email'] . '</b> </p>';


    }
        ?>

</div>
        <ul id="menu-horizontal3">
            <li><a href="../index.php" title="Back">Back</a>
        </ul>

<?
        foreach ($errores as $key => $value) {
            echo'<p class ="infoNegativeError">' . "$value" . '</p>'; // Mostramos los errores.
        }
    }
}
?>
    </body>
</html>
