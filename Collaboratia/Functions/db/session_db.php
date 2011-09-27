<?php

// Funciones de creación, finalización y búsqueda de sesiones.

function createSession($address, $user) { // Creamos una nueva sesión.
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
            $result = mysql_query("INSERT INTO Session(email)
                values('$user')", $con);
            if (!$result) {
                $res = false;
                echo 'Error!!';
            }
        }
        mysql_close($con);
    }

    return $res;
}

function finalizeSession($address, $email) { // Finalizamos una sesión
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
            $valor = date('Y-m-d H:i:s', time());
            $result = mysql_query("UPDATE Session SET  finalization_date =  now() WHERE email ='$email' and finalization_date = '0000-00-00 00:00:00'", $con);
            if (!$result) {

                $res = false;
            }
        }
        mysql_close($con);
    }

    return $res;
}


function SessionUser($address, $id_project) {   // Buscamos a los usuarios que estén ONLINE, es decir, que no han terminado su sesión.
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
            $result = mysql_query("SELECT distinct session.email from session, project_user where
                    '$id_project'=project_user.id_project and
                    project_user.email=session.email and
                    session.finalization_date='0000-00-00 00:00:00'", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }


    if ($res == false) {
        return $res;
    } else {
        return $result;
    }
}




?>