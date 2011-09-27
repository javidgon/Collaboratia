<?php
session_start();
@include("../Functions/db/project_db.php");
if (isset($_SESSION['email'])) {    // Si existe la sesión...
?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <style type="text/css">
                @import "../CSS/index.css";
                @import "../CSS/buttons.css";
                @import "../CSS/texts.css";
                @import "../CSS/menus.css";
                @import "../CSS/alerts.css";
            </style>
                        <title>Collaborative projects</title>
        </head>
        <body>
<div class="header">
    <img style="float:right;margin-right:11em; "src="../Img/logo.png" width="150" alt="Imagen" />
    <? if (isset($_SESSION['email'])) {
    ?><p>Welcome

        <?
        echo '<b>' . $_SESSION['email'] . '</b> </p>';


    }
        ?>

</div>

        <ul id="menu-horizontal">
            <li><a href="index.php" title="Texto">My Page</a>
                <ul>
                    <li><a href="Profile/index.php" title="Texto">My Profile</a></li>
                    <li><a href="myProjects.php" title="Texto">My Projects</a></li>
                </ul></li>
            <li><a href="collaborative.php" title="Texto">Collaborative Projects</a>

            </li>
            <li><a href="create.php" title="Texto">Create New Project</a></li>

        </ul>
            <ul id="menu-horizontal2">
            <li><a href="../index.php" title="Back">Back to Main Page</a>
        </ul>
        <div class="exit">
            <form action="../index.php" method="post">
                <input type="hidden" name= "end" value="end"/>
                <div class="buttons">
                    <button type="submit" class="positive">
                        <img src="../Img/icons/application_side_contract.png" alt=""/>
                        Exit
                    </button>
                </div>
            </form>

        </div>


        <?
        $array = searchProjects('localhost', $_SESSION['email'], 2);    // Buscamos los proyectos en los que colaboremos.
        $i = 0;
        while ($projects = mysql_fetch_array($array)) { // Miramos si tenemos resultados.

            $i++;
        }

        if ($i > 0) {   // Si colaboramos en alguno...
        ?>



            <div class="title">
                <h1>Collaborative Projects</h1>
                <table>

<?php
            $array = searchProjects('localhost', $_SESSION['email'], 2);    // Buscamos los proyectos.
            $i = 0;
            while ($projects = mysql_fetch_array($array)) {

                echo '<form action="../Project/index.php" method="post">';
                echo '<input type="hidden" name="id" value="' . $projects[1] . '"/>';
                echo '<tr><th>Name: </th><td>' . $projects[0] . '</td><td class="buttonsLeft"><button type="submit" name="Auth" class="positive">
                                <img src="../Img/icons/accept.png" alt=""/>
                                Access
                            </button></td></tr></form>';
            }
        } else {    // Si no colaboramos en ninguno.
 ?>

                <div class="infoNegative"><img width="100"src="../Img/icons/folder-Delete.png" alt="imagen" />I'm sorry, but You don't have any collaborative project yet.</div>

<? }
?>
            </table>
        </div>

<?
    } else {

                echo 'You are not authorized to access this page';
    }
?>


    </body>
</html>
