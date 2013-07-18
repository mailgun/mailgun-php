<?php

    // Your custom class dir
    define('CLASS_DIR', 'Mailgun');

    // Add your class dir to include path
    set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);

    // You can use this trick to make autoloader look for commonly used "My.class.php" type filenames
    spl_autoload_extensions('.php');

    // Use default autoload implementation
    spl_autoload_register();
?>