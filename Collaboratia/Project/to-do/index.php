<?php
@include("../../Functions/db/to-do_db.php");
@include("../../Functions/db/tweet_db.php");
@include("../../Functions/db/user_db.php");
@include("../../Functions/db/project_db.php");
@include("../../Functions/authentification/user_auth.php");
@include("../../Functions/db/session_db.php");
session_start();
if (isset($_SESSION['project'])) {
    $_SESSION['id_todo'] = "";  // Limpiamos el residuo de la TO-DO anterior.
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
            <title>TO-DO Creation</title>
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
            <li><a href="../index.php" title="Texto">Back</a></li>
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
            $result = SessionUser("localhost", $_SESSION['project']);   // Miramos quien está conectado actualmente.
            $i = 0;
            while ($row = mysql_fetch_array($result)) { // Los recorremos uno a uno.
                $i++;
                $emails[] = $row[0];
            }

            if ($result != false) {
                echo '<table>';
                echo '<tr><th colspan="2">Online users (' . "$i" . ')</th></tr>';

                foreach ($emails as $indice => $m) {

                    $picture = retrievePicture('localhost', $m);    // Obtenemos sus fotografías.

                    while ($row2 = mysql_fetch_array($picture)) {

                        echo "<tr><td><img width=\"50\" src=\"../../Img/users/" . $row2[0] . "\" alt=\"Imagen\"/></td><td>$m</td></tr>";
                    }
                }

                echo '</table>';
            }
            ?>
        </div>

            <div class="formularioToDo">
                <form action="index.php" method="POST">
                    <table border="0">
                        <tr><th colspan="2">Create a new To-Do</th></tr>
                        <tr> <td> Name: </td>
                            <td> <input type="text" name="description" value=""> </td>
                        </tr>
                        <tr>
                            <td class="buttonsLeft" colspan="2"><button type="submit" name="send" class="positive">
                                    Create To-Do
                                </button></td>
                        </tr>
                    </table>

                </form>
            </div>

  <?
            $flag = true;
            if (isset($_POST['send'])) {    // Si queremos crear un nuevo TO-DO...
                if ($_POST['description'] == "") {
                    $flag = false;
                    echo '<div  class="todoNegative">You need to write at least something!</div >';
                } else if (strlen($_POST['description']) > 120) {
                    echo '<div  class="todoNegative">Your To-Dos name cannot have more than 120 characters.</div >';
                } else {
                    //Filtros
                    $filtros = Array(
                        'description' => FILTER_SANITIZE_STRING
                    );

                    $entradas = filter_input_array(INPUT_POST, $filtros);
                    $id_project = $_SESSION['project'];
                    $description = $entradas['description'];

                    $res = createTodo("localhost", $id_project, $description);

                    if ($res == false) {
                        $flag = false;
                        echo '<div  class="todoNegative">A problem occurred during the process.</div >';
                    } else {
                        $flag = false;
                        echo '<div  class="todoPositive">Your To-Do was successfully created.</div>';
                    }
                }
            }

            if (isset($_POST['deleteToDo'])) {  // Si queremos borrar un To-Do.
                $res = deleteTodo('localhost', $_POST['todo']); // Borramos la ToDo.

                if ($res == false) {
                    $flag = false;
                    echo '<div  class="todoNegative">A problem occurred during the process.</div >';
                } else {
                    $flag = false;
                    echo '<div  class="todoNegative">You deleted the ToDo successfully.</div >';
                }
            }?>

        <?php
            


            $id_project = $_SESSION['project'];
            $result = readTodos("localhost", $id_project);  // Leemos todos los TO-DOs del proyecto.
            $admin = checkAdmin('localhost', $_SESSION['email'], $_SESSION['project']); // Chequeamos si somos administrador o no. Si lo somos, podremos borrar las To-Do.
            $i = 0;
            while ($row = mysql_fetch_array($result)) { // Los mostramos uno a uno.
                $i++;
                if ($i % 2 == 0) {

                    echo '<table class="listaToDo">';
                } else {
                    echo '<table class="listabToDo">';
                }

                echo '<tr><td class="name" colspan = "3">' . $row['2'] . '</td></tr><tr>';
                echo '<form method="POST" action="task.php"><td class="buttonsLeft">
                <form action="processing.php" method="post">
<input type="hidden" name="todo" value="' . $row[0] . '"/>
                    <button type="submit" name="edit" class="positive">
                        <img src="../../Img/icons/accept.png" alt=""/>
                        Enter
                    </button>
                </form>
            </td>'; ?>
        <?
                if ($admin == 1) {

                    echo '<td class="buttonsLeft">
                <form action="index.php" method="post">
<input type="hidden" name="todo" value="' . $row[0] . '"/>
                <button type="submit" name="deleteToDo" class="negative">
                    <img src="../../Img/icons/book_delete.png" alt=""/>
                    Delete
                </button>
            </form>
        </td>';
                }
                echo '<td class="date">' . $row['creation_date'] . '</td></tr>';
                echo '</table>';
            }
            if ($i == 0 && $flag == true) {

                echo '<div  class="todoPositive">Create To-Dos and have a better control of your ideas.</div >';
            } else {

                if ($flag == true) {

                    echo'<span class="titleH1">To-Dos list</span>';
                }
            }
        ?>



        <? } ?>

    </body>
</html>
