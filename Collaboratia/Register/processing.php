<?php
@include("../Functions/db/user_db.php");
?>
<html>
    <head>
        <style type="text/css">
            @import "../CSS/index.css";
            @import "../CSS/menus.css";
            @import "../CSS/texts.css";
            @import "../CSS/buttons.css";
            @import "../CSS/alerts.css";
        </style>
        <title>Processing</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
<div class="header">
    <img style="float:right;margin-right:11em; "src="../Img/logo.png" width="150" alt="Imagen" />
    <? if (isset($_SESSION['email']) && $flag_end != true) {
    ?><p>Welcome

        <?
        echo '<b>' . $_SESSION['email'] . '</b> </p>';


    }
        ?>

</div>
        <ul id="menu-horizontal3">
            <li><a href="../index.php" title="Back">Back</a>
        </ul>
        ?>
        <?php
        if (isset($_POST['Send'])) {
            //Comprobamos que los parámetros se introdujeron de manera correcta.
            if (strcmp($_POST['mail'], "") == 0) {
                $errors[] = 'You haven\'t introduced your "email".';
            }
            if (strlen($_POST['mail'])>50) {
                $errors[] = 'Your email length is more than 50 characters.';
            }
            if (strlen($_POST['pass1'])<8) {
                $errors[] = 'Your password can\'t have less than 8 characters.';
            }
            if (strcmp($_POST['pass1'], "") == 0) {
                $errors[] = 'You haven\'t introduced your "pass".';
            }
            if (strcmp($_POST['pass1'], $_POST['pass2']) != 0) {
                $errors[] = 'Your passwords dont match';
            }
            if (strlen($_POST['name']) > 50 || strlen($_POST['surname'] > 50)) {
                $errors[] = 'Check the length for your "name" and "surname".';
            }
            if ($_POST['dateOfBirth_Day'] == 0 || $_POST['dateOfBirth_Year'] == 0
                    || strcmp($_POST['dateOfBirth_Month'], "") == 0) {
                $errors[] = 'You\'ve selected a wrong "date"';
            }
            if (strcmp($_POST['field'], "0") == 0) {
                $errors[] = 'You haven\'t introduced your "field".';
            }
            if (strcmp($_POST['university'], "0") == 0) {
                $errors[] = 'You haven\'t introduced your "university".';
            }
            if (strlen($_POST['university'])>50) {
                $errors[] = 'Your university length is more than 50 characters.';
            }
            if (strcmp($_POST['country'], "") == 0) {
                $errors[] = 'You haven\'t selected your "country".';
            }


            if ($_FILES['picture']['name'] != "") {
                //Comprobamos errores en la subida de la foto.
                if ($_FILES['picture']['error'] > 0) {
                    $errors[] = 'There\'s an error during picture processing.';
                }
                //Comprobamos que el tipo del fichero sea permitido.
                if (onlyImages($_FILES['picture']) == false) {
                    $errors[] = 'You\'ve introduced an invalid type of file.';
                }
                //Si la foto supera el peso permitido.
                if (sizeLimit($_FILES['picture'], 1024 * 1024) == false) {
                    $errors[] = 'The picture size is greater than the limit.';
                }
            }
            $mail = filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL);

            if ($mail !== null && $mail !== false) {
                //El parámetro ha sido enviado y es un EMAIL.
            } else {
                $errors[] = 'Your email field isn\'t a proper email field.';
            }
            $args = array(
                'mail' => FILTER_SANITIZE_EMAIL,
                'pass1' => FILTER_SANITIZE_MAGIC_QUOTES,
                'name' => FILTER_SANITIZE_MAGIC_QUOTES,
                'surname' => FILTER_SANITIZE_MAGIC_QUOTES,
                'field' => FILTER_SANITIZE_MAGIC_QUOTES,
                'university' => FILTER_SANITIZE_MAGIC_QUOTES,
            );
            $myinputs = filter_input_array(INPUT_POST, $args);
            if (array_search(false, $myinputs, true) || array_search(NULL, $myinputs, true)) {
                $errors[] = 'There\'re some errors on the form.';
            }
            if (!isset($errors)) { // Si no hay errores eliminamos las etiquetas html.
                $mail = filter_var($myinputs['mail'], FILTER_SANITIZE_STRING);
                $pass = filter_var($myinputs['pass1'], FILTER_SANITIZE_STRING);
                $name = filter_var($myinputs['name'], FILTER_SANITIZE_STRING);
                $surname = filter_var($myinputs['surname'], FILTER_SANITIZE_STRING);
                $dateOfBirth_Day = $_POST['dateOfBirth_Day'];
                $dateOfBirth_Month = $_POST['dateOfBirth_Month'];
                $dateOfBirth_Year = $_POST['dateOfBirth_Year'];
                $field = $_POST['field'];
                $university = filter_var($myinputs['university'], FILTER_SANITIZE_STRING);
                $country = $_POST['country'];

                //Capturamos la extensión del archivo.
                $ext = substr($_FILES['picture']['name'], strrpos($_FILES['picture']['name'], '.'));

                $res = createUser('localhost', $mail, $pass, $name, $surname, $dateOfBirth_Day, $dateOfBirth_Month, $dateOfBirth_Year, $field,
                                $university, $country, $mail . $ext);


                if ($res != true) {
                    echo '<div class ="infoNegativeError">There has been an error during user creation.<br/> Probably "Email" already taken.</div>';
                } else {
                    echo '<div class="infoPositive"><img width="100"src="../Img/icons/User.png" alt="imagen" />  Congratulations! Your user has been created.</div>';

                    if ($_FILES['picture']['name'] != "") {
                        //Guardamos la foto en el sistema.
                        $ruta = "../Img/users/" . $mail . $ext;
                        move_uploaded_file($_FILES['picture']['tmp_name'], $ruta);
                    }
                }
            } else {  // Si hay errores.
                foreach ($errors as $index)
                    echo '<p class ="infoNegativeError">' . $index . '</p>';   // Vamos mostrando los errores.






            }
        }
        ?>

    </body>
</html>
