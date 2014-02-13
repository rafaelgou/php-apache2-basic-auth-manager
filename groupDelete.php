<?php 
require_once 'include.php'; 

$alertType    = 'info';
$alertMessage = '';

if (isset($_GET['groupname'])) {

    if ($groupHandler->groupExists($_GET['groupname'])) {

        $groupHandler->deleteGroup($_GET['groupname']);

        $alertType    = 'success';
        $alertMessage = "Group '{$_GET['groupname']}' deleted successfuly.";

    } else {
        $alertType    = 'warning';
        $alertMessage = "Group '{$_GET['groupname']}' doesn't exist.";
    }
} else {
    $alertType    = 'warning';
    $alertMessage = "Group '{$_GET['groupname']}' wasn't sent to be deleted.";
}

header("Location:index.php?alertType=$alertType&message=$alertMessage");
