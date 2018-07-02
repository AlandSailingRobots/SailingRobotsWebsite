# New version of the SailingRobots Website

The website needs to be more user-friendly in order to be used by biologists.
To do that, the new UI should offer a clear view of measurements/samplings that 
would have been made on previous missions.

## Current Features :
   -  Current Website : https://sailingrobots.ax/aspire/ 
   -  Previous website : http://sailingrobots.com/testdata/live/
   -  Live logs
   -  Low level configuration
   -  Easy way to add waypoints (using LeafletJS and MapboxAPI)
   -  Logs monitoring

## What has been done so far :
   -  New UI, using Gentelella Bootstrap Template (by Colorlib)
   -  Log-in system, need to be 'admin' to configure ASPire
   -  Synchronisation of the server DB with ASPire DB
   -  More user-friendly interface for route planning

## What needs to be done :
   -  Graphs / tables with data
   -  Right Management system (see profile/README.md)

## Database
There are 4 DB used by the website
| DB | Name | Purpose | 
| -- | ---- | ------- |
| Janet DB | ithaax_testdata | Janet DB, also used by the previous wesite |
| ASPire DB | ithaax_aspire_config | ASPire DB |
| Website | ithaax_website_config | handles the registered users |
| Mission | ithaax_mission | handles the list of waypoints and checkpoints as well as the mission |

## Remarks
   -  Check the different sub-folder READMEs to have a better overview of the work
   

