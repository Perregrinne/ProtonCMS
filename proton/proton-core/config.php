<?php
//Set to true after first-time setup is complete:
define('SETUP_COMPLETED', true);
define('SETUP_NO_DB', false); //True: No database has been set up or user does not want one.
//"SETUP_AUTO" is for web hosts. It determines whether or not to automatically set up
//the database and its user using the preset settings contained in this config file.
//If set to true, use this config as setup by the web host. If false, fail autosetup 
//("set it up for me") and force the user to set up the database.
define('SETUP_AUTO', true);
//Whether or not to consolidate all databases into 1. This is really
//only useful to web hosts who might give users just one database to
//work with. Production and Development have different tables in the
//same unified database. Number of databases to create during setup:
//true: Create 1 DB during setup (production/development are tables)
//false: Create 2 DB at setup (production and development databases)
define('SETUP_UNIFIED_DB', false);

//Database connection information:
define('DB_DEV_USERNAME', 'postgres'); //Username for Proton's development database. If using only one database, this username is used.
define('DB_PRO_USERNAME', '');         //Username for Proton's production database.
define('DB_DEV_PASSWORD', 'admin');    //Password for Proton's development database.
define('DB_PRO_USERNAME', '');         //Password for Proton's production database.
define('DB_DEV_NAME', 'postgres');     //Name of Proton's development or unified database.
define('DB_PRO_NAME', 'postgres');     //Name of Proton's production database.
define('DB_HOST', 'localhost');
define('DB_PREFIX', 'pgsql');          //DSN prefix: mysql, pgsql, etc. Only 1 constant is used because you won't use different RDBMSs for prod/dev databases.
define('DB_PORT', '5432');             //TODO: Is this needed at all?

//File and image settings:
define('IMG_DEV_TO_WEBP', true);  //If true, images uploaded to the CMS are automatically converted to webp.
define('IMG_PROD_TO_WEBP', true); //If true, images website visitors upload to the website are converted to webp.
define('FILE_DEV_RENAME', false); //If true, files uploaded to the CMS are automatically renamed for security.
define('FILE_PROD_RENAME', true); //If true, files that website visitors upload to the website are renamed for security.