<?php
session_start();
@include("../Functions/db/project_db.php");
@include("../Functions/db/user_db.php");
if (isset($_SESSION['email'])) {    // Si la sesión está activa...

    $_SESSION['project'] = "";  // Borramos los residuos de la sesión del proyecto.
?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <style type="text/css">
                @import "../CSS/index.css";
                @import "../CSS/menus.css";
                @import "../CSS/buttons.css";
                @import "../CSS/texts.css";
            </style>
            <title>Main Page</title>
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


        <div class="userBoard">
<?php
            $saludo = hi(); // Generamos un saludo en diferentes idiomas.
            $info = retrieveUser('localhost', $_SESSION['email']);  // Obtenemos la información del usuario.
            $row = mysql_fetch_array($info);    // Obtenemos los valores.

            echo '<span class="userGreeting">' . $saludo . ' ' . $row[1] . ' ' . $row[2] . '!<img src="../Img/icons/user.png" width="40" alt="Imagen" /></span>';
?>
            <h3>Statistics</h3>
            <img class="UImg" src="../Img/icons/Users.png" alt="Imagen"/>
            <table>
<?php
            $result = getTweetCount('localhost', $_SESSION['email']);   // Obtenemos el número de tweets.
            $row = mysql_fetch_array($result);
            echo '<tr><th>Number of tweets: </th><td>' . $row[0] . '</td><tr>';
            $result = getTaskCount('localhost', $_SESSION['email']);    // Obtenemos el número de tasks.
            $row = mysql_fetch_array($result);
            echo '<tr><th>Number of tasks: </th><td>' . $row[0] . '</td><tr>';
            $result = getProjectOwnerCount('localhost', $_SESSION['email']);    // Obtenemos el número de proyectos que hemos creado.
            $row = mysql_fetch_array($result);
            echo '<tr><th>Number of projects I manage: </th><td>' . $row[0] . '</td><tr>';
            $result = getProjectColaborateCount('localhost', $_SESSION['email']);   // Obtenemos el número de proyectos en los que colaboramos.
            $row = mysql_fetch_array($result);
            echo '<tr><th>Number of projects I\'m collaborating with: </th><td>' . $row[0] . '</td><tr>';
?>
            </table>
            <span class="fechaUser"> Today is <? echo date('D, d M Y H:i:s'); ?></span> <!-- Mostramos la fecha. -->
        </div>

<?
            } else {

                echo 'You are not authorized to access this page';
            }
?>


    </body>
</html>
