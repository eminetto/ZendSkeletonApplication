
# TODO
- Translate comments to english
- Add Form example in Skel module
- Review this README



ZendSkeletonApplication
=======================

Introduction
------------
This is a simple, skeleton application using the ZF2 MVC layer and module
systems. This application is meant to be used as a starting place for those
looking to get their feet wet with ZF2.


Installation
------------

Using Composer (recommended)
----------------------------
The recommended way to get a working copy of this project is to clone the repository
and use composer to install dependencies:

    cd my/project/dir
    git clone git://github.com/zendframework/ZendSkeletonApplication.git
    cd ZendSkeletonApplication
    php composer.phar install

Using Git submodules
--------------------
Alternatively, you can install using native git submodules:

    git clone git://github.com/zendframework/ZendSkeletonApplication.git --recursive

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

