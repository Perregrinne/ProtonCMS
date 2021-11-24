# Proton CMS Documentation

## Deprecation Notice
This documentation is being moved to an external site. When I have it setup, I will be
removing this document and linking to the documentation website in the README.

## About

## Project Structure
One of the core features of the project is to separate development and production sides.
To do this, any page in the root is to be treated as a page in production. By default, the
web server application (OpenLiteSpeed, Apache, etc.) would treat this directory without any
special access restrictions, as per the .htaccess file. You could manually change this too,
if using NGinx and wanted something hidden. All PHP and HTML files are typical pages in the
production environment, and directories that are not the "proton" and "users" directories
can be accessed freely by visitors of the website. Files contained within the "proton" folder
are meant exclusively for the CMS's use only. Any updates will overwrite any changes made
to that directory, so don't touch it. It has the CMS's main functionality scripts. Within
"users" one can find the directories for the users with access to modifying the website.
Each user can only access hisher own personal directory unless they have admin privileges.
Also within users is the "development" directory where the users' modifications are saved.
Anything users need externally will be sent to the "vendor" directory. The "users" and
"proton" directories are not publicly viewable. You must be logged in to see them. When a
user with admin privileges is finished making changes in "development" he or she may run
the script that takes the development code and turns it into production code. I will see
about adding source control, but the whole point of separating development and production
sides is to catch things before they go into production.

## Database Connectivity
Knowing the default values and PDO drivers for each database we be necessary for future reference (I almost had to reinstall Postgres...). The default values are:
1. PostgreSQL:
    - PDO Driver: pgsql
    - Username: postgres
    - Password: admin
    - Port: 5432
2. Cockroach:
    - PDO Driver: pgsql
    - Username: root
    - Password: **Password is certificate-based**
    - Port: 26257
3. MySQL:
    - PDO Driver: mysql
    - Username: root
    - Password: **No password**
    - Port: 3306
4. MariaDB:
    - PDO Driver: mysql
    - Username: root
    - Password: **No password**
    - Port: 3306

## Config.php

Defined Constants:
- **SETUP_COMPLETED**: Whether or not the first-time setup has already been run. Values are true or false and false by default.
- **SETUP_AUTO**: This is mostly for web hosts and asks if it should setup everything using the default values already contained within the config.php file. If web hosts set up the config.php file for users, skip running through the first section of the first-time setup. In this case, the value is set to true. If false, users will have to go through the first section of the first-time setup as well.
- **SETUP_NO_DB**: Whether or not the user (or web host) wants databases set up for the project. If true, no databases will be created (set by the first selection input in the first-time setup) and database setup will be skipped. If false, the user must go through database setup during first-time setup.
- **SETUP_UNIFY_DB**: Some web hosts only allow 1 database. Normally, Proton would try to create 2 (production and development databases), and would allow for any number to be created.

//For web hosts: whether or not to automatically set up
//the database and its user using the preset settings
//contained in this config file.
//If set to true, use this config. If false, fail autosetup 
//("set it up for me") and force the user to set up the database.
$ = true;
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
$P_DB['PORT'] = '5432';

## Database.php
$connect is the connection variable used by PHP to connect to the database.

find_unique_dev_name() : string
This function returns a string for a development or unified database name, consisting of "dev" and a random integer. Examples of usernames that could be generated are "dev912" or "dev1005". If the initially-generated name is already taken, rather than coming up with a new number, just add 1 to the number in the name until a new database can be created with that name.

find_unique_pro_name() : string
A function that returns a string for a usable name for a production database. It consists of "pro" and a random integer. Examples of returned strings include "pro2" or "pro2074". If the initially-generated name is already taken, rather than coming up with a new number, just add 1 to the number in the name until a database can be made using that name.

find_secure_password() : string
This function returns a cryptographically secure password (in theory) that is 12 - 20 characters long. It doesn't enforce certain rules, such as having at least 1 number, 1 or more special characters, and using upper and lowercase letters. Usually, such rules might be enforced to encourage humans to use more than letters in their passwords. But in this function, each character is randomly chosen from a list of 86 total characters, so there shouldn't be a need to limit the overall number of possible characters. The only issues might be if the password begins or ends with a space, or if there are multiple consecutive spaces, it may affect readability when telling the user what the generated password was. The CMS must support passwords with spaces. Allowing for spaces to be used in passwords in the CMS (or database) means that passphrases become a viable option for securing accounts and database connections. Passphrases (depending on how they are made) may be an adequate means of locking down an account.