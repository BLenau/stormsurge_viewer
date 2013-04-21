#!/bin/bash

## Use the sed script to convert the extracted schema into the format that is
## needed for MySQL
mdb-schema ../scripts/database_scripts/mcdowell_gooddatabase.mdb | 
../scripts/database_scripts/sedscript.sed >| ../scripts/database_scripts/database_schema.sql

## Change the permissions on the schema file so that we can execute it as a
## MySQL statement
chmod +x ../scripts/database_scripts/database_schema.sql

## Create the directory to store the schema file and then move it
cd ../scripts/database_scripts
rm -rf schema
mkdir schema
mv database_schema.sql schema/
cd ../../admin/

./../scripts/database_scripts/alter_wmphotos.sh

## Extract all the names of the tables and write them to a file for use with
## the table extractions script
mdb-tables ../scripts/database_scripts/mcdowell_gooddatabase.mdb | 
sed 's/ /\n/g' >| ../scripts/database_scripts/database_tables.txt

for LINE in $(cat ../scripts/database_scripts/database_tables.txt); do
    ## Write each table insert into it's own .sql file
    mdb-export -I ../scripts/database_scripts/mcdowell_gooddatabase.mdb $LINE | sed -e 's/)$/)\;/' | 
    ../scripts/database_scripts/sedscript.sed >| ../scripts/database_scripts/${LINE}.sql
done;

## Change the permissions on the insert statement files so that we can 
## execute it as a MySQL statement
chmod +x ../scripts/database_scripts/*.sql

## Create the directory to store the insert_statements
cd ../scripts/database_scripts/
rm -rf insert_statements
mkdir insert_statements
mv *.sql insert_statements
cd ../../admin/

## Move the populate SQL file back to the directory that is belongs in
mv ../scripts/database_scripts/insert_statements/populate_database.sql ../scripts/database_scripts/

mv -f ../scripts/database_scripts/insert_statements/insert_wmphotos.sql ../scripts/database_scripts/
