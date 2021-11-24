<?php
@include_once ($_SERVER['DOCUMENT_ROOT'] . "/proton/proton-core/config.php");
@include_once ($_SERVER['DOCUMENT_ROOT'] . "/proton/proton-core/logger.php");

function db_connect() : PDO {
    $connect = "";
    //Create a database connection to use:
    try {
        $prefix = DB_PREFIX;
        $host = (SETUP_UNIFIED_DB) ? DB_DEV_HOST : DB_PRO_HOST;
        $dbname = (SETUP_UNIFIED_DB) ? DB_DEV_NAME : DB_PRO_NAME;
        $dbuser = (SETUP_UNIFIED_DB) ? DB_DEV_USERNAME : DB_PRO_USERNAME;
        $dbpassword = (SETUP_UNIFIED_DB) ? DB_DEV_PASSWORD : DB_PRO_PASSWORD;
        //Use the connection once if initial setup has not been run.
        //Once the database has been set up, create a new connection
        //to use for querying. The new connection would be reusable:
        $persist = SETUP_COMPLETED;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT         => $persist,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $dsn = "$prefix:host=" . $host . ";";
        //The DSN varies by prefix:
        switch($prefix) {
            case 'pgsql':
                //We won't have a database during setup, so we may use "postgres" instead (in the config):
                $dsn .= "dbname=$dbname"; 
                //$dsn .= ";user=$dbuser;password=$dbpassword";
                $connect = new PDO($dsn, $dbuser, $dbpassword, $options);
                break;
            case 'mysql':
                $dsn .= (isset($dbname)) ? "dbname=$dbname;charset=utf8mb4" : "charset=utf8mb4";
                //Database connection variable:
                $connect = new PDO($dsn, $dbuser, $dbpassword, $options);
                break;
        }
        return $connect;
    }
    catch (PDOException $e) {
        log_msg("PDO failed to create a connection: " . $e->getCode() . ", " . $e->getMessage());
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    } //We can now use $connect to connect to the database and run SQL code.
}

/*
  Determine if Postgres, MySQL, or some other supported database server has been installed on the web server
  and create multiple databases for it. Development and Production databases should be separate, but if that
  setting is specified by web hosts to keep it at one database only one will be created. Returns an error if
  setup failed. Setup fails if the RDBMS is installed but not running. Setup fails if any values are missing
*/
function setup_unsure() : string {
    //Try setting it up using Postgres first:
    $error = setup_pgsql();
    //Return with no errors if the database(s) were set up without issues:
    if($error == "")
        return "";
    //If that didn't work, try MySQL instead:
    $error = setup_mysql();
    if($error == "") {
        return "";
    }
    //And if the above attempts also failed, return an error message:
    log_msg("Failed to setup any databases with an unspecified RDBMS.");
    return "Failed to setup any databases with an unspecified RDBMS.";
}

/*
The function attempts to setup a database and a user specifically for that database.
The only time one might expect to use this is when creating new projects. This is an
important step during the first-time setup but may still be used elsewhere by users.
*/
function setup_pgsql($is_pro = false) : string {
    @include_once ($_SERVER['DOCUMENT_ROOT'] . "/proton/proton-core/config.php");
    //Unfortunately, we can't use $connect here, because it relies on having the prefix specified
    //ahead of time in the config. But at setup, we can't guarantee that it has been already set.
    //We have to set up a new, one-time connection and provide it with "pgsql" as the DSN prefix.
    try {
        $user = ($is_pro) ? DB_PRO_USERNAME : DB_DEV_USERNAME;
        $password = ($is_pro) ? DB_PRO_PASSWORD : DB_DEV_PASSWORD;
        $dsn = "pgsql:host=localhost;dbname=postgres";
        $option = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,];
        $pg_connect = new PDO($dsn, $user, $password, $option);
    }
    catch (\PDOException $e) {
        log_msg("PDO failed to create a connection to PostgreSQL: " . $e->getCode() . ", " . $e->getMessage());
        return "Failed to connect to PostgreSQL.";
    }

    //With the connection established, create a user and database(s):
    $db_name_prefix = ($is_pro) ? "pro" : "dev";
    $db_name = find_unique_db_name($db_name_prefix);
    $db_user = find_unique_username();
    $db_pass = find_secure_password();
    if(!create_pg_user($db_user, $db_pass, $db_name_prefix)) {
        log_msg("PostgreSQL setup failed during user setup.");
        return "PostgreSQL setup failed during user setup.";
    }
    $err = create_pg_db($db_name, $db_user);
    $sql = $connect->prepare('GRANT ALL PRIVILEGES ON DATABASE ? TO ?');
    if(!$sql->execute([$db_db, $db_user])) {
        log_msg("Failed to grant privileges to the PostgreSQL user.");
        return false;
    }

    //If we failed to setup a postgres database, log and return the error:
    log_msg("Failed to setup any PostgreSQL databases.");
    return "Failed to setup any PostgreSQL databases.";
}

function setup_mysql() : string {
    //Unfortunately, we can't use $connect here, because it relies on having the prefix specified
    //ahead of time in the config. But at setup, we can't guarantee that it has been already set.
    //We have to set up a new, one-time connection and provide it with "mysql" as the DSN prefix.

    //If we fail to setup a mysql database, log and return the error:
    log_msg("Failed to setup any MySQL databases.");
    return "Failed to setup any MySQL databases.";
}

function create_pg_db($db_name, $db_username) : bool {
    $sql = $connect->prepare('CREATE DATABASE ? OWNER ?');
    if(!$sql->execute([$db_name, $db_username])) {
        log_msg("Failed to create the PostgreSQL database.");
        return false;
    }
    else {
        log_msg("The PostgreSQL database was created successfully.");
        return true;
    }
}

//Create Postgres User:
function create_pg_user($user = "", $password, $prefix = "") : bool {
    if($user === "" && $prefix != "") {
        $id = (int)microtime(true) % 1000;
        //We'll give Proton 6000 attempts to find a usable username.
        for($attempts = 6000; $attempts > 0; $attempts--) {
            $sql = $connect->prepare('SELECT 1 FROM pg_roles WHERE rolname = ?');
            if($sql->execute([$prefix . $id])) {
                $sql = $connect->prepare('CREATE USER ? WITH PASSWORD ?');
                if($sql->execute([$prefix . $id])) {
                    log_msg("A PostgreSQL user was successfully created.");
                    return true;
                }
                else {
                    log_msg("A PostgreSQL user could not be created.");
                    return false;
                }
            }
            $attempts--;
        }
        log_msg("PostgreSQL took too many tries to create a new user. Perhaps, try again?");
        return false;
    }
    $sql = $connect->prepare('CREATE USER ? WITH PASSWORD ?');
    if(!$sql->execute([$user, $password])) {
        log_msg("Failed to create a PostgreSQL user.");
        return false;
    }
    log_msg("A PostgreSQL user was successfully created.");
    return true;
}

//Create MySQL User:
function create_mysql_user() : bool {
    $dbpassword = (SETUP_UNIFIED_DB) ? DB_DEV_PASSWORD : DB_PRO_PASSWORD;
    $dbuser = (SETUP_UNIFIED_DB) ? DB_DEV_USERNAME : DB_PRO_USERNAME;
    $dbname = (SETUP_UNIFIED_DB) ? DB_DEV_NAME : DB_PRO_NAME;
    $sql = $connect->prepare('CREATE USER ?@? IDENTIFIED BY ?');
    if(!$sql->execute([find_unique_username(), DB_HOST, $dbpassword])) {
        log_msg("Failed to create a MySQL user.");
        return false;
    }
    $sql = $connect->prepare('GRANT ALL ON ? . * TO ?@?');
    if(!$sql->execute([$dbname, $dbuser, $dbpassword])) {
        log_msg("Failed to grant MySQL user all privileges.");
        return false;
    }

    $sql = $connect->prepare('CREATE DATABASE IF NOT EXISTS ?');
    if(!$sql->execute([find_unique_dev_name()])) {
        log_msg("Failed to create a development MySQL database.");
        return false;
    }
    if(!SETUP_UNIFIED_DB) {
        $sql = $connect->prepare('CREATE DATABASE IF NOT EXISTS ?');
        if(!$sql->execute([find_unique_pro_name()])) {
            log_msg("Failed to create a production database.");
            return false;
        }
    }
    
    //Execute using $connect and don't forget to log failures.
}

function find_unique_db_name($prefix = "proton") : string {
    $index = ((int)microtime(true)) % 1000;
    //MAX for a 32-Bit integer. Nobody should be able to have 2147483647 databases on a server, though.
    while($index < 2147483647) {
        $sql = $connect->prepare('SELECT 1 FROM pg_catalog.pg_database WHERE datname=?');
        echo 'Database: ' . $prefix . $index;
        $sql->execute(['p_' . $prefix . $index]); //Returns true if executed successfully, even if there were 0 rows fetched.
        if($sql && $sql->rowCount() > 0)
            return $prefix . $index;
        else
            $index++;
    }
    log_msg("You have encountered a bug that prevented a unique name from being found.");
    return "";
}

function find_unique_pro_name() : string {
    $index = ((int)microtime(true)) % 1000;
    $sql = $connect->prepare('SELECT 1 AS RESULT FROM postgres WHERE datname = ?');
    while(true) {
        if(!$sql->execute(["pro" . $index]))
            return "pro" . $index;
        else
            $index++;
    }
}

function find_unique_username() : string {
    $index = ((int)microtime(true)) % 1000;
    $sql = $connect->prepare('SELECT 1 AS RESULT FROM pg_roles WHERE rolname = ?');
    while(true) {
        if(!$sql->execute(["user" . $index]))
            return "user" . $index;
        else
            $index++;
    }
}

function find_secure_password() : string {
    $password = '';
    define('ALLOWED_CHARS', '0123456789 abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=.,<>[]?~`');
    define('ALLOWED_CHARS_LEN', 86);
    foreach(range(0, random_int(12, 20)) as $character_index)
        $password .= ALLOWED_CHARS[random_int(0, ALLOWED_CHARS_LEN)];
    return $password;
}