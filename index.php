<!DOCTYPE html>
<html lang="en">
<head>
<title>Proton CMS</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<?php
//In Development, all pages use includes for templates (head/footer/nav/etc...), but in production, all
//scripts have been compiled into one (at least for html templating) to minimize includes.
//TODO: Benchmark how much of a difference this really makes, depending on the number of includes.
//@include ($_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/head.php');
?>
</head>
<body>
Welcome to Proton CMS! Visit <a href="/admin.php">/admin.php</a> to begin!
<?php
//echo "admin: " . password_hash("admin", PASSWORD_BCRYPT);
?>
</body>
</html>