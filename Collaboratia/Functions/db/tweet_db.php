<?php

// Funciones para la creación, borrado y listado de Tweets.

function writeTweet($address, $sender, $description, $id_project) { // Creamos un nuevo Tweet.
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
            srand(time());

            $random = rand(1, 9000000);
            $result = mysql_query("INSERT INTO Tweet(sender, description,random)
                        values('$sender', '$description','$random')", $con);

            $id = getIdTweet('localhost', $sender, $description, $random, $con);    // Obtenemos el id del Tweet recién creado.

            writeBoardTweet('localhost', $id, $id_project,$con);    // Creamos la relación entre el Tweet y el proyecto.

            if (!$result) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    return $res;
}

function writeBoardTweet($address, $id, $id_project,$con) { // Creamos la relación entre el Tweet y el proyecto.


            $result = mysql_query("INSERT INTO Board_tweet(id_tweet, id_project)values('$id', '$id_project')", $con);
            if (!$result) {
                $res = false;
            }else{
                $res = true;
            }
    
    

    return $res;
}

function getIdTweet($address, $sender, $description, $random, $con) {   // Obtenemos el id del Tweet recién creado.


    $result = mysql_query("SELECT id_tweet FROM tweet WHERE sender = '$sender' and description = '$description' and random = '$random'", $con);

    if (!$result) {
        $res = false;
    } else {

        while ($array = mysql_fetch_array($result)) {
            $res = true;
            $id = $array[0];
        }
    }


    if ($res != true) {

        return $res;
    } else {
        return $id;
    }
}

function deleteTweet($address, $id_tweet) { // Borramos el Tweet, y su relación con el proyecto.

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

            $result = mysql_query("DELETE FROM Tweet WHERE id_tweet = '$id_tweet'", $con);
            $result2 = mysql_query("DELETE FROM Board_tweet WHERE id_tweet = '$id_tweet'", $con);

            if (!$result || !$result2) {
                $res = false;
            }
        }
        mysql_close($con);
    }

    return $res;
}

function searchTweets($address, $project) { // Buscamos todos los Tweets de un proyecto.


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

            $limit = mysql_query("SELECT n_tweets FROM Preferences WHERE id_project = '$project'", $con);

            $n_tweets = getNumberTweets($con, $project);    // Obtenemos el número máximo de tweets por página para ese proyecto.

            if ($res != false) {
                $result = mysql_query("SELECT tweet.sender, tweet.description, tweet.creation_date,tweet.id_tweet from tweet, board_tweet WHERE tweet.id_tweet = board_tweet.id_tweet
    and board_tweet.id_project = '$project' order by tweet.id_tweet DESC limit $n_tweets", $con);

                if (!$result) {
                    $res = false;
                }
            }
        }

        mysql_close($con);
    }
    if (!$res) {
        return $res;
    } else {
        return $result;
    }
}

function getNumberTweets($con, $id_project) {   // Obtenemos el número máximo de tweets por página para ese proyecto.

    $limit = mysql_query("SELECT n_tweets FROM Preferences WHERE id_project = '$id_project'", $con);

    if ($limit) {

        while ($array = mysql_fetch_array($limit)) {

            $n_tweets = $array[0];
            $res = true;
        }
    } else {
        $res = false;
    }

    if ($res == false) {
        return $res;
    } else {
        return $n_tweets;
    }
}

?>