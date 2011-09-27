<?php
@include("../../Functions/db/to-do_db.php");
@include("../../Functions/db/tweet_db.php");
@include("../../Functions/db/user_db.php");
@include("../../Functions/db/project_db.php");
@include("../../Functions/authentification/user_auth.php");
@include("../../Functions/db/session_db.php");
session_start();
if (isset($_SESSION['project'])) {

    if ($_POST['todo']) {

        $_SESSION['id_todo'] = $_POST['todo'];
    }
?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <style type="text/css">
                @import "../../CSS/index.css";
                @import "../../CSS/menus.css";
                @import "../../CSS/texts.css";
                @import "../../CSS/buttons.css";
                @import "../../CSS/todo.css";
                @import "../../CSS/alerts.css";
            </style>
            <title>Task Creation</title>
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
                    <li><a href="../Milestone/index.php" title="Texto">Milestones</a></li>
                    <li><a href="index.php" title="Texto">TO-DOs</a></li>
                </ul></li>
            <? if (checkAdmin('localhost', $_SESSION['email'], $_SESSION['project']) == 1) {
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
        <div class="connection"><?
    // Usuarios online.
    $result = SessionUser("localhost", $_SESSION['project']);
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $i++;
        $emails[] = $row[0];
    }

    if ($result != false) {
        echo '<table>';
        echo '<tr><th colspan="2">Online users (' . "$i" . ')</th></tr>';

        foreach ($emails as $indice => $m) {

            $picture = retrievePicture('localhost', $m);

            while ($row2 = mysql_fetch_array($picture)) {

                echo "<tr><td><img width=\"50\" src=\"../../Img/users/" . $row2[0] . "\" alt=\"Imagen\"/></td><td>$m</td></tr>";
            }
        }

        echo '</table>';
    }
?>
        </div>



<?php
        $flag = true;
    if (isset($_POST['createTask'])) {  // Si hemos pulsado el botón de cración de la task.

        if($_POST['name'] == ""){
        $flag = false;
            echo '<div  class="todoNegative">You have to write at least something!</div >';
        }else{

        //Filtros

        $filtros = Array(
            'name' => FILTER_SANITIZE_STRING
        );

        $entradas = filter_input_array(INPUT_POST, $filtros);
        $id_project = $_SESSION['project'];
        $id_todo = $_SESSION['id_todo'];
        $responsible = $_SESSION['email'];
        $status = $_POST['status'];
        $name = $entradas['name'];

        $res = createTask("localhost", $id_project, $id_todo, $status, $responsible, $name);    // Creamos una task.

        if($res == false){
        $flag = false;
            echo '<p class="todoNegative">A problem occurred during the process.</p>';
        }else{
            $flag = false;
            echo '<div  class="todoPositive">Your Task was created successfully.</div >';
        }
        
        
        }

    }
?>

<?

    $tasks = readTasks("localhost", $_SESSION['id_todo']);  // Leemos las task.
    $i = 0;
    while ($row = mysql_fetch_array($tasks)) {  // Las recorremos una a una.
        $i++;
        if ($i % 2 == 0) {

            echo '<table class="listaTask">';
        } else {
            echo '<table class="listabTask">';
        }

        echo '<tr><td class="name" colspan="2">' . $i . '.- ' . $row['name'] . '</td></tr>';
        echo "<tr><td style=\"text-align:left; color:#585030;font-style=italic; font-size:0.8em;\">Created by: " . $row['responsible'] . "</td>";
        if($row['3'] == 'not completed'){
                    echo '<form action="processing.php" method="post">';
            echo '<td class="buttonsLeft">
                <input type="hidden" value="' . $row['0'] . '" name="id">
<input type="hidden" value="'.$row['3'].'" name="status">
                <button type="submit" name="statusChange" class="negative">
                    <img src="../../Img/icons/book_delete.png" alt=""/>
                    Not Completed
                </button>

        </td></form>';

        }else{
        echo '<form action="processing.php" method="post">';
            echo '<td class="buttonsLeft">
                <input type="hidden" value="' . $row['0'] . '" name="id">
<input type="hidden" value="'.$row['3'].'" name="status">
                    <button type="submit" name="statusChange" class="positive">
                        <img src="../../Img/icons/accept.png" alt=""/>
                        Completed
                    </button>

            </td></form>';

        }

        echo '</tr></table>';
    }
                if ($i == 0 && $flag == true) {

                echo '<div  class="todoPositive">Would you like create any task?</div >';
            }else{

                if($flag == true){

                echo'<span class="titleH1">Task list</span>';
                }
            }
        ?>


        <div class="formularioToDo">
            <form action="task.php" method="POST">
                <table border="0">
                    <tr>
                        <th colspan="2">Create new Task</th></tr><tr>
                        <td> Name: </td>
                        <td> <input type="text" name="name" value=""> </td>
                        <td> <input type="hidden" name="status" value="not completed"> </td>
                    </tr>
                    <tr>
                         <td class="buttonsLeft" colspan="2"><button type="submit" name="createTask" class="positive">
                                    Create Task
                                </button></td>
                    </tr>
                </table>
                
            </form>
        </div>

<? } ?>
    </body>
</html>
