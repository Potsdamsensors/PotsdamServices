CREATE DATABASE potsdam_v1;

use potsdam_v1;

CREATE TABLE accounts (

 	id INT AUTO_INCREMENT,
    account_id VARCHAR(40),
	status INT,
	PRIMARY KEY (id)
);

CREATE TABLE customers (

 	id INT AUTO_INCREMENT,
    customer_name VARCHAR(20),
	customer_email VARCHAR(20),
	customer_password VARCHAR(10),
	PRIMARY KEY (id),
	FOREIGN KEY (id) REFERENCES accounts(id)
);

/*one-many*/
CREATE TABLE devices (

 	id INT AUTO_INCREMENT,
    device_id VARCHAR(40),
    account_id INT,
	status INT,
	PRIMARY KEY (id),
	FOREIGN KEY (account_id) REFERENCES accounts(id)
);

CREATE TABLE subscription_plans (

 	id INT AUTO_INCREMENT,
 	name VARCHAR (20),
    duration INT(11),
	PRIMARY KEY (id)
);


CREATE TABLE subscriptions (

 	id INT AUTO_INCREMENT,
    device_id INT,
    subscription_plan_id INT,
	status INT,
	PRIMARY KEY (id),
	FOREIGN KEY (subscription_plan_id) REFERENCES subscription_plans(id),
	FOREIGN KEY (device_id) REFERENCES devices(id)
);


CREATE TABLE sensors (
	id INT AUTO_INCREMENT,
	sensor_name VARCHAR(40),
	channels INT(2),
	PRIMARY KEY (id)
	);

CREATE TABLE sensor_data (
	id INT AUTO_INCREMENT,
	sensor_id INT,
	channel_1 DECIMAL(10,2),
	channel_2 DECIMAL(10,2),
	channel_3 DECIMAL(10,2),
	channel_4 DECIMAL(10,2),
	channel_5 DECIMAL(10,2),
	channel_6 DECIMAL(10,2),
	channel_7 DECIMAL(10,2),
	channel_8 DECIMAL(10,2),
	channel_9 DECIMAL(10,2),
	channel_10 DECIMAL(10,2),
	PRIMARY KEY (id),
	FOREIGN KEY (sensor_id) REFERENCES sensors(id)
	);

CREATE TABLE data_packets (

 	id INT AUTO_INCREMENT,
 	account_id VARCHAR(40),
 	device_id INT,
 	sensor_data_id INT,
 	time_stamp timestamp,
 	latitude DECIMAL(11,8),
 	longitude DECIMAL(11,8),
	PRIMARY KEY (id),
	FOREIGN KEY (id) REFERENCES sensor_data(id),
	FOREIGN KEY (device_id) REFERENCES devices(id)
);

ALTER TABLE accounts ADD UNIQUE (account_id);
ALTER TABLE accounts ADD NOTNULL (account_id);
ALTER TABLE accounts MODIFY COLUMN accounts.account_id VARCHAR(40) NOT NULL;
ALTER TABLE customers ADD sign_up_timestamp timestamp;
ALTER TABLE customers ADD UNIQUE (customer_email);


/*
DELETE FROM mytest.instance;
ALTER TABLE mytest.instance AUTO_INCREMENT = 1;

DELETE FROM data_packets
ALTER TABLE data_packets AUTO_INCREMENT = 1
DELETE FROM sensor_data
ALTER TABLE sensor_data AUTO_INCREMENT = 1


*/

/*CREATE UNIQUE INDEX AUTHOR_INDEX
ON tutorials_tbl (tutorial_author)*/