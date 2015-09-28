# Test 1: MySQL, PHP, JavaScript
In this case, I used the schema name `wallethub` and created the table `population`.
About the query:
* needed to create primary key for auto increment column
```
alter table population add primary key (id);
```
* filled the table using mysql
```
load data local infile './data.csv' into table wallethub.population fields terminated by '\t';
```
* added keys to handle the search function
```
alter table population add index (population);
```
and
```
alter table population add index (location);
```

About the files:
* `index.php` shows the input for the autocomplete feature
* `wh.php` is called via ajax to query the DB using PDO (DB access can be set in this file)
* `js/wh.js` handles jQuery Ajax calls
* `css/` and `img/` for look and feel


# Test 2: Wordpress plugin
Download and unzip the plugin zip file https://github.com/vallejos/wh-tests/raw/master/php-test/wh-plugin.zip into the wp-content/plugins folder of your wordpress installation.
The shortcode to include into the theme is: [whp-search]
The table population will be created on install. You can download and upload the csv file to fill the table to make it work from the Settings page of your wp-admin.
All code is under the wh-plugin/ folder.

# Test 3: pagination function
Used regular expressions, see file: test3.php
