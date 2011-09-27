<?php

// Funciones de creación, manipulación, y obtención de tickets.

function createMilestone($address, $id_project, $title, $description, $finalization_day,
        $finalization_month, $finalization_year) { // Creamos una nueva milestone.
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
            $result = mysql_query("INSERT INTO milestone(id_project, title, description, finalization_day, finalization_month, finalization_year)
                    values('$id_project','$title','$description','$finalization_day', '$finalization_month', '$finalization_year')", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    return $res;
}

function deleteMilestone($address, $id_project, $id_milestone) { // Borramos una milestone.
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
            $result = mysql_query("DELETE from milestone WHERE id_project = '$id_project'
                    and id_milestone = '$id_milestone'", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    return $res;
}

function updateMilestone($address, $id_project, $id_milestone, $title, $description,
        $finalization_day, $finalization_month, $finalization_year) { // Actualizamos una milestone.
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
            $result = mysql_query("UPDATE milestone SET title ='$title',description = '$description',
                    finalization_day ='$finalization_day', finalization_month ='$finalization_month', finalization_year ='$finalization_year'
                    WHERE id_project = '$id_project' and id_milestone = '$id_milestone'", $con);
            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    return $res;
}

function searchMilestones($address, $id_project) { // Buscamos las milestones de un proyecto determinado.
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
            $result = mysql_query("SELECT * FROM milestone WHERE id_project ='$id_project'", $con);
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
