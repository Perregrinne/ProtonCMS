<?php
//Set to true after first-time setup is complete:
$P_INITSETUP['COMPLETE'] = false;
//For web hosts: whether or not to automatically set up
//the database and its user using the preset settings
//contained in this config file.
//If set to true, use this config. If false, fail autosetup 
//("set it up for me") and force the user to set up the database.
$P_INITSETUP['AUTO'] = true;
//Whether or not to consolidate all databases into 1. This is really
//only useful to web hosts who might give users just one database to
//work with. Production and Development have different tables in the
//same unified database. Number of databases to create during setup:
//true: Create 1 DB during setup (production/development are tables)
//false: Create 2 DB at setup (production and development databases)
$P_INITSETUP['UNIFYDB'] = false;


//Database connection information:
$P_DB['USERNAME'] = 'postgres'; //Username for Proton's database only.
$P_DB['PASSWORD'] = 'admin'; //Password for Proton's database only.
$P_DB['DBNAME'] = 'postgres'; //The name of the database
$P_DB['HOST'] = 'localhost'; //Shouldn't need to be anything else, really.
$P_DB['PREFIX'] = 'pgsql'; //DSN prefix: mysql, pgsql, etc.
$P_DB['PORT'] = '5432'; //TODO: Is this needed at all?