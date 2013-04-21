#!/bin/sh                                                                       
sed -i '                                                                        
/CREATE TABLE WaterMarkPhotos/ {                                                
    N                                                                           
    N                                                                           
    N                                                                           
    N                                                                           
    N                                                                           
    N                                                                           
    N                                                                           
    N                                                                           
    N                                                                           
    s/Latitude[\t]*Int,//
    s/Longitude[\t]*Int//
    s/Description[\t]*Varchar (510),/Description\t\tVarchar (510),\n\tPhotoType\t\tVarChar(510)/
}' ../apache/database/schema/database_schema.sql
##  s/\<ID\>[\t]*Int,/ID\tInt Not Null Auto_Increment,/                         
##  s/Longitude[\t]*Int/Longitude\tInt,\n\tPrimary Key(ID)/                     
