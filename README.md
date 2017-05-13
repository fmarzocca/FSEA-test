# FSEA
Formstack Software Engineer Assignment

Fabio Marzocca
----------------------------------------



The application provides a very basic MVC framework that implements a CRUD functionality over a database of users. Users passwords are encrypted with AES and not shown in the GUI.

A database dump file (fsea.sql) is provided to create and populate the table "fsea" into the "my_app" database.

I have tried to deeply test all the functions and every test case, but I am not providing a PHPUnit code coverage, as I am not yet fully confident with PHPUnit and the time for this test wasn't enough to deepen my knowledge on it. I am confident that in a week or so I could study the testing framework.

As far as the Model is concerned,  UserssService object does not work with the database directly; instead it uses the UsersGateway object which in return issues queries to the database. At the moment, there is no class in the model that represents the User entity, instead I used standard PHP objects automatically created from the database record. This is not very OO but it was quick and the User entity was really so simple (4 fields) not to accept this slight lightness...
