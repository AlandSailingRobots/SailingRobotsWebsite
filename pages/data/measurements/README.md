# Representation of logged measurement data

This page will display logged measurements data and coordinates from logged missions.

#How it works
It will write data from the dataLogs_marine_sensors DB and longitude and latitude coordinates from dataLogs_gps and then shows a table sorted by the ID from both tables.

The whole table from marine-sensor is read into the page and the longitude and latitude data from dataLogs-gps is hardcoded using right join.

#Libraries
Currently using PhpSpreadsheet.
https://github.com/PHPOffice/PhpSpreadsheet/
Installed with Composer in /pages/data/measurements/vendor
