#!/bin/bash
$ mysql -u root -p hotel1<<EOFMYSQL
INSERT INTO `admin` (`uid`, `fname`, `lname`, `title`, `uaddress`, `email`, `password`, `lastlogin`, `token`, `numlogin`, `type`) VALUES
(1, 'Alan', 'Lim', 'Mr.', 'Asplan', 'lim@lim.com', 'lim', '2013-01-02 18:37:54', 'abc', 26, 1);
INSERT INTO `hotel` (`hid`, `hname`, `haddress`, `bannerUrl`, `message`, `imageUrl`, `tac`, `contact`, `step`, `zip`) VALUES
(1, 'Asia Sth Hotel', '25 Lower Kent Ridge Road', 'http://localhost/hotel/images/banner.png', 'some description', 'http://hotels.online.com.sg/DB/hotelpics/singapore/Pan_Pacific_Singapore_Hotel-Logo.jpg', 'Terms and Conditions', '+86123485', 4, '12345');
EOFMYSQL