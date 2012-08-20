Web-based Penetration and Vulnerability Scanner
=======
NB: This is an archive of an old web based management tool for nmap and other security tools.

Abstract
-------
This project will explore and analyse the proposed development of an open-source web-based vulnerability and penetration testing tool.

The system will implement advanced techniques for scanning machines that exist on the Internet using readily available Linux command line applications such as nmap and Nikto. The resulting collected data will be stored in a multi-user online database allowing the site user to view the output of the commands, thus helping to identify weaknesses within their web applications and provide supporting material for fixing the issues.

What makes this project and resulting online application different from any present tool, is that this has been constructed with an aim to be both secure and very user friendly. By creating a tool that is simple to use it is hoped that the users of the system will be educated in the use of the command line tools they are remotely executing.

Project Aims
-------
This project aims to provide access to a series of Linux based command line tools through a web-based user-friendly interface. As well as allowing access to advanced network mapping tools such as nmap , and web service vulnerability scanning using Nikto, the project will also allow various precursor scans using other techniques and products:

1. simple site pinging to obtain operational status of servers
2. host trace route facilities using tracert
3. obtain basic domain owner details using simple whois commands
4. lookup domain name server settings using dig 

Provision will be made for automated monthly security updates, and also a further process to manage Nikto and other open source product releases (see section 3.4 regarding Development Phases).

Objectives
-------
This project comprises three main objectives:

1. Provide a simple command execution queue management system for monitoring the status and output of a series of command line tools.
2. Educate users in the value of simple command line tools to provide extensive remote reconnaissance and vulnerability scans.
3. Embed the previous two objectives into a rich web-based interface, making the tools accessible and easy to use from any Internet enabled location.

Installation
-----------
1. configure a local MySQL and apache vhost
2. download the GIT repo in to the vhost public directory
3. create a database from the SQL file
4. update apache to reflect new "htdocs" directory as root path
5. set the database and filesystem paths in the config.php file
6. set the database and filesystem paths in the CGI scripts
7. setup CRON to execute the queue system every minute




