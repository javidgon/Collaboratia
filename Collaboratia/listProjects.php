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
                    <title>Projects list</title>
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

<? } ?>

<?
            if (isset($_POST['send'])) {

                $array = searchProjectsType('localhost', $_POST['category']);   // Buscamos todos los proyectos por categoria.

                $i = 0;
                while ($projects = mysql_fetch_array($array)) {

                    showProject($projects); // Mostramos los proyectos.
                    $i++;
                }

                if ($i == 0) {
?>
                    <div class="infoNegative"><img width="100"src="Img/icons/Folder-Delete.png" alt="imagen" />I'm sorry, but there aren't any project yet.</div>

        <?
                }
            } else {
        ?>

                <div class="selection">Select the type of project:
                    <ul>
                        <li><span class="buttonsLeft"><form action="listProjects.php" method="post">
                                    <input type="hidden" name="category" value="computerScience" />
                                    <button type="submit" name="send" class="positive">
                                        <img src="" alt=""/>
                                        Computer Science
                                    </button></form></span></li><br />
                        <li><span class="buttonsLeft"><form action="listProjects.php" method="post">
                                    <input type="hidden" name="category" value="ai" />
                                    <button type="submit" name="send" class="positive">
                                        <img src="" alt=""/>
                                        A.I
                                    </button></form></span></li><br />
                        <li><span class="buttonsLeft"><form action="listProjects.php" method="post">
                                    <input type="hidden" name="category" value="biology" />
                                    <button type="submit" name="send" class="positive">
                                        <img src="" alt=""/>
                                        Biology
                                    </button></form></span></li><br />
                        <li><span class="buttonsLeft"><form action="listProjects.php" method="post">
                                    <input type="hidden" name="category" value="bio-technology" />
                                    <button type="submit" name="send" class="positive">
                                        <img src="" alt=""/>
                                        Bio-Technology
                                    </button></form></span></li><br />
                        <li><span class="buttonsLeft"><form action="listProjects.php" method="post">
                                    <input type="hidden" name="category" value="networks" />
                                    <button type="submit" name="send" class="positive">
                                        <img src="" alt=""/>
                                        Networks
                                    </button></form></span></li><br />
                        <li><span class="buttonsLeft"><form action="listProjects.php" method="post">
                                    <input type="hidden" name="category" value="communications" />
                                    <button type="submit" name="send" class="positive">
                                        <img src="" alt=""/>
                                        Communications
                                    </button></form></span></li><br />
                        <li><span class="buttonsLeft"><form action="listProjects.php" method="post">
                                    <input type="hidden" name="category" value="industrial" />
                                    <button type="submit" name="send" class="positive">
                                        <img src="" alt=""/>
                                        Industrial
                                    </button></form></span></li><br/>

                    </ul>


                </div>


<? } ?>
    </body>
</html>
