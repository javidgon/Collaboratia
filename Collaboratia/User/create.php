<?
session_start();
@include("../Functions/db/project_db.php");
if (isset($_SESSION['email'])) {    // Si existe una sesión...
?>
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
                @import "../CSS/index.css";
                @import "../CSS/buttons.css";
                @import "../CSS/texts.css";
                @import "../CSS/menus.css";

            </style>
                        <title>Create project</title>
        </head>
        <body>
<div class="header">
    <img style="float:right;margin-right:11em; "src="../Img/logo.png" width="150" alt="Imagen" />
    <? if (isset($_SESSION['email'])) {
    ?><p>Welcome

        <?
        echo '<b>' . $_SESSION['email'] . '</b> </p>';


    }
        ?>

</div>
        <ul id="menu-horizontal">
            <li><a href="index.php" title="Texto">My Page</a>
                <ul>
                    <li><a href="Profile/index.php" title="Texto">My Profile</a></li>
                    <li><a href="myprojects.php" title="Texto">My Projects</a></li>
                </ul></li>
            <li><a href="collaborative.php" title="Texto">Collaborative Projects</a>

            </li>
            <li><a href="create.php" title="Texto">Create New Project</a></li>
        </ul>
<ul id="menu-horizontal2">
            <li><a href="../index.php" title="Back">Back to Main Page</a>
        </ul>
        <div class="exit">
            <form action="../index.php" method="post">
                <input type="hidden" name= "end" value="end"/>
                <div class="buttons">
                    <button type="submit" class="positive">
                        <img src="../Img/icons/application_side_contract.png" alt=""/>
                        Exit
                    </button>
                </div>
            </form>

        </div>
        
        <div class="title">
            <h1>New Project</h1>
            <form method="post" action="processing.php">
                <table>
                    <tr><td>Name:</td><td><input type="text" name="name"/></td></tr>
                    <tr><td>Number of participants:</td><td>    <select name="participants" >
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>

                            </select></td></tr>
                    <tr><td>Type:</td><td>    <select name="type" >

                                <option value="public">Public</option>
                                <option value="private">Private</option>

                            </select>
                        </td></tr>
                    <tr><td>Category:</td><td>   <select name="category" >

                                <option value="computerScience">Computer Science</option>
                                <option value="ai">A. I.</option>
                                <option value="biology">Biology</option>
                                <option value="bio-technology">Bio-Technology</option>
                                <option value="networks">Networks</option>
                                <option value="communications">Communications</option>
                                <option value="industrial">Industrial</option>

                            </select></td></tr>
                    <tr>
                        <td>Description:</td><td>
<textarea rows="8" cols="30" name="description"></textarea></td>
                    </tr>
                    <tr><div class="buttonsLeft">
                        <td class="buttonsLeft"><button type="submit" name="Send" class="positive">
                                <img src="../Img/icons/accept.png" alt=""/>
                                Save
                            </button></td>

                        <td class="buttonsLeft"><button type="reset">
                                <img src="../Img/icons/book_delete.png" alt=""/>
                                Reset
                            </button></td>

                    </div></tr>

                </table>

            </form>

        </div>
        <?

    }
        ?>
    </body>
</html>
