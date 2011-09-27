<?php
session_start();
@include("../Functions/db/tweet_db.php");
@include("../Functions/db/user_db.php");
@include("../Functions/db/project_db.php");
@include("../Functions/authentification/user_auth.php");
if (checkAdmin('localhost', $_SESSION['email'], $_SESSION['project']) == 1) {   // Mostramos la página sólo si es un administrador.
?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <style type="text/css">
                @import "../CSS/texts.css";
                @import "../CSS/index.css";
                @import "../CSS/buttons.css";
                @import "../CSS/alerts.css";
                @import "../CSS/menus.css";
            </style>
            <title>Project options</title>
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
<? if (checkAdmin('localhost', $_SESSION['email'], $_SESSION['project']) == 1) { ?>
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

<?
            if (isset($_POST['Send'])) {

                if ($_POST['name'] == "") {
                    $errors[] = 'You didn\'t enter "name" value properly.';
                }
                if (strlen($_POST['name']) > 80) {
                    $errors[] = 'Your project\'s name cannot have more than 80 characters.';
                }
                if ($_POST['participants'] == "-") {
                    $errors[] = 'You didn\'t enter "participants" value properly.';
                }
                if ($_POST['category'] == "-") {
                    $errors[] = 'You didn\'t enter "category" value properly.';
                }
                if ($_POST['description'] == "") {

                    $errors[] = 'You didn\'t enter "description" value properly.';
                }
                if (strlen($_POST['description']) > 300) {

                    $errors[] = 'Your description field has more than 300 characters.';
                }
                if ($_POST['n_tweets'] == "-") {

                    $errors[] = 'You didn\'t enter a correct number of tweets.';
                }


                $args = array(
                    'name' => FILTER_SANITIZE_MAGIC_QUOTES,
                    'description' => FILTER_SANITIZE_MAGIC_QUOTES,
                );

                $myinputs = filter_input_array(INPUT_POST, $args);

                if (array_search(false, $myinputs, true) || array_search(NULL, $myinputs, true)) {
                    $errors[] = 'There\'re some errors on the form.';
                }

                if (!isset($errors)) { // Si no hay errores
                    $name = filter_var($myinputs['name'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                    $description = filter_var($myinputs['description'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.

                    $res = updateProject('localhost', $_SESSION['project'], $name, $_POST['participants'], $description, $_POST['category']);   // Actualizamos proyecto.

                    $res2 = updatePreferences('localhost', $_SESSION['project'], $_POST['n_tweets']);   // Actualizamos preferencias.

                    if ($res == false || $res2 == false) {

                        echo '<div class="infoNegative"><img width="100"src="../Img/icons/Login.png" alt="imagen" />&nbsp;Ops, It happened a problem during updating.</div>';
                    } else {
                        echo '<div class="infoPositive"><img width="100" src="../Img/icons/Register.png" alt="imagen" />Your project has been updated successfully.</div>';
                    }
                } else {    // Si hay errores.

                    foreach ($errors as $indice => $error) {
                        echo "<p class=\"infoNegativeError\">$error</p>";   // Vamos mostrando los errores.
                    }
                }
            } else {

                $info = retrieveProject('localhost', $_SESSION['project']); // Obtenemos los datos del proyecto.

                while ($row = mysql_fetch_array($info)) {
?>


                    <div class="title">
                        <h1>Project options</h1>
                        <table>
                            <form method="post" action="options.php">
                                <tr><td>Name:</td><td><input type="text" name="name" value="<? echo $row[0]; ?>"/></td></tr>
                                <tr><td>Number of participants:</td><td>    <select name="participants" >
                                            <option value="-">-</option>
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="50">50</option>

                                        </select></td></tr>

                                <tr><td>Category</td><td>   <select name="category" >
                                            <option value="-">-</option>
                                            <option value="computerScience">Computer Science</option>
                                            <option value="ai">A. I.</option>
                                            <option value="biology">Biology</option>
                                            <option value="bio-technology">Bio-Technology</option>
                                            <option value="networks">Networks</option>
                                            <option value="communications">Communications</option>
                                            <option value="industrial">Industrial</option>

                                        </select></td></tr>
                                <tr>
                                    <td>Description</td><td>
                                        <textarea rows="5" cols="20" name="description"><? echo $row[2]; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td>How many tweets do you want on the board?</td><td>    <select name="n_tweets" >
                                            <option value="-">-</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                            <option value="50">50</option>
                                            <option value="60">60</option>
                                            <option value="70">70</option>
                                            <option value="80">80</option>
                                            <option value="90">90</option>
                                            <option value="100">100</option>
                                            <option value="200">200</option>


                                        </select></td>
                                </tr>
                                <tr><div class="buttonsLeft">
                                    <td class="buttonsLeft"><button type="submit" name="Send" class="positive">
                                            <img src="../Img/icons/accept.png" alt=""/>
                                            Save
                                        </button></td>

                                    <td class="buttonsLeft"><button type="reset">
                                            <img src="../Img/icons/book_delete.png" alt=""/>
                                            Reset
                                        </button></td>

                                </div></tr>
                            </form>

                        </table>


                    </div>



<?
                }
            }
        } else {

            echo 'You are not authorized to access this page';
        }
?>


    </body>
</html>
