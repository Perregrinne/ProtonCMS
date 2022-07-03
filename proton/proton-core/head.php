<?php
    session_start();

    //USERNAME is set during login, and timeout is 3600 seconds (60 minutes)
    if(!isset($_SESSION['USERNAME']) || (time() - $_SESSION['TIME']) / 3600 < 1)
        header('Location: /admin.php');
    else
        $_SESSION['TIME'] = time(); //Reset timeout
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Proton CMS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" id="viewport">
    <meta http-equiv="X-UA-Compatible" content="ie=edge; chrome=1">
</head>
<body>