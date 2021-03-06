# FSEA
Fast Software Engineer Assignment

Fabio Marzocca
----------------------------------------


Description

The application provides a very basic MVC framework that implements a CRUD functionality over a database of users. Users passwords are encrypted with AES and not shown in the GUI.

A database dump file (fsea.sql) is provided to create and populate the table `fsea` into the `my_app` database.

As far as the Model is concerned,  UsersService object does not work with the database directly; instead it uses the UsersGateway object which in return issues queries to the database. 

Standard **PDO** extension has been used for accessing database, as well as **Namespacing** to encapsulate the Model and the Controller. A basic `composer.json` file is provided too.

At the moment, there is no class in the model that represents the User entity, instead I used standard PHP objects automatically created from the database record. This is not very OO but it was quick and the User entity was really so simple (4 fields) not to accept this slight lightness...


------------------------------

Use:

- Run `vagrant up` from the application root folder;
- Access vagrant by `ssh vagrant@textbox.dev`
- Change directory to `/vagrant`
- Create and populate the database table from the file `fsea.sql`
- Access application pointing the browser to `www.textbox.dev`

