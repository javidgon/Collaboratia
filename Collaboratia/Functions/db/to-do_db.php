<?php

// Funciones de creación, borrado y obtención de TO-DOs y Tasks.

function createTodo($address, $id_project, $description) {  // Creamos un nuevo ToDo.

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
            $result = mysql_query("INSERT INTO to_do(id_project, description)
                        values('$id_project', '$description')", $con);

            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }
    return $res;
}

function createTask($address, $id_project, $id_todo, $status, $responsible, $name) {    // Creamos un nuevo task.

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
            $result = mysql_query("INSERT INTO task(id_project, id_todo, status, responsible, name)
                        values('$id_project', '$id_todo', '$status', '$responsible', '$name')", $con);

            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }
    return $res;
}

function taskCompleted($address, $task) {   // Completamos una tarea.

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
            $result = mysql_query("UPDATE task set status='completed' where id_task='$task'", $con);

            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }
    return $res;
}



function readTodos($address, $id_project) { // Leemos todos los TO-DOs que tiene un proyecto.
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
        $result = mysql_query("SELECT id_todo, creation_date, description from to_do where id_project='$id_project' order by creation_date DESC", $con);

        if (!$result) {
            $res = false;
        }
    }

    if ($res == false) {
        return $res;
    } else {
        return $result;
    }
}

function readTasks($address, $id) { // Leemos todas las task que tiene un TO-DO.
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
        $result = mysql_query("SELECT id_task, name, responsible, status from task where id_todo='$id'", $con);

        if (!$result) {
            $res = false;
        }
    }
    mysql_close($con);

    if($res == false){
        return $res;
    }else{
        return $result;
    }

}


function deleteTodo($address,$id_todo){ // Borramos un TO-DO pasado por parámetro, y todas sus tasks asociadas.
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
            $result = mysql_query("DELETE from To_do WHERE id_todo = '$id_todo'", $con);
            $result2 = mysql_query("DELETE from Task WHERE id_todo = '$id_todo'", $con);
            if (!$result || !$result2) {
                $res = false;
            }
        }
        mysql_close($con);
    }
    return $res;





}



?>