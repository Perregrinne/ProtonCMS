<?php
session_start();

//If the user is logged in, redirect to the dashboard:
if(isset($_SESSION['USERNAME']))
    header('Location: /proton/dashboard.php');

if(@include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/config.php') {
    //If first-time setup has never been run before, run through it.
    if(!$P_INITSETUP['COMPLETE']) {
        header('LOCATION: /proton/setup.php');
    }
    else { //If first-time setup has been completed, login normally:
        @include_once $_SERVER['DOCUMENT_ROOT'] . "/proton/proton-core/database/database.php";

        $login_msg = '';
        if(isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            //Hit up the database for some creds, homie:
            $query = $connect->prepare("SELECT * FROM protonusers WHERE Username = :username");
            $query->execute(['username' => $username]);
            $result = $query->fetch(PDO::FETCH_ASSOC); //We expect 0 or 1 row ONLY to be returned.

            if(isset($result) && password_verify($password, $result['Password'])) {
                $_SESSION['TIME'] = time(); //Track [in]activity time.
                $_SESSION['USERNAME'] = $username; //Identify the logged in user to prevent repeated login requests.
                //Redirect to the dashboard:
                header('Location: /proton/dashboard.php');
            }
            else {
                $login_msg = 'Incorrect username or password!';
            }
        }
    }
}