<?php

define( 'DB_HOST', 'localhost' );
define( 'DB_USER', 'theuser' );
define( 'DB_PASS', 'thepass' );
define( 'DB_NAME', 'thedb' );

define( 'EMAIL_HOST', '{imap.gmail.com:993/imap/ssl}');
define( 'EMAIL_USER', 'email@gmail.com');
define( 'EMAIL_PASS', 'theemailpass');

define( 'SHOW_LOG', true );
define( 'WRITE_LOG', true );

define( 'LOGS_PATH', 'C:\xampp\htdocs\atmosphere-pexplorer\logs\\' );
define( 'FILES_PATH', 'C:\xampp\htdocs\atmosphere-pexplorer\files\\' );
define( 'NRG_PATH', 'C:\NRG\\' );

define( 'MIN_ATTACHMENT_SIZE', 6000 );

define( 'DELETE_RWD_FILES', TRUE );

// search from START_TIME, relative to the script execution time
define( 'START_TIME', '-6 h' );