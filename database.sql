#
# Structure for the `adodb_logsql` table : 
#

DROP TABLE IF EXISTS `adodb_logsql`;

CREATE TABLE `adodb_logsql` (
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `sql0` varchar(250) NOT NULL default '',
  `sql1` text NOT NULL,
  `params` text NOT NULL,
  `tracer` text NOT NULL,
  `timer` decimal(16,6) NOT NULL default '0.000000'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Structure for the `command_groups` table : 
#

DROP TABLE IF EXISTS `command_groups`;

CREATE TABLE `command_groups` (
  `group_id` int(11) NOT NULL auto_increment,
  `group_name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Structure for the `command_list` table : 
#

DROP TABLE IF EXISTS `command_list`;

CREATE TABLE `command_list` (
  `command_id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `command` varchar(255) NOT NULL default '',
  `command_desc` varchar(255) NOT NULL default '',
  `credit_cost` int(11) NOT NULL default '0',
  PRIMARY KEY  (`command_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Structure for the `queue` table : 
#

DROP TABLE IF EXISTS `queue`;

CREATE TABLE `queue` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `command_id` int(11) NOT NULL default '0',
  `command` varchar(255) NOT NULL default '',
  `status` tinyint(3) unsigned NOT NULL default '1',
  `recurring_cnt` bigint(20) unsigned NOT NULL default '0',
  `timestamp_added` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_init` timestamp NOT NULL default '0000-00-00 00:00:00',
  `timestamp_done` timestamp NOT NULL default '0000-00-00 00:00:00',
  `client_id` bigint(20) default NULL,
  `hour` char(2) NOT NULL default '',
  `min` char(2) NOT NULL default '',
  `day` char(2) NOT NULL default '',
  `month` char(2) NOT NULL default '',
  `year` char(2) NOT NULL default '',
  `host_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `command_id` (`command_id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Structure for the `queue_output` table : 
#

DROP TABLE IF EXISTS `queue_output`;

CREATE TABLE `queue_output` (
  `output_id` bigint(20) unsigned NOT NULL auto_increment,
  `queue_id` bigint(20) unsigned default NULL,
  `output` longtext,
  `time_stampt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`output_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Structure for the `session_log` table : 
#

DROP TABLE IF EXISTS `session_log`;

CREATE TABLE `session_log` (
  `username` varchar(50) default '',
  `time` varchar(14) default '',
  `session_id` varchar(200) NOT NULL default '0',
  `guest` tinyint(4) default '0',
  `userid` int(11) default '0',
  `usertype` varchar(50) default '',
  `gid` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`session_id`),
  KEY `whosonline` (`guest`,`usertype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Structure for the `statuses` table : 
#

DROP TABLE IF EXISTS `statuses`;

CREATE TABLE `statuses` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `status` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Data for the `statuses` table  (LIMIT 0,500)
#

INSERT INTO `statuses` (`id`, `status`) VALUES 
  (1,'pending'),
  (2,'active'),
  (3,'completed'),
  (4,'recurring'),
  (5,'cancelled');

COMMIT;

#
# Data for the `command_list` table  (LIMIT 0,500)
#

INSERT INTO `command_list` (`command_id`, `group_id`, `command`, `command_desc`, `credit_cost`) VALUES 
  (1,1,'nmap -sS -p 20-2550 %%host%%','Scan ports 20-2550 - Stealth',20),
  (2,3,'ping %%host%% -c 2','Ping twice',5),
  (3,4,'dig %%host%% +trace','Check DNS and trace',10),
  (4,3,'ping %%host%% -c 10','Ping ten times',10),
  (5,1,'nmap -sX -p22,53,110,143,4564 %%host%%','SYN Scan (specific ports)',20),
  (6,1,'nmap -O %%host%%','Determine OS',20),
  (7,5,'traceroute %%host%%','Trace the host',10),
  (8,4,'dig -x %%host%%','Reverse DNS search',10),
  (9,4,'nslookup %%host%%','Queries a name server for a host or domain lookup',5),
  (10,6,'lynx -dump %%host%%','Output a websites content (text only)',30),
  (11,1,'nmap -sU %%host%%','UDP Scan',20),
  (12,1,'nmap -sF %%host%%','FIN Scan',20),
  (13,1,'nmap -sA %%host%%','ACK Scan',20),
  (14,1,'nmap -sN %%host%%','Null Scan',20),
  (15,1,'nmap -sX %%host%%','SYN Scan',20),
  (16,1,'nmap -sP %%host%%','Ping Scan',20),
  (17,1,'nmap -p20-150 -sV %%host%%','Service version scanning',20),
  (18,2,'nikto -h %%host%%','Basic port 80 Scan',30),
  (19,2,'nikto -h %%host%% -p 443 -s -g','Forced SSL Scan on port 443',30),
  (20,2,'nikto -h %%host%% -e 1','Scan + random URI encoding',30),
  (21,2,'nikto -h %%host%% -e 2','Scan + add directory self-reference',30),
  (22,4,'whois -h whois.nic.uk %%host%%','UK domain whois lookup',15),
  (23,4,'whois -h rs.internic.net %%host%%','US domain whois lookup',15),
  (24,4,'dig %%host%% NS +short','Obtain domain NS records',5);

COMMIT;

#
# Data for the `command_groups` table  (LIMIT 0,500)
#

INSERT INTO `command_groups` (`group_id`, `group_name`) VALUES 
  (1,'Nmap'),
  (2,'Nikto'),
  (3,'Ping'),
  (4,'Domain'),
  (5,'Trace Route'),
  (6,'Browser');

COMMIT;
