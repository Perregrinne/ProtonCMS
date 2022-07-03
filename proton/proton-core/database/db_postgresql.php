<?php
//PostgreSQL and MySQL don't have perfectly matching SQL commands, so I decided that the
//main database API (database.php) will serve as an abstraction layer. The commands used
//will be agnostic to whichever RDBMS you have in use. In this file, you will find every
//command's actual SQL, specifically for PostgreSQL.
@include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/database/database.php';
//Use the connection set up in the database file
echo '<br>Postgres connection is set: ' . $connect . '<br>';