<?php 
require_once 'include.php'; 

$groups = $groupHandler->getGroups();
ksort($groups);

$alert = '';

if (isset($_GET['username'])) {
    $username   = $_GET['username'];
    $userGroups = $groupHandler->getGroupsByUser($_GET['username']);
}

if (isset($_POST['username'])) {

    $username   = $_POST['username'];
    $userGroups = $groupHandler->getGroupsByUser($_POST['username']);

    if (strlen($_POST['password']) > 0) {
 
        if (strlen($_POST['password']) < $CONFIG['minPassword'] ) {

            $alert = '<div class="alert alert-danger">Please fill Password (min ' . $CONFIG['minPassword'] . ' characters) </div>';

        } else {
            $passwdHandler->editUser($_POST['username'], $_POST['password']);
            $groupHandler->setGroupsToUser($_POST['username'], $_POST['groups']);

            $alertType    = 'success';
            $alertMessage = "User {$_POST['username']} updated successfuly.";
            header("Location:index.php?alertType=$alertType&message=$alertMessage");
            exit;
        }
       
    } else {
        $groupHandler->setGroupsToUser($_POST['username'], $_POST['groups']);

        $alertType    = 'success';
        $alertMessage = "User {$_POST['username']} updated successfuly.";
        header("Location:index.php?alertType=$alertType&message=$alertMessage");
        exit;
    }

}

require_once '_header.php'; 
?>
    <div class="container">
        <div class="page-header">
           <h1>Edit User</h1>
        </div>

        <div class="container">
<?php echo $alert ?>
    
            <form action="userEdit.php" class="form-horizontal" method="post">
                <div class="row">
                    <div class="form-group">
                        <label for="username" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">Username</label>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <p class="form-control-static col-lg-4 col-md-4 col-sm-4 col-xs-12"><?php echo $username ?></p>
                        <input type="hidden" id="username" name="username" value="<?php echo $username ?>">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">Password</label>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to not update (<?php echo $CONFIG['minPassword'] ?> characters)">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="groups" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">Groups</label>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
<?php foreach ($groups as $groupName => $group) :?>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="groups" name="groups[]" value="<?php echo $groupName ?>" <?php echo in_array($groupName, $userGroups) ? 'checked="checked"' : '' ?>> <?php echo $groupName ?>

                            </label>
<?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>

            <hr/>
            <button type="submit" class="btn btn-primary">Edit User</button>
            <a href="index.php" class="btn btn-link">Back to List</a> 
            
        </form>


    </div>

<?php require_once '_footer.php'; ?>
