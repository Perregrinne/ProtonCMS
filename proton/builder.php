<?php @include_once $_SERVER['DOCUMENT_ROOT'] . "/proton/proton-core/builder.php"; ?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Proton Page Builder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.12.17/css/grapes.min.css" />
</head>
<body>
    <div id="grapes-canvas" style="border: 1px solid #ddd"></div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.12.17/grapes.min.js"></script>
<script>
    const editor = grapesjs.init({
        container: "#grapes-canvas",
        height: '300px',
        width: 'auto',
        storageManager: false,
        panels: { defaults: []},
        });
</script>
</html>