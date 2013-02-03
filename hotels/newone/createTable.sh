#!/bin/bash
printf "Step 2:To Create Empty Tables for the New Hotel,Enter the Database Name You Created in Step1:\n"
read hotel
mysql -u root -p $hotel<<EOFMYSQL
CREATE TABLE IF NOT EXISTS `admin` (
`uid` int(11) NOT NULL AUTO_INCREMENT,
`fname` varchar(16) NOT NULL,
`lname` varchar(16) NOT NULL,
`title` varchar(6) NOT NULL,
`uaddress` text NOT NULL,
`email` varchar(32) NOT NULL,
`password` varchar(16) NOT NULL,
`lastlogin` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`token` varchar(16) NOT NULL,
`numlogin` int(11) NOT NULL DEFAULT '0',
`type` int(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`uid`),
UNIQUE KEY `email` (`email`)
);
CREATE TABLE IF NOT EXISTS `booking` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`rid` varchar(11) NOT NULL,
`confirmation` varchar(32) NOT NULL,
`hotelName` varchar(128) NOT NULL,
`priceName` varchar(32) NOT NULL,
`status` varchar(16) NOT NULL,
`createDate` varchar(12) NOT NULL,
`codeID` varchar(128) NOT NULL,
`cid` int(11) NOT NULL,
`currency` varchar(5) NOT NULL,
`totalPrice` float NOT NULL,
`totalRoom` int(3) NOT NULL,
`visitortax` float NOT NULL,
`totalVisitortax` float NOT NULL,
`origin` varchar(256) NOT NULL,
`amountcc` varchar(20) NOT NULL,
`autorisationcc` varchar(32) NOT NULL,
`transactioncc` varchar(256) NOT NULL,
`comment` text NOT NULL,
`roomnamelist` varchar(256) NOT NULL,
`rememberprice` varchar(256) NOT NULL,
`room1` varchar(32) NOT NULL,
`typeofbed1` varchar(32) NOT NULL,
`numberofadults1` int(3) NOT NULL,
`arrivalday1` int(2) NOT NULL,
`arrivalmonth1` int(2) NOT NULL,
`arrivalyear1` int(4) NOT NULL,
`arrivalhour1` int(2) NOT NULL,
`numberofdays1` int(2) NOT NULL,
`nonsmoking1` int(1) NOT NULL,
`numberofchildren1` int(2) NOT NULL,
`room2` varchar(32) NOT NULL,
`typeofbed2` varchar(32) NOT NULL,
`numberofadults2` int(3) NOT NULL,
`arrivalday2` int(2) NOT NULL,
`arrivalmonth2` int(2) NOT NULL,
`arrivalyear2` int(4) NOT NULL,
`arrivalhour2` int(2) NOT NULL,
`numberofdays2` int(2) NOT NULL,
`nonsmoking2` int(1) NOT NULL,
`numberofchildren2` int(2) NOT NULL,
`room3` varchar(32) NOT NULL,
`typeofbed3` varchar(32) NOT NULL,
`numberofadults3` int(3) NOT NULL,
`arrivalday3` int(2) NOT NULL,
`arrivalmonth3` int(3) NOT NULL,
`arrivalyear3` int(4) NOT NULL,
`arrivalhour3` int(2) NOT NULL,
`numberofdays3` int(2) NOT NULL,
`nonsmoking3` int(1) NOT NULL,
`numberofchildren3` int(2) NOT NULL,
`typeresa` varchar(128) NOT NULL,
`extrafield` varchar(128) NOT NULL,
`lastadminaccess` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`email` varchar(32) NOT NULL,
`hid` int(1) NOT NULL DEFAULT '1',
`step` int(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `record` (
`rid` varchar(11) NOT NULL,
`confirmation` varchar(32) NOT NULL,
`hotelName` varchar(128) NOT NULL,
`priceName` varchar(32) NOT NULL,
`status` varchar(16) NOT NULL,
`createDate` varchar(12) NOT NULL,
`codeID` varchar(128) NOT NULL,
`title` varchar(6) NOT NULL,
`name` varchar(64) NOT NULL,
`firstName` varchar(32) NOT NULL,
`address` varchar(256) NOT NULL,
`company` varchar(256) NOT NULL,
`zip` varchar(15) NOT NULL,
`city` varchar(32) NOT NULL,
`country` varchar(32) NOT NULL,
`state` varchar(32) NOT NULL,
`email` varchar(64) NOT NULL,
`tel` varchar(24) NOT NULL,
`fax` varchar(24) NOT NULL,
`currency` varchar(5) NOT NULL,
`totalPrice` float NOT NULL,
`totalRoom` int(3) NOT NULL,
`visitortax` float NOT NULL,
`totalVisitortax` float NOT NULL,
`origin` varchar(256) NOT NULL,
`amountcc` varchar(20) NOT NULL,
`autorisationcc` varchar(32) NOT NULL,
`transactioncc` varchar(256) NOT NULL,
`comment` text NOT NULL,
`roomnamelist` varchar(256) NOT NULL,
`rememberprice` varchar(256) NOT NULL,
`room1` varchar(32) NOT NULL,
`typeofbed1` varchar(32) NOT NULL,
`numberofadults1` int(3) NOT NULL,
`arrivalday1` int(2) NOT NULL,
`arrivalmonth1` int(2) NOT NULL,
`arrivalyear1` int(4) NOT NULL,
`nonsmoking1` int(1) NOT NULL,
`numberofchildren1` int(2) NOT NULL,
`room2` varchar(32) NOT NULL,
`typeofbed2` varchar(32) NOT NULL,
`numberofadults2` int(3) NOT NULL,
`arrivalday2` int(2) NOT NULL,
`arrivalmonth2` int(2) NOT NULL,
`arrivalyear2` int(4) NOT NULL,
`arrivalhour2` int(2) NOT NULL,
`arrivalhour1` int(2) NOT NULL,
`numberofdays2` int(2) NOT NULL,
`numberofdays1` int(2) NOT NULL,
`nonsmoking2` int(1) NOT NULL,
`numberofchildren2` int(2) NOT NULL,
`room3` varchar(32) NOT NULL,
`typeofbed3` varchar(32) NOT NULL,
`numberofadults3` int(3) NOT NULL,
`arrivalday3` int(2) NOT NULL,
`arrivalmonth3` int(3) NOT NULL,
`arrivalyear3` int(4) NOT NULL,
`arrivalhour3` int(2) NOT NULL,
`numberofdays3` int(2) NOT NULL,
`nonsmoking3` int(1) NOT NULL,
`numberofchildren3` int(2) NOT NULL,
`typeresa` varchar(128) NOT NULL,
`extrafield` varchar(128) NOT NULL,
`lastadminID` int(11) NOT NULL,
`lastadminTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`importSource` varchar(64) NOT NULL,
`uploadStatus` varchar(11) NOT NULL,
PRIMARY KEY (`rid`)
);
CREATE TABLE IF NOT EXISTS `staff` (
`uid` int(11) NOT NULL AUTO_INCREMENT,
`fname` varchar(16) NOT NULL,
`lname` varchar(16) NOT NULL,
`email` varchar(32) NOT NULL,
`password` varchar(32) NOT NULL,
`lastlogin` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`token` varchar(32) NOT NULL,
`numlogin` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`uid`),
UNIQUE KEY `email` (`email`)
);
CREATE TABLE IF NOT EXISTS `upload` (
`upid` int(11) NOT NULL AUTO_INCREMENT,
`uid` int(16) NOT NULL,
`fname` varchar(128) NOT NULL,
`upload_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`upid`)
);
CREATE TABLE IF NOT EXISTS `user` (
`uid` int(11) NOT NULL AUTO_INCREMENT,
`fname` varchar(16) NOT NULL,
`lname` varchar(16) NOT NULL,
`title` varchar(6) NOT NULL,
`uaddress` text NOT NULL,
`email` varchar(32) NOT NULL,
`password` varchar(16) NOT NULL,
`phone` varchar(13) NOT NULL,
`passport` varchar(16) NOT NULL,
`passportPhoto` longtext NOT NULL,
`idPhoto` longtext NOT NULL,
`lastlogin` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`token` varchar(16) NOT NULL,
`numlogin` int(11) NOT NULL DEFAULT '0',
`type` int(1) NOT NULL DEFAULT '1',
`expireDate` varchar(16) NOT NULL,
`issueDate` varchar(16) NOT NULL,
`issueCountry` varchar(64) NOT NULL,
`issueCity` varchar(64) NOT NULL,
PRIMARY KEY (`uid`),
UNIQUE KEY `email` (`email`),
UNIQUE KEY `phone` (`phone`)
);
CREATE TABLE IF NOT EXISTS `hotel` (
`hid` int(11) NOT NULL AUTO_INCREMENT,
`hname` varchar(32) NOT NULL,
`haddress` varchar(128) NOT NULL,
`bannerUrl` varchar(64) NOT NULL,
`message` text NOT NULL,
`imageUrl` varchar(128) NOT NULL,
`tac` text NOT NULL,
`contact` varchar(32) NOT NULL,
`step` int(1) NOT NULL DEFAULT '1',
`zip` varchar(10) NOT NULL,
PRIMARY KEY (`hid`)
)
EOFMYSQL
