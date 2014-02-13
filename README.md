# PHP Apache2 Basic Auth Manager

A really simple manager for .htaccess Basic Auth using .htpasswd and .htgroup files.

Original HtPasswd and HtGroup classes from 
[Yang Yang](http://www.kavoir.com/2012/04/php-class-for-handling-htpasswd-and-htgroup-member-login-user-management.html)


## TODO

Editing users and groups

## Install

1) Clone the repository under a web:

Considering you have a Apache Web Server running with ServerRoot= /var/www

    cd /var/www
    git clone https://github.com/rafaelgou/php-apache2-basic-auth-manager.git


2) Configure the application

   cd php-apache2-basic-auth-manager
   cp config-dist.php config.php
   chown -R www-data:www-data *

(or whatever user your webserver is running under).

Edit `config.php` using your favorite editor, and be sure to point to the right paths for
`.htpasswd`  and `.htgroup` files.

Pay attention to `$CONFIG['adminGroup']   = 'admin';` entry, you'll need that on the following steps.

3) Apache config

The system directory must have:

    AllowOverride All

to permit Basic Auth.

4) Create `.htpasswd` and `.htgroup` files

They can be anywhere, but must be readable by webserver user (e.g. www-data).
You need to create a initial admin user:

    htpasswd -cs /var/www/.htpasswd superuser
    chown www-data:www-data /var/www/.htpasswd	

And the adminGroup as configured above, with this user on it:

    echo 'admin: superuser' > /var/www/.htgroup
    chown www-data:www-data /var/www/.htgroup


5) Create .htaccess file for the system

   cd php-apache2-basic-auth-manager

Edit `.htaccess` using your favorite editor, and put the following content


    AuthName "Members Area"
    AuthType Basic
    AuthUserFile /var/www/.htpasswd
    AuthGroupFile /var/www/.htgroup
    <Limit GET POST>
        require group admin
    </Limit>

6) Now you can access

http://localhost/php-apache2-basic-auth-manager

Use the user/password created above.








