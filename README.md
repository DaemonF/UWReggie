UWReggieWeb
===========

UW Reggie is a simple web service providing course notifications for open classes at the University of Washington
To use the system, create a free account at http://uwreggie.com. To set up a local copy to contribute, read on!

Dev Set Up
==========

Reggie requires a full webserver stack (Any server with PHP and MySql) and PHP pear mail. I wrote it with a couple of UNIX features in mind like a fifo file, so there is some work to be done on other platforms.

- Clone repo
- Copy contents of 'web' to the root of a web server directory
- Create a MySql DB called 'uwreggiedb' and import 'mysql/uwreggiedb.sql'
- Create a user with full permissions on that database
- Edit 'includes/config.php' to use your gmail, google voice and mysql login credentials.
- Copy contents of 'includes' to any directory listed in php.ini's include path
- Create an acount on your local installation of the website and add alerts
- Create a fifo in 
- Run 'bash backend/reggie' to check for open classes and send out alerts

To Do
=====

- [ ] Improve logging
- [ ] Factor out strings for internationalization
- [ ] More checking optimizations?
