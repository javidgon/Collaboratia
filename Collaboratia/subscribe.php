<?
session_start();
@include('Functions/authentification/user_auth.php');
@include('Functions/db/project_db.php');
$flag_end = false;
if ($_SESSION['email']) {   // Si la sesión está activa.

    $flag_session = true;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
            @import "CSS/index.css";
            @import "CSS/menus.css";
            @import "CSS/texts.css";
            @import "CSS/buttons.css";
            @import "CSS/alerts.css";

        </style>
                    <title>Subscription</title>
    </head>
    <body>

        <div class="header">
            <img style="float:right;margin-right:11em; "src="Img/logo.png" width="150" alt="Imagen" />
            <? if (isset($_SESSION['email']) && $flag_end != true) {    // Necesitamos esta condición para que nos cambie el menu
                                                                            // dependiendo de si estamos conectados o no.
            
            ?><p>Welcome

                <?
                echo '<b>' . $_SESSION['email'] . '</b> </p>';
            }
                ?>

        </div> <?
                if ($flag_session == true and $flag_end == false) {
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
<? } else { ?>


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

<? } 


                if ($_SESSION['email']) {   // Si la sesión está activa.

                    if (checkUser('localhost', $_POST['id'], $_SESSION['email'])) { // Vemos si ese usuario ya está en el proyecto.

                        echo '<div class="infoNegative"><img width="100" src="Img/icons/Finder.png" alt="imagen" />Sorry, but you are already in the project.</div>';
                    } else {    // Si no lo está.

                        $res = subscribeProject('localhost', $_POST['id'], $_SESSION['email']); // Lo inscribimos.

                        if ($res == true) {
                            // Si no ha habido problemas mostramos este mensaje.

                            echo '<div class="infoPositive"><img width="100" src="Img/icons/Register.png" alt="imagen" />Yes! You have been subscribe.</div>'; 

     
                        } else {

                            // Si ha ocurrido un problema mostramos este mensaje.

                            echo '<div class="infoNegative"><img width="100"src="Img/icons/run.png" alt="imagen" />Sorry, but it seems there isnt more seats.</div>';
                        }
                    }

                } else {    // Si no hemos iniciado la sesión obtendíamos el siguiente mensaje.


                    echo '<div class="infoNegative"><img width="100"src="Img/icons/Login.png" alt="imagen" />&nbsp;Sorry, but you need to be logged for subscribing.</div>';
                } ?>




    </body>
</html>
