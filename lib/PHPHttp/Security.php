<?php

namespace PHPHttp;

class Security {

    public static function checkAdmin(Group $groupHandler)
    {
        global $CONFIG;

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('Location:403.php');
            exit;
        }
        if (!$groupHandler->isInGroup($_SERVER['PHP_AUTH_USER'], $CONFIG['adminGroup']) ) {
            header('Location:403.php');
            exit;
        }

    }
}
