UWReggieWeb
===========

UW Reggie is a simple web service providing course notifications for open classes at the University of Washington
To use the system, create a free account at http://uwreggie.com. To set up a local copy to contribute, read on!

Dev Set Up
==========

Reggie requires the following:
- [ ] Webserver stack (Any server with PHP and MySql)
- [ ] PHP pear mail

- Clone repo
- Copy contents of 'web' to the root of a web server directory
- Edit 'includes/config.php' to use your google accounts
- Copy contents of 'includes' to any directory listed in php.ini's include path
- Import the empty MySQL database from the 'mysql' directory
- Create an acount on your local installation of the website and add alerts
- Change directory to 'backend' and run 'php -f Main.php' to check for open spaces in any classes you have added
- Repeat step 6 as many times as you want to check each class (Main.php does one cycle through the requests)
