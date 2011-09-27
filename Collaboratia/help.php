<?
session_start();
@include('Functions/authentification/user_auth.php');
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
            @import "CSS/menus.css";
            @import "CSS/buttons.css";
            @import "CSS/alerts.css";
        </style>
                    <title>Contact form</title>
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
        <div class="title">
            <h1>Contact us:</h1>
        <form name="mail_form" action="mailto:collaboratia@hotmail.com" method="POST" enctype="text/plain">
            <table>
                <tr>
                    <td>

                        Name:  </td><td>
                        <input type = "text" name = "Name"></td></tr>
                <tr>
                    <td>

                        Subject:  </td><td>
                        <input type = "text" name = "Subject"></td></tr>

                <tr>
                    <td>

                        Comments:</td><td>
                        <textarea name = "Comments" cols = 50 rows = 6></textarea></td></tr>
                        <tr>
                            <td> <input type="submit" name="Send" value="Send"/></td><td><input type="reset" name="Reset" value="Reset" /></td>
                        </tr>

            </table>
        </form>
        </div>
    </body>
</html>
