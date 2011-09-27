<?php

// Funciones de manipulación y obtención de proyectos.

function createUser($address, $email, $pass, $name, $surname, $dateOfBirth_Day, $dateOfBirth_Month, $dateOfBirth_Year, $field, $university, $country, $picture) { // Creamos un nuevo proyecto.
    $res = false;   // Función que crea un usuario a partir de los datos pasados por parámetros.
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
            $pass = sha1($pass);
            $result = mysql_query("INSERT INTO User(email,password,name,surname,dateOfBirth_Day,dateOfBirth_Month,dateOfBirth_Year,
                    field,university,country,picture)
                    values('$email','$pass','$name','$surname','$dateOfBirth_Day','$dateOfBirth_Month','$dateOfBirth_Year','$field',
                    '$university','$country','$picture')", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }
    return $res;
}

function updateUser($address, $email, $pass, $name, $surname, $dateOfBirth_Day, $dateOfBirth_Month, $dateOfBirth_Year, $field, $university, $country, $picture) { // Creamos un nuevo proyecto.
    $res = false;   // Función que actualiza un usuario a partir de los datos pasados por parámetros.
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
            $pass = sha1($pass);
            $result = mysql_query("UPDATE user SET name='$name',surname='$surname',dateOfBirth_Day='$dateOfBirth_Day',
                    dateOfBirth_Month='$dateOfBirth_Month', dateOfBirth_Year='$dateOfBirth_Year', field='$field',
                    university='$university',country='$country',password='$pass',picture='$picture'
                WHERE email = '$email'", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    return $res;
}

function retrieveUser($address, $mail) {    // Función que obtiene los datos de un usuario a partir de su email.
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
            $result = mysql_query("SELECT * FROM user WHERE email ='$mail'", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    if ($res == FALSE) {
        return $res;
    } else {
        return $result;
    }
}
function retrievePicture($address, $mail) { // Función que recupera la fotografía un usuario a partir de su email.
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
            $result = mysql_query("SELECT picture FROM user WHERE email ='$mail'", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    if ($res == FALSE) {
        return $res;
    } else {
        return $result;
    }
}


function deleteUser($address, $mail) {  // Función que borra un usuario.
    echo 'principio';
    $res = false;
    $con = mysql_connect($address, "root", "");

    if ($con) {
        $res = true;
    }
    if ($res != false) {
        echo 'Entrar bd';
        $db_selected = mysql_select_db("Collaboratia", $con);

        if (!$db_selected) {
            mysql_close($con);
            $res = false;
        }
        if ($res != false) {
            echo 'Borra user';
            $result = mysql_query("DELETE FROM user WHERE email ='$mail'", $con);
            if (!$result) {
                $res = false;
            } else {
                echo 'Borra project_user';
                $result = mysql_query("DELETE FROM project_user WHERE email ='$mail'", $con);
                if (!$result) {
                    $res = false;
                }
            }
        }
        mysql_close($con);
    }
    if ($res == FALSE) {
        return $res;
    } else {
        return $result;
    }
}


function getTweetCount($address, $mail) {
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
            $result = mysql_query("SELECT count(sender) FROM tweet WHERE sender ='$mail'", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    if ($res == FALSE) {
        return $res;
    } else {
        return $result;
    }
}

function getTaskCount($address, $mail) {
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
            $result = mysql_query("SELECT count(responsible) FROM task WHERE responsible ='$mail'", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    if ($res == FALSE) {
        return $res;
    } else {
        return $result;
    }
}

function getProjectOwnerCount($address, $mail) {
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
            $result = mysql_query("SELECT count(email) FROM project_user WHERE email ='$mail' and user_type = '1'", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    if ($res == FALSE) {
        return $res;
    } else {
        return $result;
    }
}

function getProjectColaborateCount($address, $mail) {
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
            $result = mysql_query("SELECT count(email) FROM project_user WHERE email ='$mail' and user_type = '2'", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    if ($res == FALSE) {
        return $res;
    } else {
        return $result;
    }
}

function hi(){
    $words = array('Hello','γειά σου','hei','merhaba','здраво','Hola','привет','Olá','こんにちは','Ciao','Bonjour','你好','cześć','Hallo');
    srand((double) microtime() * 1000000);
    $num = rand() % 14;
    return $words[$num];
}


//Funciones de apoyo en registro de usuarios.

function onlyImages($file) {    // Función que especifica los tipos de foto aceptados.
    $acceptedTypes = Array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
    if (array_search($file['type'], $acceptedTypes) == false)
        return false;
    else
        return true;
}

function sizeLimit($file, $limit) { // Función que especifica el tamaño máximo que puede tener una foto subida por el usuario.
    return $file['size'] <= $limit;
}
?>