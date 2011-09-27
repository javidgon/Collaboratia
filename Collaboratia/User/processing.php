<?php
session_start();
@include("../Functions/db/project_db.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
            @import "../CSS/index.css";
            @import "../CSS/buttons.css";
            @import "../CSS/texts.css";
            @import "../CSS/menus.css";
            @import "../CSS/alerts.css";
        </style>
                    <title>Processing</title>
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
        if (isset($_POST['Send'])) {


            if ($_POST['name'] == "") {

                $errores[] = 'You didn\'t enter "name" value properly.';
            }
            if (strlen($_POST['name']) > 80) {
                $errores[] = 'Your project\'s name cannot have more than 80 characters.';
            }
            if ($_POST['participants'] == "") {

                $errores[] = 'You didn\'t enter "participants" value properly.';
            }
            if ($_POST['type'] == "") {

                $errores[] = 'You didn\'t enter "type" value properly.';
            }
            if ($_POST['category'] == "") {

                $errores[] = 'You didn\'t enter "category" value properly.';
            }
            if ($_POST['description'] == "") {

                $errores[] = 'You didn\'t enter "description" value properly.';
            }
            if (strlen($_POST['description'])>300) {

                $errores[] = 'Your project\'s description cannot have more than 300 characters.';
            }

            $args = array(
                'name' => FILTER_SANITIZE_MAGIC_QUOTES,
                'participants' => FILTER_SANITIZE_MAGIC_QUOTES,
                'type' => FILTER_SANITIZE_MAGIC_QUOTES,
                'category' => FILTER_SANITIZE_MAGIC_QUOTES,
                'description' => FILTER_SANITIZE_MAGIC_QUOTES
            );

            $myinputs = filter_input_array(INPUT_POST, $args);

            if (array_search(false, $myinputs, true) || array_search(NULL, $myinputs, true)) {
                $errores[] = 'There\'re some errors on the form.';
            }

            if (!isset($errores)) { // Si no hay errores.
                $name = filter_var($myinputs['name'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                $n_people = filter_var($myinputs['participants'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                $type = filter_var($myinputs['type'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                $category = filter_var($myinputs['category'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                $description = filter_var($myinputs['description'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                // Creamos el proyecto.
                $res = createProject('localhost', $name, $n_people, $type, $category, $description, $_SESSION['email']);    // Creamos el proyecto.
                if ($res != true) {
                    echo '<div class="infoNegativeError">Error a la hora de crear el proyecto.</div>';
                } else {
                    createPreferences('localhost', $res);   // Creamos las preferencias del proyecto.
                    if (strcmp($type, "private") == 0) {    // Si es un proyecto privado.

                       $code = retrieveCode('localhost', $name, $n_people, $category, $description);    // Obtenemos el código privado.

                        echo '<div class="infoPositive"> <img width ="100 "src="../Img/icons/Package-Accept-icon.png" alt="imagen" /> Congratulations! Your project has been created and your code is ' . $code . ' </div>';
                    } else {
                        echo '<div class="infoPositive"> <img width ="100 "src="../Img/icons/Package-Accept-icon.png" alt="imagen" /> Congratulations! Your project has been created.</div>';
                    }
                }
            } else {  // Si hay errores.
                foreach ($errores as $indice => $error) {

                    echo "<p class=\"infoNegativeError\">$error</p>";   // Vamos mostrando los errores.
                }
            }
        }
        ?>


    </body>
</html>