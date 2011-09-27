<?
session_start();
@include("../../Functions/db/milestone_db.php");
@include("../../Functions/authentification/user_auth.php");
@include("../../Functions/db/project_db.php");
if (isset($_SESSION['project'])) {
?>

    <!--
    To change this template, choose Tools | Templates
    and open the template in the editor.
    -->
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <style type="text/css">
                @import "../../CSS/index.css";
                @import "../../CSS/buttons.css";
                @import "../../CSS/texts.css";
                @import "../../CSS/menus.css";
                @import "../../CSS/tweets.css";
                @import "../../CSS/alerts.css";
            </style>
                        <title>Processing</title>
        </head>
        <body>
<div class="header">
    <img style="float:right;margin-right:11em; "src="../../Img/logo.png" width="150" alt="Imagen" />
    <? if (isset($_SESSION['email'])) {
    ?><p>Welcome

        <?
        echo '<b>' . $_SESSION['email'] . '</b> </p>';


    }
        ?>

</div>
        <ul id="menu-horizontal">
            <li><a href="../index.php" title="Texto">Home</a></li>
            <li><a href="#" title="Texto">Tools</a>
                <ul>
                    <li><a href="../board.php" title="Texto">Board</a></li>
                    <li><a href="index.php" title="Texto">Milestones</a></li>
                    <li><a href="../to-do/index.php" title="Texto">TO-DOs</a></li>
                </ul></li>
            <? if (checkAdmin('localhost', $_SESSION['email'], $_SESSION['project']) == 1) {    // Si somos administradores nos aparecerá la opción "Opciones".
            ?>
                <li><a href="../options.php" title="Texto">Options</a></li>
            <? } ?>
        </ul>

        <ul id="menu-horizontal2">
            <li><a href="index.php" title="Texto">Back</a></li>
        </ul>

        <div class="exit">
            <form action="../../index.php" method="post">
                <input type="hidden" name= "end" value="end"/>
                <div class="buttons">
                    <button type="submit" class="positive">
                        <img src="../../Img/icons/application_side_contract.png" alt=""/>
                        Exit
                    </button>
                </div>
            </form>

        </div>
        <!-- Formulario básico de creación de Milestones.-->


            <?php
            if (isset($_POST['create'])) {


                if ($_POST['title'] == "") {

                    $errors[] = 'You didn\'t enter a "title" for the milestone.';
                }
                if (strlen($_POST['title'])>100) {

                    $errors[] = 'Your title field has more than 100 characters.';
                }
                if ($_POST['description'] == "") {

                    $errors[] = 'You didn\'t enter a "description" for the milestone.';
                }
                if (strlen($_POST['description'])>300) {

                    $errors[] = 'Your description field has more than 300 characters';
                }

                if ($_POST['finalization_day'] == 0 || $_POST['finalization_year'] == 0 || strcmp($_POST['finalization_month'], "") == 0) {
                    $errors[] = 'You\'ve selected a wrong "date"';
                }

                $args = array(
                    'title' => FILTER_SANITIZE_MAGIC_QUOTES,
                    'description' => FILTER_SANITIZE_MAGIC_QUOTES,
                );

                $myinputs = filter_input_array(INPUT_POST, $args);

                if (array_search(false, $myinputs, true) || array_search(NULL, $myinputs, true)) {
                    $errors[] = 'There\'re some errors on the form.';
                }

                if (!isset($errors)) { // Si no hay errores.
                    $title = filter_var($myinputs['title'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                    $description = filter_var($myinputs['description'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                    $finalization_day = $_POST['finalization_day'];
                    $finalization_month = $_POST['finalization_month'];
                    $finalization_year = $_POST['finalization_year'];
                    $res = createMilestone('localhost', $_SESSION['project'], $title, $description, $finalization_day, $finalization_month, $finalization_year);
                    if ($res != true) {
                        echo '<div class="milestoneNegative">A error occurred during the process</div>';
                    } else {
                        echo '<div class="milestonePositive">Congratulations! Your milestone has been created.</div>';
                    }
                } else {  // Si hay errores.
                    foreach ($errors as $indice => $error)
                        echo "<p class=\"infoNegativeError\">$error</p>";   // Vamos mostrando los errores.



                }
            }
            if (isset($_POST['delete'])) {
                $res = deleteMilestone('localhost', $_SESSION['project'], $_POST['id']);
                if ($res != true) {
                    echo 'A error occurrend during the process.';
                } else {
                    echo '<div class="milestoneNegative">Congratulations! Your milestone has been deleted.</div>';
                }
            }
            if (isset($_POST['edit'])) {
            ?>        <div class="title">
                <h1>Edit Milestone</h1>
                <form action="processing.php" method="post">
<?php echo '<input type="hidden" name="id_milestone" value="' . $_POST['id'] . '" />'; ?>
                <table>
                    <tr>
                        <td>Title:</td>
                        <td><input type="text" name="title" /></td>
                    </tr>
                    <tr>
                        <td>Description:</td>
                        <td><textarea cols="30" rows="5" name="description"></textarea></td>
                    </tr>
                    <tr>
                        <td>Finalization date: </td>
                        <td>
                            <select name="finalization_month">
                                <option value=""> - Month - </option>
                                <option value="January">January</option>
                                <option value="Febuary">Febuary</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>

                            <select name="finalization_day">
                                <option value="0"> - Day - </option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>

                            <select name="finalization_year">
                                <option value="0"> - Year - </option>
                                <option value="2011">2011</option>
                                <option value="2012">2012</option>
                                <option value="2013">2013</option>
                                <option value="2014">2014</option>
                                <option value="2015">2015</option>
                                <option value="2016">2016</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="confirm_edit" value="Edit"/></td><td><input type="reset" name="Reset" value="Reset"/></td>
                    </tr>
                </table>
            </form>
            <?php
            }if (isset($_POST['confirm_edit'])) {

                if ($_POST['title'] == "") {

                    $errors[] = 'You didn\'t enter a "title" for the milestone.';
                }
                if ($_POST['description'] == "") {

                    $errors[] = 'You didn\'t enter a "description" for the milestone.';
                }

                if ($_POST['finalization_day'] == 0 || $_POST['finalization_year'] == 0 || strcmp($_POST['finalization_month'], "") == 0) {
                    $errors[] = 'You\'ve selected a wrong "date"';
                }

                $args = array(
                    'title' => FILTER_SANITIZE_MAGIC_QUOTES,
                    'description' => FILTER_SANITIZE_MAGIC_QUOTES,
                );

                $myinputs = filter_input_array(INPUT_POST, $args);

                if (array_search(false, $myinputs, true) || array_search(NULL, $myinputs, true)) {
                    $errors[] = 'There\'re some errors on the form.';
                }

                if (!isset($errors)) { // Si no hay errores.
                    $title = filter_var($myinputs['title'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                    $description = filter_var($myinputs['description'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                    $finalization_day = $_POST['finalization_day'];
                    $finalization_month = $_POST['finalization_month'];
                    $finalization_year = $_POST['finalization_year'];
                    $res = updateMilestone('localhost', $_SESSION['project'], $_POST['id_milestone'], $title, $description, $finalization_day, $finalization_month, $finalization_year);
                    if ($res != true) {
                        echo '<div class="milestoneNegative">There\'s an error during milestone editing.</div>';
                    } else {
                        echo '<div class="milestonePositive">Congratulations! Your milestone has been edited.</div>';
                    }
                } else {  // Si hay errores.
                    foreach ($errors as $indice => $error)
                       echo "<p class=\"infoNegativeError\">$error</p>";   // Vamos mostrando los errores.

                }
            } ?>
            </div>
        <?
        }
        ?>
    </body>
</html>