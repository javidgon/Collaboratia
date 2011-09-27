<?php
@include("../../Functions/db/to-do_db.php");
@include("../../Functions/authentification/user_auth.php");
session_start();
if (isset($_SESSION['project'])) {
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
            <title>Processing</title>
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
            <li><a href="task.php" title="Texto">Back</a></li>
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




        <?php

            if (isset($_POST['statusChange'])) {    // Si hemos completado una task...

                if ($_POST['status'] == 'completed') {  // Si la task ya estaba completada anteriormente...

                    echo '<div class="infoNegativeError">This task has been already completed.</div>';

                } else {    // Si no...

                    $res = taskCompleted('localhost', $_POST['id']);    // Cambiamos su estatus a completado.

                    if ($res == false) {

                        echo '<div class="infoNegativeError">It happened a problem during completing.</div>';
                    } else {

                        echo '<div class="infoPositive"><img width ="100" src="../../Img/icons/Todo.png" alt="foto"/>The task has been completed!</div>';
                    }
                }
            }
}
        ?>


    </body>
</html>
