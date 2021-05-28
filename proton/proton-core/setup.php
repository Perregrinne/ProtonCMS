<?php
/*
1st-time setup tasks:
- Setup the database
- Set a proper username and password for the administrative account(s).
*/
session_start();

if(@include_once ($_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/config.php')) {
    //If setup has already been run once, don't run through it a second time!
    //Redirect to the dashboard page instead:
    if($P_INITSETUP['COMPLETE']) {
        //If they're logged in (ALWAYS CHECK!), redirect to the dashboard, otherwise to the login:
        if(!isset($_SESSION['USERNAME']) && (time() - $_SESSION['TIME']) / 3600 < 1) {
            header('Location: /proton/dashboard.php');
        }
        else {
            header('Location: /admin.php');
        }
    }
}
else {
    //If the config include fails, log it and redirect to the index page:
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/logger.php';
    log_msg("Failed to include the config file!");
    header('Location: /');
}

//Once the user hits Submit, attempt to set up everything, retrying anything that fails,
//but not retrying anything that succeeded.
$setup_errors = "";
if(isset($_POST['db-setup']))
{
    switch($_POST['db-setup'])
    {
        case "automatic":
            //Let's attempt setting up the database!
            break;
        case "manual":
            break;
        default:
            //The user would have to screw with the input on the client-side to hit this.
            @include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/logger.php';
            log_msg('User did not specify "automatic" or "manual" for setup.');
            break;
    }
}