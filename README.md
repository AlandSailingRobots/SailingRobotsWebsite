# New version of the SailingRobots Website

The website needs to be more user-friendly in order to be used by biologists.
To do that, the new UI should offer a clear view of measurements/samplings that 
would have been made on previous missions.

## Installation:
* [For Mac OS](INSTALL_MAC_OS.md)
* [Arch Linux](https://github.com/AlandSailingRobots/SailingRobotsDocs/blob/master/Website%20on%20localhost%20guide.md) :exclamation: __most likely outdated__

## Current Features :
   -  Current Website : https://sailingrobots.ax/aspire/ 
   -  Previous website : http://sailingrobots.com/testdata/live/
   -  Live logs
   -  Low level configuration
   -  Easy way to add waypoints (using [LeafletJS](http://leafletjs.com) and [MapboxAPI](https://docs.mapbox.com/api/))
   -  Logs monitoring
   -  Depth recognition from Aerial images from finland (using an [new system](https://github.com/AlandSailingRobots/AerialImagesToWaterDepth))

## What has been done so far :
   - [x] New UI, using [Gentelella Bootstrap Template](https://github.com/ColorlibHQ/gentelella) (by Colorlib)
   - [x] Log-in system, need to be 'admin' to configure ASPire
   - [x] Synchronisation of the server DB with ASPire DB
   - [x] More user-friendly interface for route planning


## What needs to be done :
   - [ ] Graphs / tables with data
   - [ ] [Right Management system](pages/profile/README.md#Objectives)

## Database
There are 4 databases used by the website

| DB | Name | Purpose | 
| --- | ---- | ------- |
| Janet DB| ithaax_testdata | Janet DB, also used by the previous website |
| ASPire DB| ithaax_aspire_config | ASPire DB |
| Website | ithaax_website_config | handles the registered users |
| Mission | ithaax_mission | handles the list of waypoints and checkpoints as well as the mission |

## Remarks
   -  Check the different sub-folder README's to have a better overview of the work
   

