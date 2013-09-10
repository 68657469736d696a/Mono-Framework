Mono-Framework
==============
There are a lot of nice MVC frameworks made in PHP, with all kinds of fancy features like build in javascript libraries and database management scripts. Mono does not have these fancy features, because you as a programmer will probably know best which 3rd party solutions are best for your project. The goal of mono is skipping all the overhead and focussing on the primary tasks. That is what makes mono a lightweight (<100kB) and super fast MVC framework


Installation
==============
Setting up the Mono-framework takes two simple steps and about 3 minutes of your time

First of all you'll need to edit the .htaccess file. On line 3 is the rewriteBase. Change this value to the directory mono is installed. If you run mono from the root, just use /

The second step is to create a configuration file in the /sites/config/ directory. The mono framwork will look for the file which is named after the hostname. So if you're opening a page on example.net mono will open the example.net.php file. This allows you to run multiple sites on a single installation. In the configuration directory is a localhost.php file. Copy this file and rename it to the hostname you are using. The sitePath parameter is the one you will need to change into the directory mono will be running from (same as the rewriteBase).

* Please note that every subdomain needs its own configuration file.

