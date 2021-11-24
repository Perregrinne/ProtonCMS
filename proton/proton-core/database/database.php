<?php
@include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/config.php';
@include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/logger.php';

$connect = ""; //Connection global for accessing your database

//This script requires PHP 7.4 or newer because of the now consistent DSN (MySQL used to be the oddball)
$username = ($is_pro) ? DB_PRO_USERNAME : DB_DEV_USERNAME;
$password = ($is_pro) ? DB_PRO_PASSWORD : DB_DEV_PASSWORD;
$database = ($is_pro) ? DB_PRO_NAME : DB_DEV_NAME;
$charset = (!SETUP_NO_DB && DB_PREFIX === 'mysql') ? ';charset=utf8mb4' : ''; //Postgres does not have a 'charset' option.
try {
    $dsn = DB_PREFIX . ':host=' . DB_HOST . ';dbname=' . $database . $charset;
    $attributes = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => true
    );
    $connect = new PDO($dsn, $username, $password, $attributes);
}
catch(PDOException $e) {
    log_msg("PDO failed to create a connection: " . $e->getCode() . ", " . $e->getMessage());
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

function create_user($connect, $name, $password, $options = null) : bool {
    //First, create the user:
    $sql = '';
    switch(DB_PREFIX) {
        case 'pgsql':
            $sql = 'CREATE USER ' . $name . ' WITH PASSWORD ' . $password;
            break;
        case 'mysql':
            break;
        default:
            log_msg("Could not create database user: an invalid database prefix was given.");
            return false;
    }
    $query = $connect->prepare($sql);
    $query->execute ();

    //Now, grant relevant permissions:
    if(isset($options) && is_array($options)) {
        foreach($options as $option) {
            //PICK UP HERE!
        }
    }
    return false;
}

function create_db($connect, $name) : bool {
    return false;
}

function postgresql_connection() : PDO {

}

function mysql_connection() : PDO {

}

function setup_db_postgresql() : bool {
    //Last step: write db into config (and log successful creation)
}

function setup_db_mysql() : bool {
    //Last step: write db into config (and log successful creation)
}

function setup_user_postgresql() : bool {
    //Last step: write user/password into config (and log successful creation)
}

function setup_user_mysql() : bool {
    //Last step: write user/password into config (and log successful creation)
}

function connect_db_postgresql() : bool {

}

function connect_db_mysql() : bool {

}

//TODO: Delete db and/or assoc users?