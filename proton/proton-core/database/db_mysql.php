<?php
//Whereas database.php is the general-purpose API for executing work in SQL, db_mysql.php is 
//responsible for holding all of the possible SQL commands to be put together into the SQL query.
//Since other RDBMSs are supported and their SQL commands don't perfectly match in some cases, we
//would end up with too much clutter if all commands were stuffed into database.php.
@include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/database/database.php';
//Use the connection set up in the database file
echo '<br>MySQL connection is set: ' . $connect . '<br>';
