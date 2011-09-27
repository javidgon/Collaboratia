<?
session_start();
@include("../../Functions/db/milestone_db.php");
@include("../../Functions/authentification/user_auth.php");
@include("../../Functions/db/project_db.php");
@include("../../Functions/db/session_db.php");
@include("../../Functions/db/user_db.php");
if (isset($_SESSION['project'])) {  // Si la sesión está activa...
?>

    <!--
    To change this template, choose Tools | Templates
    and open the template in the editor.
    -->
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
        <head>    
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <style type="text/css">
                @import "../../CSS/index.css";
                @import "../../CSS/buttons.css";
                @import "../../CSS/texts.css";
                @import "../../CSS/menus.css";
                @import "../../CSS/tweets.css";
                @import "../../CSS/milestone.css";
                @import "../../CSS/alerts.css";
            </style>
            <title>Milestones list</title>
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
                    <li><a href="index.php" title="Texto">Milestones</a></li>
                    <li><a href="../to-do/index.php" title="Texto">TO-DOs</a></li>
                </ul></li>
<? if (checkAdmin('localhost', $_SESSION['email'], $_SESSION['project']) == 1) { ?>
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
        <!-- Formulario básico de creación de Milestones.-->

        <table class="formularioMilestone">
            <form action="create.php" method="POST">
                <tr><td>Create a new Milestone</td></tr>
                <tr> <td class="buttonsLeft"><button type="submit" name="send" class="positive">
                            Create
                        </button></td></tr>
            </form>
        </table>


<?php
            $array = searchMilestones('localhost', $_SESSION['project']);   // Buscamos las milestones de este proyecto.
            $i = 0;
            while ($milestones = mysql_fetch_array($array)) {   // Las recorremos una a una.
                $i++;
                if ($i % 2 == 0) {

                    echo '<table class="listabMilestone">';
                } else {
                    echo '<table class="listaMilestone">';
                } ?>
            <tr>
                <th class="nameMilestone">
        <?php echo $milestones[2]; ?>
                </th>
                <td class="dateMilestone">
            <?php echo $milestones[5] . ' / ' . $milestones[6] . ' / ' . $milestones[7]; ?>
            </td>
        </tr>
        <tr>
            <td class="descriptionMilestone" colspan="2">
<?php echo $milestones[3]; ?>
            </td>
        </tr>
        <tr class="buttonsMilestone">
            <td class="buttonsLeft">
                <form action="processing.php" method="post">
<?php echo '<input type="hidden" name="id" value="' . $milestones[1] . '"/>'; ?>
                    <button type="submit" name="edit" class="positive">
                        <img src="../../Img/icons/accept.png" alt=""/>
                        Edit
                    </button>
                </form>
            </td>
            <td class="buttonsLeft">
                <form action="processing.php" method="post">
<?php echo '<input type="hidden" name="id" value="' . $milestones[1] . '"/>'; ?>
                <button type="submit" name="delete" class="negative">
                    <img src="../../Img/icons/book_delete.png" alt=""/>
                    Delete
                </button>
            </form>
        </td>
    </tr>
</table>


<?php
            }

            if ($i == 0) {

                echo '<div class="milestonePositive">Create Milestone and have a better planning.</div>';
            } else {



                echo'<span class="titleH1">Milestones list</span>';
            }
?>


<?
        }
?>
</body>
</html>
