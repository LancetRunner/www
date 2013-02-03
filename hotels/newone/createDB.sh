#!/bin/bash
printf "Step 1:Enter Database Name for the New Hotel:\n"
read hotel
mysql -u root -p <<EOFMYSQL
CREATE DATABASE $hotel;
EOFMYSQL