-- COMING SOON SQL 
-- Import this file into your database menagement to create all Coming Soon tables

CREATE TABLE members (
  email varchar(128) NOT NULL,
  join_date int(11) NOT NULL,
  PRIMARY KEY (email)
) DEFAULT CHARSET=utf8;

CREATE TABLE admin_accounts (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(16)  NOT NULL,
  hashed_password varchar(40) NOT NULL,
  email varchar(128) NOT NULL,
  PRIMARY KEY (id)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;