<!DOCTYPE html>
<head>
<title>Proton CMS</title>
</head>
<html>
<?php
//Production side pages render out completely anything without a variable. No unnecessary includes needed.
//Development side keeps all includes, though.
//@include ($_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/head.php');
?>
<body>
Welcome to Proton CMS! Visit <a href="/admin.php">/admin.php</a> to begin!
<?php
//echo "admin: " . password_hash("admin", PASSWORD_BCRYPT);
?>
</body>
</html>