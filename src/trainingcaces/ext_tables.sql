#
# Table structure for table 'fe_users'
#
CREATE TABLE fe_users (
	activated_on int(11) unsigned DEFAULT '0' NOT NULL,
	pseudonym varchar(50) DEFAULT '',
	open_password varchar(50) DEFAULT '',
	gender int(11) unsigned DEFAULT '0' NOT NULL,
	date_of_birth int(11) DEFAULT '0' NOT NULL,
	language char(2) DEFAULT '' NOT NULL,
	zone varchar(45) DEFAULT '' NOT NULL,
	static_info_country char(3) DEFAULT '' NOT NULL,
	timezone float DEFAULT '0' NOT NULL,
	daylight tinyint(4) unsigned DEFAULT '0' NOT NULL,
	mobilephone varchar(20) DEFAULT '' NOT NULL,
	gtc tinyint(4) unsigned DEFAULT '0' NOT NULL,
	privacy tinyint(4) unsigned DEFAULT '0' NOT NULL,
	status int(11) unsigned DEFAULT '0' NOT NULL,
	by_invitation tinyint(4) unsigned DEFAULT '0' NOT NULL,
	comments text,
	exam int(11) unsigned DEFAULT '0' NOT NULL,
	photo int(11) unsigned NOT NULL default '0',
);


#
# Table structure for table 'fe_groups'
#
CREATE TABLE fe_groups (
	felogin_redirectPid tinytext
);


#
# Table structure for table 'tx_trainingcaces_domain_model_exam'
#
CREATE TABLE tx_trainingcaces_domain_model_exam (

    number varchar(255) DEFAULT '' NOT NULL,
	session_date date DEFAULT NULL,
    validate_date date DEFAULT NULL,
	theory_test_date date DEFAULT NULL,
	theory_result varchar(255) DEFAULT '' NOT NULL,
	theory_result_file int(11) unsigned NOT NULL default '0',
	practice_test_date date DEFAULT NULL,
	practice_result varchar(255) DEFAULT '' NOT NULL,
	practice_result_file int(11) unsigned NOT NULL default '0',
	selection varchar(255) DEFAULT '' NOT NULL,
	enterprice_client int(11) unsigned DEFAULT '0',
	place int(11) unsigned DEFAULT '0',
	candidate int(11) unsigned DEFAULT '0',
	theory_trainer int(11) unsigned DEFAULT '0',
	practice_trainer int(11) unsigned DEFAULT '0',
	type int(11) unsigned DEFAULT '0',
	category int(11) unsigned DEFAULT '0',
	path_segment varchar(2048),
	theory_answers text,
	practice_answers text,
	theory_status varchar(255) DEFAULT '' NOT NULL,
	practice_status varchar(255) DEFAULT '' NOT NULL,
	theory_is_sent varchar(255) DEFAULT '' NOT NULL,
	practice_is_sent varchar(255) DEFAULT '' NOT NULL,
	is_choice int(11) unsigned DEFAULT '0',
    is_option smallint(5) unsigned DEFAULT '0' NOT NULL,
    is_practice smallint(5) unsigned DEFAULT '0' NOT NULL,
    next_exam varchar(255) DEFAULT '' NOT NULL,
    sub_cat int(11) unsigned DEFAULT '0',

    #is_practice int(11) unsigned DEFAULT '0',
    #is_option int(11) unsigned DEFAULT '0',
    #sub_cat varchar(255) DEFAULT '' NOT NULL,
	#is_choice varchar(255) DEFAULT '' NOT NULL,
    #is_practice varchar(255) DEFAULT '' NOT NULL,
	#is_option varchar(255) DEFAULT '' NOT NULL,

	note text,
	company varchar(255) DEFAULT '' NOT NULL,
);

#
# Table structure for table 'tx_trainingcaces_domain_model_enterpriseclient'
#
CREATE TABLE tx_trainingcaces_domain_model_enterpriseclient (

	name varchar(255) DEFAULT '' NOT NULL

);

#
# Table structure for table 'tx_trainingcaces_domain_model_place'
#
CREATE TABLE tx_trainingcaces_domain_model_place (

	name varchar(255) DEFAULT '' NOT NULL

);

#
# Table structure for table 'tx_trainingcaces_domain_model_type'
#
CREATE TABLE tx_trainingcaces_domain_model_type (

	name varchar(255) DEFAULT '' NOT NULL,
	category int(11) unsigned DEFAULT '0' NOT NULL,
    description text,

);

#
# Table structure for table 'tx_trainingcaces_domain_model_category'
#
CREATE TABLE tx_trainingcaces_domain_model_category (

	name varchar(255) DEFAULT '' NOT NULL,
    short_name varchar(255) DEFAULT '' NOT NULL,
	section varchar(255) DEFAULT '' NOT NULL,
	type int(11) unsigned DEFAULT '0',
    description text,
    unique_name varchar(255) DEFAULT '' NOT NULL,

);

#
# Table structure for table 'tx_trainingcaces_domain_model_subcategory'
#
CREATE TABLE tx_trainingcaces_domain_model_subcategory (

    name varchar(255) DEFAULT '' NOT NULL,
    short_name varchar(255) DEFAULT '' NOT NULL,
    unique_name varchar(255) DEFAULT '' NOT NULL

);
