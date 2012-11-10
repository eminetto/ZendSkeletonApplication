ZendSkeletonApplication
=======================

Introduction
------------
This is a simple, skeleton application using the ZF2 MVC layer and module
systems, based in the [http://github.com/zendframework/ZendSkeletonApplication](http://github.com/zendframework/ZendSkeletonApplication)

Features added:

- Core module with 
    - TableGateway implementation
    - PHPUnit tests
    - Entity class
    - Service class
    - AdapterServiceFactory using module-based db configuration

- Skel module

Installation
------------

Using Composer (recommended)
----------------------------
The recommended way to get a working copy of this project is to clone the repository
and use composer to install dependencies:

    cd my/project/dir
    git clone git://github.com/eminetto/ZendSkeletonApplication.git
    cd ZendSkeletonApplication
    php composer.phar install


Virtual Host
------------

	<VirtualHost *:80>
    	ServerName zf2napratica.dev
	    DocumentRoot /vagrant/zf2napratica/public
    	SetEnv APPLICATION_ENV "development"
	    SetEnv PROJECT_ROOT "/vagrant/zf2napratica" 
   		<Directory /vagrant/zf2napratica/public>
        	DirectoryIndex index.php
        	AllowOverride All
        	Order allow,deny
        	Allow from all
    	</Directory>
	</VirtualHost>

