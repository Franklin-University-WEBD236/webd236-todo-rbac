#!/bin/bash 

truncate -s 0 .apache2/log/*
#.mysql/run-mysqld.sh &
rm ToDoList.db3
sqlite3 ToDoList.db3 < ToDoList.sql
.apache2/run-apache2.sh &

tail -f -v .apache2/log/*
#wait 
