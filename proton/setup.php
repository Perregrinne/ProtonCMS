
<?php 
@include_once ($_SERVER["DOCUMENT_ROOT"] . "/proton/proton-core/setup.php");
@include_once ($_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/database.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Proton CMS - Setup</title>
</head>
<body>
Welcome to the first-time setup!<br>
<br><?php echo $setup_errors; ?><br>
<form action="" method="post" role="form">
    First thing's first, let's setup the database:<br>
    <label for="db-setup">Set up a database:</label><br>
    <select name="db-setup" id="db-setup" onchange="dbVisibility()">
        <option id="db-setup-auto" value="automatic" selected>Set it up for me.</option><!-- Automatically set db settings and generate one. -->
        <option id="db-setup-manual" value="manual">I will set it up.</option><!-- User sets the username, password, port, database name. -->
    </select><br>
    <!-- ON THE CLIENT SIDE, YOU CAN SWITCH VISIBILITY BETWEEN driver FORM AND connect FORM, BUT CHECK YOU HAVE CORRECT INFO ON THE SERVER SIDE! -->
    <!-- This means that database creation stuff can't be sent with connection to existing database info -->
    <div id="db-div" style="display: none;">
    <hr>
        <label for="db-driver">What database?</label><br>
        <select name="db-driver" id="db-driver">
            <!-- TODO: In documentation: MySQL driver should be compatible with MySQL and MariaDB. -->
            <option value="mysql">MySQL</option>
            <!-- TODO: In documentation: CockroachDB is compatible with the Postgres driver. -->
            <option value="pgsql">PostgreSQL</option>
            <!--option value="dblib">SQL Server</option--><!-- TODO: MS SQL Server support planned for another day -->
            <!-- TODO: Autodetect installation list. If multiple are installed, I am not sure what the default should be. -->
            <option value="unsure" style="visibility: visible;" selected>I don't know</option>
        </select><br>
        <input type="checkbox" id="has-db" name="has-db">
        <label for="has-db">Use this information to connect to an already existing database:</label><br>
        <input type="text" id="db-name" name="db-name" placeholder="Database Name"><br>
        <input type="text" id="db-username" name="username" placeholder="Username"><br>
        <label for="db-password">We keep track of the password, so please set it to something long, messy, and nonsensical.</label><br>
        <input type="password" id="db-password" name="password" placeholder="Password" minlength="12" maxlength="32">
        <input type="checkbox" id="db-hide-password" checked>
        <label for="db-hide-password" id="db-hide-password">Hide</label><br>
        <input type="password" id="db-confirm-password" name="db-confirm" placeholder="Confirm Password"><br>
        <label for="db-host">Usually, "localhost" works. Ask your web host for this if the setup fails.</label><br>
        <input type="text" id="db-host" name="db-host" value="localhost"><br>
        <input type="number" id="db-port" name="db-port" value="5432"><br>
        <label for="db-upload-sql">Upload SQL File (if you have one):</label><br>
        <input type="file" id="db-upload-sql" name="db-upload-sql" accept=".sql"><!-- TODO: Check server side if it's a .sql file -->
    </div>
    <div id="login-div"><hr>
        <input type="text" id="p-username" name="username" placeholder="Proton Admin Username"><br>
        <input type="password" id="p-password" name="password" placeholder="Password">
        <input type="checkbox" id="p-hide-password" checked>
        <label for="p-hide-password" id="p-hide-password">Hide</label><br>
        <input type="password" id="p-confirm-password" name="confirm" placeholder="Confirm Password"><br>
        <input type="email" id="p-email-2fa" name="2fa" placeholder="email@address.com"><br>
    </div>
    <input type="submit" id="db-submit" name="db-submit"><br>
</form>
</body>
<script>
    let dbSetupAuto = document.getElementById("db-setup-auto");
    let dbSetupManual = document.getElementById("db-setup-manual");
    let dbSetupOwn = document.getElementById("db-setup-own");
    let dbDriver = document.getElementById("db-driver");
    let submit = document.getElementById("db-submit");
    let setup = document.getElementById("db-setup");
    let dbDiv = document.getElementById("db-div");
    let dbUploadSQL = document.getElementById("db-upload-sql");

    function dbVisibility() {
        dbDiv.style.display = (setup.selectedIndex === 0) ? "none" : "inline";
    }

    /*TODO: Postgres default login is "postgres" and "admin". MySQL's is "root", no password.
            Web hosts must generate the config file ahead of time for clients, that gets used
            to create the project and its database.
    */
</script>
</html>