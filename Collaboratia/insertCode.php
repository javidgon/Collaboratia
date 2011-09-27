<?
session_start();
@include('Functions/authentification/user_auth.php');
@include('Functions/db/project_db.php');
$flag_end = false;
if ($_SESSION['email']) {

    $flag_session = true;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
            @import "CSS/index.css";
            @import "CSS/texts.css";
            @import "CSS/buttons.css";
            @import "CSS/alerts.css";
            @import "CSS/menus.css";
        </style>
        <title>Insert Code</title>
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

        </div>
        <?
            if ($flag_session == true and $flag_end == false) {
        ?>
                <ul id="menu-horizontal">
                    <li><a href="User/index.php" title="Menu">My Page</a></li>
                    <li><a href="listProjects.php" title="Projects">Search Projects</a></li>
                    <li><a href="help.php" title="Help">Help</a></li>
                </ul>
                <ul id="menu-horizontal2">
                    <li><a href="index.php" title="Texto">Back</a></li>
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
        <? } else {
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

        <? } ?>
        <?php
            if (isset($_POST['insert'])) {

                if ($flag_session == true) {    // Comprobamos primero que estamos logeados.

                    $id = searchPrivate('localhost', $_POST['code']);   // Buscamos el código del proyecto privado.
                    if ($id == FALSE)
                        echo '<div class=infoNegativeError>You can\'t suscribe to a project with this code.</div>';

                    else {  // Si existe el código.

                        $res = checkUser('localhost', $id, $_SESSION['email']); // Comprobamos que no esté ya en el proyecto.
                        $i = 0;
                        if($res != false){
                            while ($user = mysql_fetch_array($res)) {
                             $i++;
                             }
                        }
                        if ($i != 0) {  // Si está ya en el proyecto...

                            echo '<div class=infoNegativeError>You are already in the project</div>';

                        } else {    // Si no...

                            $result = subscribeProject('localhost', $id, $_SESSION['email']);   //  Nos suscribimos al proyecto si no hay fallos.
                            if ($result == FALSE)
                                echo '<div class=infoNegativeError>There\'s an error while suscribing.</div>';
                            else
                                echo '<div class=infoPositive>You have been subscribed successfully.</div>';
                        }
                    }
                }else {

                    echo '<div class=infoNegativeError>You need to be logged for subscribing.</div>';
                }
            } else {
        ?>
                <div class="title">
                    <h1>Insert your code</h1>
                    <form action="insertCode.php" method="post">
                        <table>
                            <tr>
                                <td>Private code:</td>
                                <td><input type="text" name="code" value="" /><td>
                            </tr>
                            <tr>
                                <td><input type="submit" name="insert" value="Insert" /><td>
                            </tr>
                        </table>
                    </form>
                </div>
        <? } ?>
    </body>
</html>
