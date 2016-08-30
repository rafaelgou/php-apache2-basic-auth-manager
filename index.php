<?php
require_once 'include.php';
require_once '_header.php';

$users = $passwdHandler->getUsersAndGroups($groupHandler);
ksort($users);
$groups = $groupHandler->getGroups();
ksort($groups);
?>
    <div class="container">
        <div class="page-header">
           <h1>PHP Apache2 Basic Auth Manager</h1>
        </div>
<?php if(isset($_GET['alertType']) && isset($_GET['message']) && $_GET['message'] != '') : ?>
    <div class="alert alert-<?php echo $_GET['alertType'] ?> "><?php echo $_GET['message'] ?></div>
<?php endif; ?>



        <div class="row">

            <div id="users" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-user"></i> USERS</div>
                    <div class="panel-body">
                        <table class="table table-striped table-rounded">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Groups</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $user) :?>
                                <tr>
                                    <th><?php echo $user['username'] ?></th>
                                    <th><?php echo implode(', ', $user['groups']) ?></th>
                                    <th>
                                        <a href="userEdit.php?username=<?php echo $user['username'] ?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i></a>
                                        <a href="userDelete.php?username=<?php echo $user['username'] ?>" data-username="<?php echo $user['username'] ?>" class="btn btn-danger btn-xs userDelete"><i class="fa fa-times"></i></a>
                                    </th>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="groups" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-group"></i> GROUPS</div>
                    <div class="panel-body">
                        <table class="table table-striped table-rounded">
                            <thead>
                                <tr>
                                    <th>Groups</th>
                                    <th>Members</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($groups as $groupName => $group) :?>
                                <tr>
                                    <th><?php echo $groupName ?></th>
                                    <th><?php echo implode(', ', $group) ?></th>
                                    <th>
                                        <a href="groupEdit.php?groupname=<?php echo $groupName ?>" data-groupname="<?php echo $groupName ?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i></a>
                                        <a href="groupDelete.php?groupname=<?php echo $groupName ?>" data-groupname="<?php echo $groupName ?>" class="btn btn-danger btn-xs groupDelete"><i class="fa fa-times"></i></a>
                                    </th>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>

<?php require_once '_footer.php'; ?>
