<?php
session_start();
@include("../Functions/db/tweet_db.php");
@include("../Functions/db/user_db.php");
@include("../Functions/db/project_db.php");
@include("../Functions/authentification/user_auth.php");
@include("../Functions/db/session_db.php");
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
                @import "../CSS/tweet.css";
                @import "../CSS/alerts.css";
            </style>
            <title>Board</title>
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
            <li><a href="index.php" title="Texto">Back</a></li>
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

        </div><?
            $flag = true;
            if (isset($_POST['delete_tweet'])) {    // Si hemos pulsado el botón de borrado de tweet.

                $res = deleteTweet('localhost', $_POST['id_tweet']);    // Borramos el tweet.

                if ($res == true) {
                    echo '<div class="boardNegative">The tweet has been removed.</div>';
                    $flag = false;
                } else {
                    echo '<div class="boardNegative">It happened a problem during deleting.</div>';
                    $flag = false;
                }
            }
            ?>
            <div class="connection"><?
            // Usuarios online.
            $result = SessionUser("localhost", $_SESSION['project']);   // Buscamos las personas del proyecto que estén online.
            $i = 0;
            while ($row = mysql_fetch_array($result)) { // Los mostramos uno a uno.
                $i++;
                $emails[] = $row[0];
            }

            if ($result != false) {
                echo '<table>';
                echo '<tr><th colspan="2">Online users (' . "$i" . ')</th></tr>';

                foreach ($emails as $indice => $m) {

                    $picture = retrievePicture('localhost', $m);    // Obtenemos sus fotografias.

                    while ($row2 = mysql_fetch_array($picture)) {

                        echo "<tr><td><img width=\"50\" src=\"../Img/users/" . $row2[0] . "\" alt=\"Imagen\"/></td><td>$m</td></tr>";
                    }
                }

                echo '</table>';
            }
            ?>
        </div>
            <?
            if (isset($_POST['send'])) {

                if (strlen($_POST['tweet']) > 140) {

                    echo '<div class="boardNegative">Remember, only 140 characters!</div>';
                    $flag = false;
                } else if ($_POST['tweet'] == "") {

                    echo '<div class="boardNegative">You need to write at least something!</div>';
                    $flag = false;
                } else {

                    //Filtros
                    $filtros = Array(
                        'tweet' => FILTER_SANITIZE_STRING
                    );

                    $entradas = filter_input_array(INPUT_POST, $filtros);
                    $tweet = $entradas['tweet'];
                    writeTweet('localhost', $_SESSION['email'], $tweet, $_SESSION['project']);

                    echo '<div class="boardPositive">Your tweet has been created!</div>';
                    $flag = false;
                }
            }
            ?>

            <table class="formularioTweet">
                <form action="board.php" method="POST">
                    <tr><td>Tweet: </td></tr>
                    <tr><td> <textarea name="tweet" cols="30" rows="3"> </textarea> </td> </tr>
                    <tr> <td class="buttonsLeft"><button type="submit" name="send" class="positive">
                                Publish
                            </button></td></tr>
                </form>
            </table>


<?
            $result = searchTweets('localhost', $_SESSION['project']);  // Buscamos los tweets de ese proyecto.
            $i = 0;
            while ($row = mysql_fetch_array($result)) { // Los recorremos uno a uno.
                $i++;

                $picture = retrievePicture('localhost', $row[0]);   // Obtenemos las fotografías de sus autores.

                while ($row2 = mysql_fetch_array($picture)) {
                    $picture_address = $row2[0];
                }

                    // Miramos si somos administrador o no, ya que necesitamos saber si colocar un botón de borrado de tweet.
                if (checkAdmin('localhost', $_SESSION['email'], $_SESSION['project']) == 1) {
                        echo '<form action="board.php" method="post">';
                        echo '<input type="hidden" name="id_tweet" value="' . $row[3] . '"';
                    if ($i % 2 == 0) {
                        echo '<table class="lista">';
                    } else {
                        echo '<table class="listab">';
                    }
                    echo '<tr>';
                    echo "<td><img width=\"50\" src=\"../Img/users/$picture_address\" border=\"1px\" alt=\"Imagen\"/></td><td class=\"email\">$row[0]</td>";
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td class="description" colspan="2">' . $row[1] . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><input type="submit" name="delete_tweet" value="Delete" /></form></td><td class="date">' . $row[2] . '</td>';
                    echo '</tr>';
                    echo '<table>';
                }

            }

            if ($i == 0) {  // Si no hemos encontrado ningún tweet.

                echo '<p class="boardPositive">Write something and share ideas!</p>';

            } else {    // Si hay tweets.

                if ($flag == true) {    // Si no ha habido errores anteriormente.

                    if ($_SESSION['project'] != "") {   // Si la sesión del proyecto no tiene errores...

                        $array = retrieveProject('localhost', $_SESSION['project']);    // Obtenemos los datos del proyecto.

                        while ($name = mysql_fetch_array($array)) {

                            echo '<span class="titleH1">' . "$name[0]" . '</span>'; // Mostramos el nombre del proyecto en la parte superior.
                        }
                    }
                }
            }
        } else {


                echo 'You are not authorized to access this page';
        }
        ?>


    </body>
</html>
