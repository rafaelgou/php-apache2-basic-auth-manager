<?php 
require_once 'include.php'; 

$users = $passwdHandler->getUsers();
ksort($users);

$alert = '';

if (isset($_GET['groupname'])) {
    $groupname  = $_GET['groupname'];
    $groupUsers = $groupHandler->getGroup($_GET['groupname']);
}   

if (isset($_POST['groupname'])) {

        if (isset($_POST['users'])) {

            $groupHandler->setUsersToGroup($_POST['groupname'], $_POST['users']);

        } else {

            $groupHandler->setUsersToGroup($_POST['groupname'], array());

        }

        $alertType    = 'success';
        $alertMessage = "Group {$_POST['groupname']} updated successfuly.";
        header("Location:index.php?alertType=$alertType&message=$alertMessage");
        exit;

}

require_once '_header.php'; 
?>
    <div class="container">
        <div class="page-header">
           <h1>Edit Group</h1>
        </div>

        <div class="container">
<?php echo $alert ?>
    
            <form action="groupEdit.php" class="form-horizontal" method="post">
                <div class="row">
                    <div class="form-group">
                        <label for="groupname" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">Group Name</label>
                        <p class="form-control-static col-lg-4 col-md-4 col-sm-4 col-xs-12"><?php echo $groupname ?></p>
                        <input type="hidden" id="groupname" name="groupname" value="<?php echo $groupname ?>">
                    </div>

                    <div class="form-group">
                        <label for="users" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">Users</label>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
<?php foreach ($users as $username => $user) :?>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="users" name="users[]" value="<?php echo $username ?>" <?php echo in_array($username, $groupUsers) ? 'checked="checked"' : '' ?>> <?php echo $username ?>

                            </label>
<?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>

            <hr/>
            <button type="submit" class="btn btn-primary">Update Group</button>
            <a href="index.php" class="btn btn-link">Back to List</a> 
            
        </form>


    </div>

<?php require_once '_footer.php'; ?>
