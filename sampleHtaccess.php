<?php 
require_once 'include.php'; 
require_once '_header.php'; 
?>

    <div class="container">

        <div class="page-header">
           <h1>Sample .htaccess</h1>
        </div>
        
        <pre>
AuthName "Members Area"
AuthType Basic
AuthUserFile <?php echo $CONFIG['htpasswd'] ?>

AuthGroupFile <?php echo $CONFIG['htgroup'] ?>
<Limit GET POST>
require user admin
require group writer
</Limit>
        </pre>
    </div>

<?php require_once '_footer.php'; ?>