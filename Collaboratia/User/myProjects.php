<?php
session_start();
@include("../Functions/db/project_db.php");
if (isset($_SESSION['email'])) {    // Si la sesión está activa...
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
                        <title>My Projects</title>
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

        <? if (isset($_POST['delete'])) {   // Si hemos pulsado algún botón delete.
        ?>
            <div class="title">
                <p>Are you sure you want to delete this project?</p>

                <table>
                    <tr>
                    <form action="myProjects.php" method="post">
                        <input type="hidden" name="id" value="<? echo $_POST['id']; ?>"/>
                        <td class="buttonsLeft"><button type="submit" name="redelete" class="positive">
                                <img src="../Img/icons/accept.png" alt=""/>
                                Yes
                            </button></td>
                    </form>
                    <form action="myProjects.php" method="post">
                        <td class="buttonsLeft"><button type="submit" name="no" class="negative">
                                <img src="../Img/icons/book_delete.png" alt=""/>
                                No
                            </button></td>
                    </form>
                    </tr>
                </table>
            </div>
        <?php
        } else if (isset($_POST['redelete'])) { // Si hemos pulsado el botón de seguridad.

            $res = deleteProject('localhost', $_POST['id']);    // Borramos el proyecto y todas sus referencias.

            if ($res == false) {    // Si se ha producido algún error.

                echo '<div class="infoNegative">A error occurred during the process.</div>';

            } else {
        ?>
                <div class="infoPositive"><img width ="100" src="../Img/icons/trash.png" alt="foto"/>You remove it successfully.</div>

<?
            }
        } else {    // Si no hemos pulsado ningún botón.

            $array = searchProjects('localhost', $_SESSION['email']);   // Buscamos los proyectos del usuario.
            $i = 0;
            while ($projects = mysql_fetch_array($array)) { // Los contamos.

                $i++;
            }

            if ($i > 0) {   // Si hay más de uno...
?>

                <div class="title">
                    <h1>My Projects</h1>
                    <table>

<?php
                $array = searchProjects('localhost', $_SESSION['email']);   // Buscamos los proyectos en los que participa el usuario.
                while ($projects = mysql_fetch_array($array)) {

                    echo '<form action="../Project/index.php" method="post">';
                    echo '<input type="hidden" name="id" value="' . $projects[1] . '"/>';
                    echo '<tr><th>Name: </th><td>' . $projects[0] . '</td><td class="buttonsLeft"><button type="submit" name="Auth" class="positive">
                                <img src="../Img/icons/accept.png" alt=""/>
                                Access
                            </button></td></form>';
                    echo '<form action="myProjects.php" method="post">';
                    echo '<input type="hidden" name="id" value="' . $projects[1] . '"/>';
                    echo '<td class="buttonsLeft"><button type="submit" name="delete" class="negative">
                                <img src="../Img/icons/book_delete.png" alt=""/>
                                Delete
                            </button></td></tr></form>';
                }

            } else {    // Si no hay ningún proyecto...
?>

                <div class="infoNegative"><img width="100"src="../Img/icons/Folder-Delete.png" alt="imagen" />I'm sorry, but you don't have any own project yet.</div>

<? } ?>
            </table>
        </div>

<?
        }
    } else {

                echo 'You are not authorized to access this page';
    }
?>


    </body>
</html>
