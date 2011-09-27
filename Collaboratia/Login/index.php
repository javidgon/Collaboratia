<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Log in</title>
        <style type="text/css">
            @import "../CSS/index.css";
            @import "../CSS/menus.css";
            @import "../CSS/texts.css";
            @import "../CSS/buttons.css";
        </style>
            <title>Login</title>
    </head>
    <body>
<div class="header">
    <img style="float:right;margin-right:11em; "src="../Img/logo.png" width="150" alt="Imagen" />
    <? if (isset($_SESSION['email']) && $flag_end != true) {
    ?><p>Welcome

        <?
        echo '<b>' . $_SESSION['email'] . '</b> </p>';


    }
        ?>

</div>
        <ul id="menu-horizontal3">
            <li><a href="../index.php" title="Back">Back</a>
        </ul>
        <div class="title">
            <h1><img width="50"src="../Img/icons/Register.png" alt="Imagen"/> &nbsp;Log in </h1>
            <form action="processing.php" method="post">
                <table>
                    <tr>
                        <td>E-mail:</td><td> <input type="text" name="email" value="" /></td></tr>
                    <tr>
                        <td>Password:</td><td> <input type="password" name="password" value="" /></td></tr>
                    <tr><div class="buttonsLeft">
                        <td class="buttonsLeft"><button type="submit" name="Auth" class="positive">
                                <img src="../Img/icons/accept.png" alt=""/>
                                Send
                            </button></td>

                        <td class="buttonsLeft"><button type="reset">
                                <img src="../Img/icons/book_delete.png" alt=""/>
                                Reset
                            </button></td>

                    </div></tr>
                </table>
            </form>
            If you are not in the community yet, you can sign in
            <a href="../Register/index.php">here</a>!
        </div>
    </body>
</html>
