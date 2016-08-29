<?php 
require_once 'include.php'; 

$groups = $groupHandler->getGroups();
ksort($groups);

$alert = '';

if (isset($_POST['username'])) {

    if (strlen($_POST['username']) < $CONFIG['minUsername'] || strlen($_POST['password']) < $CONFIG['minPassword'] ) {

        $username = $_POST['username'];
        $alert = '<div class="alert alert-danger">Please fill Username (min ' . $CONFIG['minUsername'] . ' characters) and Password (min ' . $CONFIG['minPassword'] . ' characters) </div>';

    } else {

        $passwdHandler->addUser($_POST['username'], $_POST['password']);

        if (isset($_POST['groups'])) {

            foreach($_POST['groups'] as $group) {

                $groupHandler->addUserToGroup($_POST['username'], $group);

            }
        }


        $alertType    = 'success';
        $alertMessage = "User {$_POST['username']} added successfuly.";
        header("Location:index.php?alertType=$alertType&message=$alertMessage");
        exit;
    }    

}

require_once '_header.php'; 
?>
    <div class="container">
        <div class="page-header">
           <h1>Add User</h1>
        </div>

        <div class="container">
<?php echo $alert ?>
    
            <form action="userAdd.php" class="form-horizontal" method="post">
                <div class="row">
                    <div class="form-group">
                        <label for="username" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">Username</label>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username, no spaces, min <?php echo $CONFIG['minUsername'] ?> characters" value="<?php echo isset($username) ? $username : '' ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">Password</label>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <input type="password" class="form-control" id="password" name="password" placeholder="<?php echo $CONFIG['minPassword'] ?> characters">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="groups" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">Groups</label>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
<?php foreach ($groups as $groupName => $group) :?>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="groups" name="groups[]" value="<?php echo $groupName ?>"> <?php echo $groupName ?>

                            </label>
<?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>

            <hr/>
            <button type="submit" class="btn btn-primary">Add User</button>
            <a href="index.php" class="btn btn-link">Back to List</a> 
            
        </form>


    </div>

<?php require_once '_footer.php'; ?>
