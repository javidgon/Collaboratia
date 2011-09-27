<?php

// Funciones de chequeo de autenticacion del usuario.


function chequearAutenticacion($email, $password) { // Comprobamos que el usuario tiene una cuenta válida en el sistema.

    $res = false;

    $con = mysql_connect("localhost", "root", "");

    if (!$con) {
        echo('No se ha podido conectar: ' . mysql_error());
    } else {
        $res = true;
    }

    if ($res != false) {

        $db_selected = mysql_select_db("Collaboratia", $con);

        if (!$db_selected) {
            mysql_close($con);
            echo('No puedo usar la base de datos: ' . mysql_error());
            $res = false;
        } else {
            $password = sha1($password);
            $result = mysql_query("SELECT email,password FROM user WHERE email = '$email' and password = '$password'", $con);
            if (!$result) {
                echo('Error al ejecutar la consulta: ' . mysql_error());
                $res = false;
            } else {

                while ($array = mysql_fetch_array($result)) {

                    $i++;
                }
            }
        }
        mysql_close($con);
    }

    if ($i == 1) {
        return true;
    } else {
        return false;
    }
}




function checkAdmin($address,$email,$id_project){   // Comprobamos si el usuario es administrador o colaborador en un proyecto determinado.
     $res = false;
    $con = mysql_connect($address, "root", "");

    if ($con) {
        $res = true;
    }

    if ($res != false) {

        $db_selected = mysql_select_db("Collaboratia", $con);

        if (!$db_selected) {
            mysql_close($con);
            $res = false;
        }
        if ($res != false) {
            $result = mysql_query("SELECT user_type from Project_user WHERE email ='$email' and id_project = '$id_project' ", $con);
            if (!$result) {

                $res = false;
            }else{

                while($row = mysql_fetch_array($result)){
                    $user_type = $row[0];
                }

            }
        }
        mysql_close($con);
    }

    return $user_type;

}
?>