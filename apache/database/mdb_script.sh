#!/bin/bash

rm ../apache/database/schema/*
rm ../apache/database/table_statements/*

## Use the sed script to convert the extracted schema into the format that is
## needed for MySQL
mdb-schema ../apache/database/database_file.mdb | 
../apache/database/sedscript.sed >| ../apache/database/schema/database_schema.sql

## Change the permissions on the schema file so that we can execute it as a
## MySQL statement
chmod +x ../apache/database/schema/database_schema.sql

./../apache/database/alter_wmphotos.sh

## Extract all the names of the tables and write them to a file for use with
## the table extractions script
mdb-tables ../apache/database/database_file.mdb | 
sed 's/ /\n/g' >| ../apache/database/database_tables.txt

for LINE in $(cat ../apache/database/database_tables.txt); do
    ## Write each table insert into it's own .sql file
    mdb-export -I ../apache/database/database_file.mdb $LINE | sed -e 's/)$/)\;/' | 
    ../apache/database/sedscript.sed >| ../apache/database/table_statements/${LINE}.sql
done;

## Change the permissions on the insert statement files so that we can 
## execute it as a MySQL statement
chmod +x ../apache/database/table_statements/*.sql
