<?php 
require_once 'include.php'; 

$users = $passwdHandler->getUsers();
ksort($users);

$alert = '';

if (isset($_POST['groupname'])) {

    if (strlen($_POST['groupname']) < $CONFIG['minGroupname'] ) {

        $groupname = $_POST['groupname'];
        $alert = '<div class="alert alert-danger">Please fill Group Name (min ' . $CONFIG['minGroupname'] . ' characters)</div>';

    } else {

        $groupHandler->addGroup($_POST['groupname']);

        if (isset($_POST['users'])) {

            foreach($_POST['users'] as $username) {

                $groupHandler->addUserToGroup($username, $_POST['groupname']);

            }
        }

        $alertType    = 'success';
        $alertMessage = "Group {$_POST['groupname']} added successfuly.";
        header("Location:index.php?alertType=$alertType&message=$alertMessage");
        exit;
    }    

}

require_once '_header.php'; 
?>
    <div class="container">
        <div class="page-header">
           <h1>Add Group</h1>
        </div>

        <div class="container">
<?php echo $alert ?>
    
            <form action="groupAdd.php" class="form-horizontal" method="post">
                <div class="row">
                    <div class="form-group">
                        <label for="groupname" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">Group Name</label>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <input type="text" class="form-control" id="groupname" name="groupname" placeholder="Group Name, no spaces, min <?php echo $CONFIG['minUsername'] ?> characters" value="<?php echo isset($groupname) ? $groupname : '' ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="users" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">Users</label>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
<?php foreach ($users as $username => $user) :?>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="users" name="users[]" value="<?php echo $username ?>"> <?php echo $username ?>

                            </label>
<?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>

            <hr/>
            <button type="submit" class="btn btn-primary">Add Group</button>
            <a href="index.php" class="btn btn-link">Back to List</a> 
            
        </form>


    </div>

<?php require_once '_footer.php'; ?>
