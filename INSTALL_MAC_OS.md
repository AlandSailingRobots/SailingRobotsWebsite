# Website on Mac OS Local Host:
_Tested using 10.14.4 with HomeBrew and PHP 3.7.5_

* [Clone](INSTALL_MAC_OS.md#Clone-repo)
* [Installation of Services](INSTALL_MAC_OS.md#Installation-of-services)
* [Database Setup](INSTALL_MAC_OS.md#Database-setup)
* [Starting Services](INSTALL_MAC_OS.md#Starting-services)
* [References](INSTALL_MAC_OS.md#References)

## Clone repo
Clone the repository in a directory of choice:
```shell script
#!/usr/bin/env bash
cd /Users/Dummy/SailingRobotsWebsite
git clone https://github.com/AlandSailingRobots/SailingRobotsWebsite.git
```

## Installation of services

### 1. Prerequisites
There are multiple services needed.
so check if http, php, phpMyAdmin and mariaDB are installed
or install them via a terminal:

```shell script
#!/usr/bin/env bash
#Optional install of HomeBrew
/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
# Install apache
brew install httpd
# Install php
brew install php
# Install phpMyAdmin
brew install phpmyadmin
# install mariaDB
brew install mariadb
```

### 2. Connection to Database
To setup the mariaDB password:`mysqladmin -u root password 'yourpassword`
### 3. Changes to `php.ini`
The `php.ini` file need's to be changed a bit. The file can most likely be found at `/usr/local/etc/php/*php_version number*/php.ini`
```
#/usr/local/etc/php/*php_version number*/php.ini
..
date.timezone = Europe/Mariehamn
..
# Most likely already on
display_errors = On
..
# For uploading the initial database it is best to increase the file size for the upload:
upload_max_filesize = 250M
...
post_max_size = 250M
...
```
### 4. Changes to `phpmyadmin.config.inc.php`
Permit empty passwords in phpMyAdmin by editing `/usr/local/etc/phpmyadmin.config.inc.php`

    ```
    ...
    $cfg['Servers'][$i]['AllowNoPassword'] = true;
    ...
    ```
### 5. Custom Setup for Running PHP and phpMyAdmin.
As suggested by HomeBrew it's best to start phpMyAdmin and php-fpm when apache starts. This can be done by creating an additional file used by the httpd config.
Create a file in the extra directory where httpd is installed e.g:`/usr/local/etc/httpd/extra/php-phpmyadmin.conf`

:warning::exclamation: This is depended on changes of the php version. If this doesnt work look at the output of brew during installation :exclamation::warning: 
```
#/usr/local/etc/httpd/extra/php-phpmyadmin.conf
# For PHP
LoadModule php7_module /usr/local/opt/php/lib/httpd/modules/libphp7.so

<FilesMatch \.php$>
    SetHandler application/x-httpd-php
</FilesMatch>

# Finally, check DirectoryIndex includes index.php
DirectoryIndex index.php index.html

#For phpMyAdmin
Alias /phpmyadmin /usr/local/share/phpmyadmin
<Directory /usr/local/share/phpmyadmin/>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    <IfModule mod_authz_core.c>
        Require all granted
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order allow,deny
        Allow from all
    </IfModule>
</Directory>
```

### 6. Changes to `httpd.conf`
edit the httpd.conf file with a link to the file made in step 5 and other necessary steps. The file can most likely be found at `/usr/local/etc/httpd/httpd.conf`

```
#/usr/local/etc/httpd/httpd.conf
..
ServerName localhost
...
#Change the Documentroot and Directory lines to own directory
DocumentRoot "/Users/Dummy/SailingRobotsWebsite"
   <Directory "/Users/Dummy/SailingRobotsWebsite">
..
## Uncomment the following lines:
LoadModule proxy_module lib/httpd/modules/mod_proxy.so
..
LoadModule proxy_fcgi_module lib/httpd/modules/mod_proxy_fcgi.so
..
## To add the the file from step 5 add the path from it E.G:
Include /usr/local/etc/httpd/extra/php-phpmyadmin.conf   
```
## Database Setup

### Getting the current database:
1. Log in at [HostGator](https://gator3083.hostgator.com:2083/)
2. Go to *phpMyAdmin*
3. Export database

  * *Export* -> *Custom*
  * Deselect all database named ithaax_wrdp\*
  * Optional: Select gzip as *Compression* under *Output* for quicker download
  * Under object creation options check:
    * Add DROP DATABASE IF EXISTS statement
    * Add DROP TABLE / VIEW / PROCEDURE / FUNCTION / EVENT / TRIGGER statement
  * Press *Go* and save the SQL-file on your computer


### Import downloaded database to local server:

Import the database using mysql as database user root on the commandline. You need to be in the same directory or give the full path the SQL-file you downloaded in the previous step.
```shell script
#!/usr/bin/env bash
gzip localhost.sql.gz | mysql -v --user=root --password=your_password 
```

  :exclamation: The `mysql -v` option produces lots of verbose output in your terminal. If you want it to stay quiet omit the option.

(if you downloaded as raw text without compression do `mysql -v --user=root --password=your_password  < localhost.sql` instead)   


## Starting services
To start the services:
   ```shell script
   #!/usr/bin/env bash
   #For the MariaDB
   mysql.server start
   
   #For the Apache either:
   # (1) Through Apache Direct
   sudo apachectl -k start
   # (2) Or through httpd
   sudo httpd -k start
   ```
   
   if ```mysql.server start``` gives as an error: `Can't read dir of '/usr/local/etc/my.cnf.d'` then `mkdir /usr/local/etc/my.cnf.d`

Or run the corresponding [script](run_httpd_mysql.sh) with `sh run_httpd_mysql.sh`
## References
[Arch Linux guide](https://github.com/AlandSailingRobots/SailingRobotsDocs/blob/master/Website%20on%20localhost%20guide.md)

[MariaDB](https://mariadb.com/kb/en/library/installing-mariadb-on-macos-using-homebrew/)