<?php

// Funciones de creación, manipulación y obtención de proyectos.


function createProject($address, $name, $n_people, $type, $category, $description, $email) { // Creamos un nuevo proyecto.
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
            $result = mysql_query("INSERT INTO Project(name,n_people,type,category,description)
                                        values('$name','$n_people','$type','$category','$description')", $con);

            $id = idProject($name, $n_people, $category, $description);


            $result2 = mysql_query("INSERT INTO Project_user(email,id_project,user_type)values('$email','$id',1)", $con);

             if (strcmp($type, "private") == 0) {
                $code = createPrivateCode();
                echo $code;
                $result3 = mysql_query("INSERT INTO Project_code (id_project,code) values ('$id','$code')", $con);
                if (!$result3) {
                    $res = false;
                }
            }

            if (!$result || !$result2) {
                $res = false;
            }
        }
        mysql_close($con);
    }
    if ($res == false) {
        return $res;
    } else {
        return $id;
    }
}

function idProject($name, $n_people, $category, $description) { // Obtenemos la id generada por Mysql para un proyecto concreto.
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
            $result = mysql_query("SELECT id_project from Project WHERE
                    name = '$name' and n_people = '$n_people' and category = '$category' and description = '$description'", $con);
            if (!$result) {

                $res = false;
            } else {
                while ($array = mysql_fetch_array($result)) {

                    $id = $array[0];
                }
            }
        }
        mysql_close($con);
    }
    if ($res != false) {
        return $id;
    } else {
        return $res;
    }
}

function deleteProject($address, $id) { // Borramos un proyecto, y todas sus referencias.

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
            $result = mysql_query("DELETE from Project WHERE id_project = '$id'", $con);
            $result2 = mysql_query("DELETE from Project_user WHERE id_project = '$id'", $con);
            $result3 = mysql_query("DELETE from Preferences WHERE id_project = '$id'",$con);
            $result4 = mysql_query("DELETE from To_do WHERE id_project = '$id'",$con);
            $result5 = mysql_query("DELETE from Task WHERE id_project = '$id'",$con);
            $result6 = mysql_query("DELETE Tweet FROM Tweet, Board_tweet WHERE Tweet.id_tweet=Board_tweet.id_tweet and Board_tweet.id_project = '$id'",$con);
            $result7 = mysql_query("DELETE from Board_tweet WHERE id_project = '$id'",$con);

            if (!$result || !$result2 || !$result3 || !$result4 || !$result5 || !$result6 || !$result7) {

                $res = false;
            }
        }
        mysql_close($con);
    }

    return $res;
}

function updateProject($address, $id_project, $name, $n_people, $description, $category) { // Actualizamos un determinado proyecto.
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
            $result = mysql_query("UPDATE Project SET
                                        name = '$name', n_people='$n_people',description = '$description',
                                        category='$category'
                                        WHERE id_project = '$id_project'", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    return $res;
}

function retrieveProject($address, $id_project) { // Obtenemos el proyecto para utilizarlo.
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

            $result = mysql_query("SELECT name,n_people,description,category,type FROM Project WHERE id_project ='$id_project'", $con);
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

function searchProjects($address, $email, $user_type=1) {   // Buscamos los proyectos que tiene cada usuario.

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

            $result = mysql_query("SELECT Project.name,Project.id_project FROM Project, Project_user WHERE
                Project_user.email = '$email' and Project_user.user_type = '$user_type' and Project_user.id_project = Project.id_project", $con);
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

function searchProjectsType($address, $category) { // Buscamos los proyectos en base a su categoria.

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
            $result = mysql_query("SELECT Project.name,Project.description,Project.n_people, Project.creation_date, Project.id_project FROM Project WHERE
                Project.category = '$category' and Project.type = 'public'", $con);
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

function subscribeProject($address, $id, $email) { // Nos subscribimos a un determinado proyecto.
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
            $result = mysql_query("SELECT n_people from Project WHERE id_project = '$id'", $con);
            $i = 0;
            $exit = false;
            while ($project = mysql_fetch_array($result)) {
                $i++;
                if ($project[0] > 0) {
                    $people = $project[0] - 1;
                } else {
                    $exit = true;
                }
            }
            if ($i == 1 && $exit == false) {
                $result2 = mysql_query("UPDATE Project SET n_people = '$people' WHERE id_project = '$id'", $con);
                $result3 = mysql_query("INSERT INTO Project_user (id_project,email,user_type) values('$id','$email',2)", $con);
                if (!$result || !$result2) {
                    $res = false;
                }
            }
            mysql_close($con);
        }
    }
    if ($exit == true || $res == false) {
        return false;
    } else {
        return true;
    }
}

function showProject($project) {    // Mostramos tablas con los proyectos a los que nos podemos subscribir.

    echo '<div class="listProjects">';
    echo '<table>';
    echo '<tr>';
    echo '<td rowspan="2"><img src="Img/icons/Box.png" alt="Imagen" width="100"/></td><th>Name:</th><td><p>' . $project[0] . '</p></td><th>Free seats:</th><td>' . $project[2] . '</td><th>Creation Date:</th><td>' . $project[3] . '</td><form action="subscribe.php" method="post"><input type="hidden" name="id" value="' . $project[4] . '"<td class="buttonsLeft"><button type="submit">
                                <img src="Img/icons/connect.png" alt=""/>
                                Subscribe
                            </button></td></form>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Description:</th><td colspan="6">' . $project[1] . '</td>';
    echo '</tr>';
    echo '</table>';
    echo '</div><br />';
}

function checkUser($address, $id, $email) { // Mira si un determinado usuario está ya en ese proyecto o no.
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
            $result = mysql_query("SELECT * from Project_user WHERE id_project = '$id' and email = '$email'", $con);
            $i = 0;
            while ($project = mysql_fetch_array($result)) {
                $i++;
            }

            if (!$result || !$result2) {
                $res = false;
            }
        }
        mysql_close($con);
    }
    if ($i == 0) {
        return false;
    } else {
        return true;
    }
}

function createPreferences($address, $id_project) { // Creamos las preferencias para un determinado proyecto.

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
            $result = mysql_query("INSERT INTO Preferences  (id_project, n_tweets) values ('$id_project',30)", $con);

            if (!$result) {

                $res = false;
            }
        }
        mysql_close($con);
    }

    return $res;
}

function updatePreferences($address, $id_project, $n_tweets) {  // Actualizamos las preferencias.
echo 'error111';
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
            $result = mysql_query("UPDATE Preferences SET n_tweets = '$n_tweets' WHERE id_project = '$id_project'", $con);

            if (!$result) {
                                echo 'ERROR';
                $res = false;
            }
        }
        mysql_close($con);
    }

    return $res;
}

function searchUsers($address, $id_project) {   // Buscamos los usuarios de un proyecto en particular
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

            $result = mysql_query("SELECT email from Project_user WHERE
                     id_project = '$id_project'", $con);
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

/* Parte de la librería de funciones encargada en la generación de códigos para los proyectos privados.*/




function retrieveCode($address, $name, $n_people, $category, $description) {    //Obtenemos el código guardado en la db para un determinado proyecto.
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
            $id = idProject($name, $n_people, $category, $description);

            $result = mysql_query("SELECT code from Project_code WHERE
                     id_project = '$id'", $con);
            if (!$result) {

                $res = false;
            } else {
                while ($array = mysql_fetch_array($result)) {
                    $code = $array[0];
                }
            }
        }
        mysql_close($con);
    }
    if ($res != false) {
        return $code;
    } else {
        return $res;
    }
}


function createPrivateCode() {  // Creamos un código para el proyecto.

    $chars = "abcdefghijkmnopqrstuvwxyz0123456789";
    srand((double) microtime() * 1000000);
    $i = 0;
    $pass = '';

    while ($i <= 7) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}





function searchPrivate($address, $code) {   // Buscamos entre los proyectos privados, uno que coincida con el código introducido.
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
            $result = mysql_query("SELECT id_project FROM Project_code WHERE
                code = '$code'", $con);
            if (!$result) {
                $res = false;
            } else {
                while ($array = mysql_fetch_array($result)) {
                    $id = $array[0];
                }
            }
        }
        mysql_close($con);
    }

    if ($res == FALSE) {
        return $res;
    } else {
        return $id;
    }


}

/* Funciones estadísticas del proyecto. */

function getProjectTweetCount($address, $id_project) { //Función para contar el número de tweets que contiene un proyecto.
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
            $result = mysql_query("SELECT count(id_project) FROM board_tweet WHERE id_project ='$id_project'", $con);
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

function getMilestonesCount($address, $id_project) { //Función para contar el número de milestones que contiene un proyecto.
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
            $result = mysql_query("SELECT count(id_project) FROM milestone WHERE id_project ='$id_project'", $con);
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

function getProjectTaskCount($address, $id_project) { //Función para contar el número de tasks que contiene un proyecto.
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
            $result = mysql_query("SELECT count(id_project) FROM task WHERE id_project ='$id_project'", $con);
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

function getToDoCount($address, $id_project) { //Función para contar el número de to-do que contiene un proyecto.
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
            $result = mysql_query("SELECT count(id_project) FROM to_do WHERE id_project ='$id_project'", $con);
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

?>

