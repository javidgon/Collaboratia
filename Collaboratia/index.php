<?
session_start();    // Abrimos la sesión.
@include('Functions/authentification/user_auth.php');
@include('Functions/db/session_db.php');
$flag_end = false;  // Bandera de fin de sesión.
$flag_session = false;  // Bandera de sesión.
if ($_SESSION['email']) {

    $flag_session = true;   // La sesión está activa.
}
if ($_POST['end']) {    // La sesión se va a cerrar.
    $flag_session = false;  //Se pone a false ambas banderas.
    $flag_end = true;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
            @import "CSS/index.css";
            @import "CSS/texts.css";
            @import "CSS/menus.css";
            @import "CSS/buttons.css";
            @import "CSS/alerts.css";

        </style>
        <title>Collaboratia</title>
    </head>
    <body>

        <div class="header">
            <img style="float:right;margin-right:11em; "src="Img/logo.png" width="150" alt="Imagen" />
            <? if (isset($_SESSION['email']) && $flag_end != true) {
            ?><p>Welcome

                <?
                echo '<b>' . $_SESSION['email'] . '</b> </p>';
            }
                ?>

        </div><?
                if ($flag_session != true) {
                ?>
                    <ul id="menu-horizontal">
                        <li><a href="index.php" title="Main Page">Enter</a>
                            <ul>
                                <li><a href="Login/index.php" title="Log in">Log in</a></li>
                                <li><a href="Register/index.php" title="Sign in">Sign in</a></li>
                            </ul>
                        </li>
                        <li><a href="listProjects.php" title="Projects">Search Projects</a></li>
                        <li><a href="help.php" title="Help">Help</a></li>
                    </ul>

        <? } else {
        ?>



                    <ul id="menu-horizontal">
                        <li><a href="User/index.php" title="Menu">My Page</a></li>
                        <li><a href="listProjects.php" title="Projects">Search Projects</a></li>
                        <li><a href="help.php" title="Help">Help</a></li>
                    </ul>



                    <div class="exit">
                        <form action="index.php" method="post">
                            <input type="hidden" name= "end" value="end"/>
                            <div class="buttons">
                                <button type="submit" class="positive">
                                    <img src="Img/icons/application_side_contract.png" alt=""/>
                                    Exit
                                </button>
                            </div>
                        </form>

                    </div>


        <?
                }

                if (isset($_POST['end'])) { // Si hemos pulsado el botón de salir.
                    finalizeSession('localhost', $_SESSION['email']);   // Finalizamos la sesión llamando a esta función.
                    session_destroy();  // Destruimos la sesión.
        ?>

                    <div style="position:absolute;top:5em;left:2em;font-size: 1.5em; color:#325A66"><img width="100"src="Img/icons/Register.png" alt="imagen" />You've been logged off.</div>

        <? } else {
        ?>         <table class="welcomePicture"><tr><td>

                                <img src="Img/cartel.png" alt="Imagen"/></td>
                            <td><img src="Img/features.png" alt="Imagen" width="510"/></td></tr></table>
                    <div class="validators"><p>
                            <a href="http://jigsaw.w3.org/css-validator/check/referer">
                                <img style="border:0;width:88px;height:31px"
                                     src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
                                     alt="¡CSS Válido!" />
                            </a>
                        </p>
                    </div>
                    <div class="insertCode">
                    <span class="buttonsLeft"><form action="insertCode.php" method="post">
                            <input type="hidden" name="category" value="industrial" />
                            <button type="submit" name="send" class="positive">
                                <img src="" alt=""/>
                                ¡Insert your private code!
                            </button></form></span></div>

        <? } ?>
    </body>
</html>
