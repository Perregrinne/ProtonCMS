<?php
@include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/database/database.php';
//Use the connection set up in the database file
echo '<br>Postgres; connection is set: ' . isset($connect) . '<br>';