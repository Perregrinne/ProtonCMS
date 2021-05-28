# Proton CMS Documentation
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