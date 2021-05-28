<?php
//Only logged-in admins may see this page!
session_start();

if(!isset($_SESSION['USERNAME']) || (time() - $_SESSION['TIME']) / 3600 < 1) {
    header('Location: /admin.php');
}

?>
<!Doctype html>
<head>
    <title>Web Mill CMS - Dashboard</title>
</head>
<body>
<div id="pt-container">
    <div id="pt-dashboard">
        <div class="pt-card" style="position: absolute; min-height: 100%; width: 70%; right: 0;">Page Views</div>
    </div>
    <div id="pt-dashboard">
        <div class="pt-card" style="position: absolute; min-height: 100%; width: 70%; right: 0;">To-Do List</div>
        <ul>
            <li>Move a lot of the comments out of the code and into the documentation.</li>
            <li>Allow splitting one database into multiple or consolidating into one</li>
            <li>Support CockroachDB, MS SQL Server, and others.</li>
        </ul>
    </div>
    <div id="pt-left-nav" style="position: fixed; height: 100%; width: 30%; left: 0;">
        <div class="pt-welcome">Welcome, <?= $_SESSION['USERNAME'] ?>!</div>
    </div>
</div>
<div id="footer" style="position: absolute; bottom: 0; left: 0; right: 0; height: 35px; text-align: center; margin: auto;">Copyright © 2021 Proton CMS</div>
<script src="/proton/proton-core/resources/js/proton.js"></script>
</body>
</html>