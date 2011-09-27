<?
session_start();
@include("../../Functions/db/user_db.php");


if (isset($_SESSION['email'])) {
?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <style type="text/css">
                @import "../../CSS/index.css";
                @import "../../CSS/buttons.css";
                @import "../../CSS/texts.css";
                @import "../../CSS/menus.css";
            </style>
                        <title>Profile info</title>
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
            <li><a href="../index.php" title="Texto">My Page</a>
                <ul>
                    <li><a href="index.php" title="Texto">My Profile</a></li>
                    <li><a href="../myProjects.php" title="Texto">My Projects</a></li>
                </ul></li>
            <li><a href="../collaborative.php" title="Texto">Collaborative Projects</a>

            </li>
            <li><a href="../create.php" title="Texto">Create New Project</a></li>

        </ul>
            <ul id="menu-horizontal2">
            <li><a href="../index.php" title="Back">Back</a>
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
        <div class="title">
            <h1>Profile information</h1>
            <?php
            $value = retrieveUser('localhost', $_SESSION['email']);
            $profile = mysql_fetch_array($value);
            echo '<p><img src="../../Img/users/' . $profile[4] . '" width="100"/></p>';
            echo '<table><tr><th>E-Mail: </th><td>' . $profile[0] . '</td>';
            echo '<th>Name: </th><td>' . $profile[1] . '</td></tr>';
            echo '<tr><th>Surname: </th><td>' . $profile[2] . '</td>';
            echo '<th>Date of birth: </th><td>' . $profile[5] . '/' . $profile[6] . '/' . $profile[7] . '</td></tr>';
            echo '<tr><th>Field: </th><td>' . $profile[8] . '</td>';
            echo '<th>University: </th><td>' . $profile[10] . '</td></tr>';
            echo '<tr><th>Name: </th><td>' . $profile[11] . '</td></tr></table>';
            ?>
            <table>
                <tr>
                    <td class="buttonsLeft">
                        <form action="processing.php" method="post">
                            <button type="submit" name="edit" class="positive">
                                <?php
                                echo '<input type="hidden" name="id" value="' . $profile[0] . '"/>';
                                ?>
                                <img src="../../Img/icons/accept.png" alt=""/>
                                Edit
                            </button>
                        </form>
                    </td>
                    <td class="buttonsLeft">
                        <form action="processing.php" method="post"><button type="submit" name="delete" class="negative">
                                <?php
                                echo '<input type="hidden" name="id" value="' . $profile[0] . '"/>';
                                ?>
                                <img src="../../Img/icons/book_delete.png" alt=""/>
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>            
            </table>
        </div>

        <? } ?>
    </body>
</html>