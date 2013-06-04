<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
Title: Error Codes
This file defines constants that are used as error codes.

The errors codes are used in exception handling.
*/

/*
Constants: Database
Error codes for database errors.

DRIVER_ERROR - 0
SERVER_ERROR - 1
DATABASE_ERROR - 2
SQL_ERROR - 3

See Also:
<DbException>
*/
define('DRIVER_ERROR', 0);
define('SERVER_ERROR', 1);
define('DATABASE_ERROR', 2);
define('SQL_ERROR', 3);