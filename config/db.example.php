<?php
/**
 * Copy to config/db.local.php on the server and fill in the cPanel MySQL details.
 * config/db.local.php is git-ignored: never commit credentials.
 *
 * Local development needs nothing: without this file the dashboard uses
 * storage/app.sqlite automatically.
 */
return [
    'driver' => 'mysql',
    'host'   => 'localhost',      // cPanel usually uses "localhost"
    'port'   => 3306,
    'name'   => 'cpaneluser_epx', // database name
    'user'   => 'cpaneluser_epx', // database user
    'pass'   => 'change-me',      // database password
];
