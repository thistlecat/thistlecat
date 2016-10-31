# System Requirements
* Linux web server
* PHP >5.4 with PDO enabled.
* MySQL

*ThistleCAT may work on Windows or with earlier versions of PHP, but it has not been tested in these environments.*


# Installation Steps
1. Unpack ZIP file to any directory on your web server.
2. Run setup/setup_database.sh. (Feel free to modify database/table names beforehand if you want, but be sure to also update this information in config.php after setup).
3. [Import your item data](#importing-your-data) into the items table created by the script.
4. Edit config.php with your institution-specific information.


# Importing Your Data

The items table in ThistleCAT is structured as follows:

| Field          | Type         | Null | Key | Default | Extra |
|----------------|--------------|------|-----|---------|-------|
| biblionumber   | int(11)      | YES  |     | NULL    |       |
| barcode        | varchar(13)  | YES  |     | NULL    |       |
| title          | varchar(255) | YES  |     | NULL    |       |
| author         | varchar(255) | YES  |     | NULL    |       |
| oclc           | varchar(255) | YES  |     | NULL    |       |
| copyrightdate  | int(11)      | YES  |     | NULL    |       |
| issues         | int(11)      | YES  |     | NULL    |       |
| itemcallnumber | varchar(255) | YES  |     | NULL    |       |
| cn_sort        | varchar(255) | YES  |     | NULL    |       |
| lastborrowed   | datetime     | YES  |     | NULL    |       |
| special        | varchar(255) | YES  |     | NULL    |       |
| duplicates     | varchar(255) | YES  |     | NULL    |       |
| language       | varchar(255) | YES  |     | NULL    |       |
| status         | varchar(255) | YES  |     | NULL    |       |


* biblionumber - Record number of item in OPAC (biblio or item, however your OPAC links to an item). Leave blank if you don't want to use it.
* barcode - Barcode of item.
* title - Title of item. Be sure to escape any special characters before importing. (Recommend replacing any double quotes with single quotes).
* author - Author of item. 
* oclc - OCLC number. 
* copyrightdate - Copyright date of item in YYYY format.
* issues - Total checkouts for item.
* itemcallnumber - Unformatted LC call number of item.
* cn_sort - [Formatted call number](#lc-call-number-formatting) of item. This is extremely important for obtaining accurate visualizations.
* lastborrowed - Last checkout date of item, in datetime format.
* special - General field to include any additional attributes, such as items on protected title lists. Separate attributes with a comma.
* duplicates - Include OCLC numbers of alternate editions of items held in collection, separated by commas. 
* language - 3 letter [MARC language code](http://www.loc.gov/marc/languages/language_code.html)
* status - Column to hold weeding decision statuses (leave empty, this will be populated later if you have weeding decision tracking turned on)

# LC Call Number Formatting

Class numbers are always padded to 4 digits. E.g.,
* PQ239 = PQ0239

Any decimals are added to the end of the 4 digits without a decimal point. E.g.,
* G70.212 = G0070212

Any periods preceding Cutter numbers are removed and replaced with a space, if they are not already preceded by a space. E.g.,
* G70 .B6 = G0070 B6
* Z103.B2 F72 = Z0103 B2 F72
