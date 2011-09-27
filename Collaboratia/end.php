<?
session_start();
@include("Functions/authentification/user_auth.php");
@include("Functions/db/user_db.php");
if (isset($_SESSION['email'])) {    // Si la sesión está activa.
    $flag_end = true;
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title>Account deleted</title>
            <style type="text/css">
                @import "CSS/index.css";
                @import "CSS/menus.css";
                @import "CSS/texts.css";
                @import "CSS/alerts.css";
            </style>
        </head>
        <body>

            <div class="header">
                <img style="float:right;margin-right:11em; "src="Img/logo.png" width="150" alt="Imagen" />
            <?
            if (isset($_SESSION['email']) && $flag_end != true) {
                echo'<p>Welcome<b>' . $_SESSION['email'] . '</b> </p>';
            }
            ?>

        </div>
        <ul id="menu-horizontal2">
            <li><a href="index.php" title="Back">Back</a></li>
        </ul>

<?
            if ($_POST['delete']) { // Si hemos pulsado anteriormente un formulario de borrado de usuario.
                $res = deleteUser('localhost', $_SESSION['email']); // Borramos el usuario.

                if ($res == false) {    // Si ha habído fallos...
                    echo'<div class="infoNegative"><img width="100"src="Img/icons/Login.png" alt="imagen" /> It happened a problem during deleting.
            </div> ';
                } else {    // Si no...
?>
                    <div class="infoNegative"><img width="100"src="Img/icons/Login.png" alt="imagen" /> Your account has been successfully deleted.
                    </div>
<?
                    finalizeSession('localhost', $_SESSION['email']);   // Finalizamos la sesión.
                    $_SESSION['email'] = "";
                    session_destroy();  // Destruimos la sesión.
                }
            }
        }
?>

    </body>
</html>