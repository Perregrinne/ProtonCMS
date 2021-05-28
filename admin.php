<?php
//admin.php contains the HTML layout, login.php has the login logic.
@include_once ($_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/login.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>Proton CMS - Login</title>
</head>
<body>
    <div id="container">
        <div id="login-error">
            <?php if(!empty($login_msg)) { echo $login_msg; } ?>
        </div>
        <form role="form" action="" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" pattern="[a-zA-Z0-9]+" placeholder="username" required autofocus/><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="password" required/><br>
        <button type="submit" name="login" value="login">Log in</button>
        </form>
    </div>
</body>
</html>
