<?php 
require_once 'include.php'; 

$alertType    = 'info';
$alertMessage = '';

if (isset($_GET['username'])) {

    if ($passwdHandler->userExists($_GET['username'])) {

        $groups = $groupHandler->getGroupsByUser($_GET['username']);

        foreach($groups as $group) {
            $groupHandler->deleteUserFromGroup($_GET['username'], $group);
        }

        $passwdHandler->deleteUser($_GET['username']);

        $alertType    = 'success';
        $alertMessage = "User '{$_GET['username']}' deleted successfuly.";

    } else {
        $alertType    = 'warning';
        $alertMessage = "User '{$_GET['username']}' doesn't exist.";
    }
} else {
    $alertType    = 'warning';
    $alertMessage = "User '{$_GET['username']}' wasn't sent to be deleted.";
}

header("Location:index.php?alertType=$alertType&message=$alertMessage");
exit;


