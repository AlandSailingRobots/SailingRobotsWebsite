# Configuration of the robot

This page is used to read the content of the different tables (the name is spe-
cified by the webmaster).

Every entries have an unique ID which is used to recover which field of the DB
needs to be updated. To do that, the name of the table is concatenated to the
name of the column. That way, the button 'Save' only sends one form which is
displayed in different tables.

That has been done in order to avoid using AJAX queries on every table because, 
I as a beginner developper in web development, I was not comfortable enough with
Javascript at the moment I wrote this page.


# Update of the DB
The body file must be adapted and the file php/getConfigData.php as well on 
each update made on the structure of the DB.
