<?php

    // define global constants, and require other private scripts
    defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

    defined('URL') ? null : define('URL', "http://localhost");

    defined('SITE_ROOT') ? null :
        define('SITE_ROOT', DS.'xampp'.DS.'htdocs'.DS.'RAD-Project');
        // ^ C:/xampp/htdocs/RAD-Project
        // Alter this code to your directory's needs

    defined('SHARED_PATH') ? null : define('SHARED_PATH', SITE_ROOT . DS . 'private');

    require_once SHARED_PATH . DS . 'db.php';
    require_once SHARED_PATH . DS . 'db_credentials.php';
    require_once SHARED_PATH . DS . 'functions.php';
    require_once SHARED_PATH . DS . 'session.php';

?>
