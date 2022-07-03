<?php
/*
nodb.php ("No Database")

This file allows users to skip the creation of a database during setup.
Users may opt to go this route if they don't have an RDBMS installed or issues
arose during setup that prevented successful setup of a database. We'd want to
push to have a database, but it would also be good to allow them to set one up
at a later date, if so desired (or required).

More specifically, this file contains functions to help authenticate users 
without the need for a database. Because we have no database, we authenticate
the user with a JWT we write to the conf.php file.
*/

