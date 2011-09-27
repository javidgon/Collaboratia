<!-- Plantilla XHTML Strict 1.0 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>User not found</title>
	<style type="text/css">
            @import "../CSS/index.css";
            @import "../CSS/menus.css";
            @import "../CSS/alerts.css";
	</style>
                    <title>Error</title>
</head>
<body>

<div class="header">
    <img style="float:right;margin-right:11em; "src="../Img/logo.png" width="150" alt="Imagen" />
    <? if (isset($_SESSION['email']) && $flag_end != true) {
    echo'<p>Welcome <b>' . $_SESSION['email'] . '</b> </p>';


    }
        ?>

</div>

    <!-- Mostramos esta página si no se encuentra el usuario. -->
    
                    <ul id="menu-horizontal2">
                <li><a href="../index.php" title="Back">Back</a></li>
            </ul>

            <div class="infoNegative"><img width="100"src="../Img/icons/Search.png" alt="imagen" />       I'm sorry, but we cannot find you. Try again please.
            </div>

 

</body>
</html>