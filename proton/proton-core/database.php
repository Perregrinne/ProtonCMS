<?php
@include_once ($_SERVER['DOCUMENT_ROOT'] . "/proton/proton-core/config.php");

//Create a database connection to use:
try {
    $prefix = $P_DB['PREFIX'];
    $host = $P_DB['HOST'];
    $dbname = $P_DB['DBNAME'];
    $dbuser = $P_DB['USERNAME'];
    $dbpassword = $P_DB['PASSWORD'];
    //Use the connection once if initial setup has not been run.
    //Once the database has been set up, create a new connection
    //to use for querying. The new connection would be reusable:
    $persist = $P_INITSETUP['COMPLETE'];
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_PERSISTENT         => $persist,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $dsn = "$prefix:host=$host;";
    //The DSN varies by prefix:
    switch($prefix)
    {
        case 'pgsql':
            //We won't have a database during setup, so we may use "postgres" instead (in the config):
            $dsn .= "dbname=$dbname"; 
            //$dsn .= ";user=$dbuser;password=$dbpassword";
            $connect = new PDO($dsn, $dbuser, $dbpassword, $options);
            break;
        case 'mysql':
            $dsn .= (isset($dbname)) ? "dbname=$dbname;charset=utf8mb4" : "charset=utf8mb4";
            //Database connection variable:
            $connect = new PDO($dsn, $P_DB['USERNAME'], $P_DB['PASSWORD'], $options);
            break;
    }
} catch (\PDOException $e) {
    @include_once ($_SERVER["DOCUMENT_ROOT"] . '/proton/proton-core/logger.php');
    log_msg("PDO failed to create a connection: " . $e->getCode() . ", " . $e->getMessage());
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
} //We can now use $connect to connect to the database and run SQL code.

//Create User:
function p_create_db_user($user, $password)
{
    $sql = "CREATE $user";
    //Execute using $connect and don't forget to log failures.
}