<?php
/*
Setup.php:

This is the first-time setup page. If the user has not yet run first-time setup,
this is the page they will land on to do that. The setting that controls this is
$P_INITSETUP['COMPLETE'] which is true if setup has been completed, false if not
(This setting is found in config.php). Setup first asks about the database which
will store much of Proton's information. Alternatively, users may opt to not use
a database. In such a case, all information will be stored in PHP files. This is
not recommended, but can be considered for small, LOCAL projects that don't have
sensitive information to hide. Proton login credentials will be stored this way.

The second task in setting up Proton is setting the username and password to get
into the CMS to make admin changes to settings and to the website. Usernames are
unique names and may even be an email. This username will by default be an admin
user account. The password for it must be 12 or more characters long, contain at
least one number, uppercase AND lowercase letters, and one or more symbols. This
password will be hashed and stored in a database (recommended) or in config.php.

The final task (optionally) is to setup the project with a template. Users could
opt to start with a blank slate but may use a template for a blog, simple static
website, or they may use a template for a kind of web application. Themes may be
chosen to give users some HTML, CSS, and Javascript to build off of in projects.
*/

session_start();

if(@include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/config.php') {
    //If setup has already been run once, don't run through it a second time!
    //Redirect to the dashboard page instead:
    if($P_INITSETUP['COMPLETE']) {
        //If they're logged in (ALWAYS CHECK!), redirect to the dashboard, otherwise to the login:
        if(!isset($_SESSION['USERNAME']) && (time() - $_SESSION['TIME']) / 3600 < 1)
            header('Location: /proton/dashboard.php');
        else
            header('Location: /admin.php');
    }
}
else {
    //If the config include fails, log it and redirect to the index page:
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/logger.php';
    log_msg("Failed to include the config file!");
    header('Location: /');
}

//See if the user has just finished the setup. If setup is finished, there should be a flag set in a URL parameter.
if(isset($_POST['complete']) && $_POST['complete'] === "done") {
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/php/proton-core/conf.php';
    set_conf('SETUP_COMPLETED', true);
    header('Location: /proton/dashboard.php'); //Since setup is complete, redirect to the dashboard where we begin other things...
}

if(isset($_POST['db-setup']) && $_POST['db-setup'] === 'skip') {
    switch($_POST['db_setup']) {
        case 'skip':
            echo 'Skipped creating the database';
            break;
        case 'manual':
            //Check $_POST for all necessary values
            break;
        case 'automatic':
            //Run automatic setup
            break;
        case 'connect':
            //Check $_POST for all necessary values
            break;
    }
}
//Once the user hits Submit, attempt to set up everything, retrying anything that previously failed,
//but not retrying anything that succeeded.

$setup_errors = "";

$database_failure = true; //If setting up the database failed, halt setup.
$user_creation_failure = true; //If database setup worked, but not user creation, halt the setup.
$datatable_creation_failure = true; //If we can't put datatables in the database(s), don't continue.

//Setup begins here:
if(isset($_POST['db-setup'])) {
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/database/database.php';
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/database/db_mysql.php';
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/database/db_postgresql.php';
    $err = false;
    switch($_POST['db-setup']) {
        case "automatic": //Let's attempt setting up the database!
            setup_unsure();
            break;
        case "manual":
            if(isset($_POST['db-driver'])) {
                switch($_POST['db-driver']) {
                    case 'pgsql':
                        $err = setup_pgsql();
                        break;
                    case 'mysql':
                        $err = setup_mysql();
                        break;
                    default:
                        //If the user chose to skip creating a database (or screwed around with the input),
                        //then by default, choose to skip making any databases. They can create them later.
                        //TODO: Make a PHP script that can overwrite constants in the config file, so we can set SETUP_NO_DB to false.
                }
            }
            break;
        default:
            //The user would have to screw with the input on the client-side to hit this.
            @include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/logger.php';
            log_msg('User did not specify "automatic" or "manual" for setup.');
            $setup_errors .= "You need to specify whether you want automatic or manual setup.<br>";
            $err = true; //Set this because we don't really need two error messages for the same issue.
            break;
    }
    //$err starts as false and remains false if no database is setup. In such a case, tell the user no database was created.
    if(!$err)
        $setup_errors .= "Failed to setup a database. If you can't get past this, skip creating a database for now (you may create one later).";
}