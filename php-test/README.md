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
Not Available.

# Test 3: pagination function
Used regular expressions, see file: test3.php
