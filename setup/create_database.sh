#!/bin/bash
set -e
PASS1=`< /dev/urandom tr -dc _A-Z-a-z-0-9 | head -c${1:-32};echo;`
PASS2=`< /dev/urandom tr -dc _A-Z-a-z-0-9 | head -c${1:-32};echo;`
USER1="thistleuser"
USER2="thistleedit"
DATABASE="thistlecat"
ITEMTABLE="allitems"



echo "Enter MySQL root password"
mysql -uroot -p <<MYSQL_SCRIPT
CREATE DATABASE $DATABASE;
CREATE TABLE $DATABASE.$ITEMTABLE (biblionumber INT, barcode VARCHAR(13), title VARCHAR(255), author VARCHAR(255), oclc VARCHAR(255), copyrightdate INT, issues INT, itemcallnumber VARCHAR(255), cn_sort VARCHAR(255), lastborrowed DATETIME, special VARCHAR(255), duplicates VARCHAR(255), language VARCHAR(255), status VARCHAR(255));
CREATE TABLE $DATABASE.LCclasses (class VARCHAR(3), description VARCHAR(255));
LOAD DATA LOCAL INFILE "$PWD/lcclasses.csv" INTO TABLE $DATABASE.LCclasses FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\r\n';
CREATE USER '$USER1'@'localhost' IDENTIFIED BY '$PASS1';
GRANT SELECT ON $DATABASE.* TO '$USER1'@'localhost';
CREATE USER '$USER2'@'localhost' IDENTIFIED BY '$PASS2';
GRANT SELECT,UPDATE ON $DATABASE.* TO '$USER2'@'localhost';
FLUSH PRIVILEGES;
MYSQL_SCRIPT

echo "MySQL users created. Copy these passwords into config.php!"
echo "Username:   $USER1"
echo "Password:   $PASS1"
echo "Username:   $USER2"
echo "Password:   $PASS2"
