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
    s/Latitude[\t]*Int,/Latitude\t\tDouble,/
    s/Longitude[\t]*Int/Longitude\t\tDouble/
}' ../apache/database_file/schema/database_schema.sql
##  s/\<ID\>[\t]*Int,/ID\tInt Not Null Auto_Increment,/                         
##  s/Longitude[\t]*Int/Longitude\tInt,\n\tPrimary Key(ID)/                     
