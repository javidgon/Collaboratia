<?php
session_start();
@include("../Functions/db/tweet_db.php");
@include("../Functions/db/user_db.php");
@include("../Functions/db/project_db.php");
@include("../Functions/authentification/user_auth.php");
@include("../Functions/db/session_db.php");
if (isset($_SESSION['email'])) {    // Si la sesión está activa.

    if (isset($_POST['id'])) {
        $_SESSION['project'] = $_POST['id'];    // Guardamos en la sesión el id del proyecto en el que nos encontramos.
    }
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
                @import "../CSS/tweet.css";
                @import "../CSS/alerts.css";
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
            <li><a href="index.php" title="Texto">Home</a></li>
            <li><a href="#" title="Texto">Tools</a>
                <ul>
                    <li><a href="board.php" title="Texto">Board</a></li>
                    <li><a href="Milestone/index.php" title="Texto">Milestones</a></li>
                    <li><a href="to-do/index.php" title="Texto">TO-DOs</a></li>
                </ul></li>
            <? if (checkAdmin('localhost', $_SESSION['email'], $_SESSION['project']) == 1) {
            ?>
                <li><a href="options.php" title="Texto">Options</a></li>
            <? } ?>
        </ul>

        <ul id="menu-horizontal2">
            <li><a href="../User/index.php" title="Texto">Back to My Page</a></li>
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
        <?php
            if (isset($_POST['delete'])) {  // Si pulsamos el botón de borrado.
                deleteUser('localhost', $_POST['user']);    // Borramos el usuario.
            }
        ?>
            <div class="userBoard">
            <?php
            $result = retrieveProject("localhost", $_SESSION['project']);   // Obtenemos los datos de usuario.
            $row = mysql_fetch_array($result);
            echo '<span class="projectGreeting">Welcome to the main page of <b>'.$row[0]. '</b><img src="../Img/icons/box.png" width="50" alt="Imagen" /></span>';
            ?>
            <h3>Statistics</h3>
            <table>
                <tr><th>Description:</th><th><?php echo $row[2] ?></th></tr>
                <tr><th>Category:</th><th><?php echo $row[3] ?></th></tr>
                <tr><th>Type:</th><th><?php echo $row[4] ?></th></tr>
                <tr><th>Number of free seats:</th><td><?php echo $row[1] ?></td></tr>
                <?php
                $result = getProjectTweetCount("localhost", $_SESSION['project']);  // Obtenemos el número de tweet.
                $row = mysql_fetch_array($result);
                ?>
                <tr><th>Number of tweets:</th><td><?php echo $row[0] ?></td></tr>
                <?php
                $result = getMilestonesCount("localhost", $_SESSION['project']);    // Obtenemos el número de milestones.
                $row = mysql_fetch_array($result);
                ?>
                <tr><th>Number of milestones:</th><td><?php echo $row[0] ?></td></tr>
                <?php
                $result = getToDoCount("localhost", $_SESSION['project']);  // Obtenemos el número de To-Dos.
                $row = mysql_fetch_array($result);
                ?>
                <tr><th>Number of to-do:</th><td><?php echo $row[0] ?></td></tr>
                <?php
                $result = getProjectTaskCount("localhost", $_SESSION['project']);   // Obtenemos el número de tasks.
                $row = mysql_fetch_array($result);
                ?>
                <tr><th>Number of tasks:</th><td><?php echo $row[0] ?></td></tr>
            </table>
                        <span class="fechaUser"> Today is <? echo date('D, d M Y H:i:s');?></span>  <!-- Mostrar la fecha -->
        </div>


        <div class="connection">
            <?php
                // Usuarios online.
                $result = searchUsers("localhost", $_SESSION['project']);   // Buscamos los usuarios de este proyecto.
                $i = 0;
                while ($row = mysql_fetch_array($result)) { // Los mostramos uno por uno.
                    $i++;
                    $emails[] = $row[0];
                }

                if ($result != false) {
                    echo '<table>';
                    echo '<tr><th colspan="2">List of users (' . "$i" . ')</th></tr>';

                    foreach ($emails as $indice => $m) {

                        $picture = retrievePicture('localhost', $m);  // Buscamos su fotografía.

                        while ($row2 = mysql_fetch_array($picture)) {

                            echo "<tr><td><img width=\"50\" src=\"../Img/users/" . $row2[0] . "\" alt=\"Imagen\"/></td><td>$m</td><td>";
                            if (checkAdmin('localhost', $_SESSION['email'], $_SESSION['project']) == 1 && $m != $_SESSION['email']) {   // Si somos un administrador nos permitiría borrarlos.
            ?>
                                <form action="index.php" method="post">
                <?php echo'<input type="hidden" name="user" value="' . $m . '" />'; ?>
                                <input type="submit" name="delete" value="Delete" />
                            </form>
            <?php
                                echo '</td></tr>';
                            }
                        }
                    }

                    echo '</table>';
                } ?></div><?
            } else {

                echo 'You are not authorized to access this page';
            }
            ?>


    </body>
</html>
