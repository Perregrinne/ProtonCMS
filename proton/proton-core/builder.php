<?php
//Only logged-in admins may see this page!
session_start();

if(!isset($_SESSION['USERNAME']) || (time() - $_SESSION['TIME']) / 3600 < 1)
    header('Location: /admin.php');
else
    $_SESSION['TIME'] = time();

/*
We want to give our users access to a web page builder. I'd like to be able to make my own from scratch, but
for now, in the interest of time and being able to focus on more important parts of the CMS, I can just plug
in grapes.js. I would also like to make my own text editor from scratch too, but Ace should be fine for now.
*/
@include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/head.php';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.12.17/css/grapes.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.12.17/grapes.min.js"></script>
<?php
@include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/footer.php';