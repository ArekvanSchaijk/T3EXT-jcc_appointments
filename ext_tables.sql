#
# Table structure for table 'tx_gss_domain_model_keyword'
#
CREATE TABLE tx_jccappointments_domain_model_appointment (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	app_id int(11) DEFAULT '0' NOT NULL,
	app_time int(11) unsigned DEFAULT '0' NOT NULL,
	secret_hash varchar(255) DEFAULT '' NOT NULL,
	sms tinyint(1) unsigned DEFAULT '0' NOT NULL,
	sms_send tinyint(1) unsigned DEFAULT '0' NOT NULL,
	sms_send_date int(11) unsigned DEFAULT '0' NOT NULL,
	mail_send tinyint(1) unsigned DEFAULT '0' NOT NULL,
	closed tinyint(1) unsigned DEFAULT '0' NOT NULL,
	client_id varchar(255) DEFAULT '' NOT NULL,
	client_initials varchar(255) DEFAULT '' NOT NULL,
	client_insertions varchar(255) DEFAULT '' NOT NULL,
	client_last_name varchar(255) DEFAULT '' NOT NULL,
	client_sex varchar(255) DEFAULT '' NOT NULL,
	client_date_of_birth int(11) unsigned DEFAULT '0' NOT NULL,
	client_street varchar(255) DEFAULT '' NOT NULL,
	client_street_number varchar(255) DEFAULT '' NOT NULL,
	client_postal_code varchar(255) DEFAULT '' NOT NULL,
	client_city varchar(255) DEFAULT '' NOT NULL,
	client_phone varchar(255) DEFAULT '' NOT NULL,
	client_mobile_phone varchar(255) DEFAULT '' NOT NULL,
	client_email varchar(255) DEFAULT '' NOT NULL,
	location_name varchar(255) DEFAULT '' NOT NULL,
	location_address varchar(255) DEFAULT '' NOT NULL,
	location_postal_code varchar(255) DEFAULT '' NOT NULL,
	location_phone varchar(255) DEFAULT '' NOT NULL,
	
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_jccappointments_domain_model_sms'
#
CREATE TABLE tx_jccappointments_domain_model_sms (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	recipient_name varchar(255) DEFAULT '' NOT NULL,
	recipient_number varchar(255) DEFAULT '' NOT NULL,
	sender_name varchar(255) DEFAULT '' NOT NULL,
	message text DEFAULT '' NOT NULL,
	app_id int(11) DEFAULT '0' NOT NULL,
	status varchar(255) DEFAULT '' NOT NULL,
	send_date int(11) DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY language (l10n_parent,sys_language_uid)

);
