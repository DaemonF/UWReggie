UWReggieWeb
===========

UW Reggie is a simple web service providing course notifications for open classes at the University of Washington
To use the system, create a free account at http://uwreggie.com. To set up a local copy to contribute, read on!

Dev Set Up
==========

Reggie requires a full webserver stack (Any server with PHP and MySql) and PHP pear mail. I wrote it with a couple of UNIX features in mind like a fifo file, so there is some work to be done on other platforms. Some changes to php.ini are required. See below.

- Clone repo
- Create a MySql DB called 'uwreggiedb' and import 'mysql/uwreggiedb.sql'
- Create a user with full permissions on that database
- Edit 'includes/config.php' to use your gmail, google voice and mysql login credentials.
- Link the contents of 'web/' into the root of a web server directory and give it the right permisions
- Copy contents of 'includes/' to any directory listed in php.ini's include path
- Browse to your site and create an acount to add alerts to the DB
- Create a fifo called 'sendQueue.fifo' in 'backend/'
- Run 'bash backend/reggie' to check for open classes and send out alerts

Changes to php.ini
==================

- set 'allow-url-fopen' to '1'
- add the 'includes/' directory to the include path

To Do
=====

- [ ] Improve logging
- [ ] Factor out strings for internationalization
- [ ] More checking optimizations?
