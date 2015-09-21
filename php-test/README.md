# Test 1: MySQL, PHP, JavaScript 
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

# Test 2: Wordpress plugin


# Test 3: pagination function
Used regular expressions.

