/****************************************************************************************
 *
 * Purpose:
 *      provides a shared library of functions (mostly for db handling). See usage in
 *      page headers and jsfunctions files.
 *
 *
 * Developer Notes:
 *      - still TODO: Combine all dbapi- and dbconnection php files into one of each and
 *          include them in the libs folder for less duplicates.
 *      - Name is not optimal, should probably think of something better.
 *
 ***************************************************************************************/

    //Number of running ajax threads
    var u_errorMessage = "[ERROR: libs/utility.js]: ";
    var u_runningThreads = 0;

    var u_waypointsObj;
    var u_gpsDataObj;
    var u_logsDataObj;
    var u_routeDataObj;
    var u_idDataObj;

    var u_latestId = -1;
    var u_currentId = -1;



    function u_getIdData()
    {
        if (u_routeDataObj == null)
        {
            console.log(u_errorMessage + "IdData are null");
        }
        return u_idDataObj;
    }

    function u_getRouteData()
    {
        if (u_routeDataObj == null)
        {
            console.log(u_errorMessage + "Routes are null");
        }
        return u_routeDataObj;
    }

    function u_getLogData()
    {
        if (u_logsDataObj == null)
        {
            console.log(u_errorMessage + "Logs are null");
        }
        return u_logsDataObj;
    }

    function u_getWaypoints()
    {
        if (u_waypointsObj == null)
        {
            console.log(u_errorMessage + "Waypoints are null");
        }
        return u_waypointsObj;
    }

    function u_getGpsData()
    {
        if (u_gpsDataObj == null)
        {
            console.log(u_errorMessage + "Gpsdata is null");
        }
        return u_gpsDataObj;
    }

    //Called when ajax threads finish. Implement utilityCallback() in external scripts to subscribe.
    function u_ajaxFinished(message)
    {
        console.log(message);
        u_runningThreads--;
        //Only call when ALL threads are done
        if (u_runningThreads < 1)
        {
            //name of callback
            utilityCallback();
            u_runningThreads = 0;
        }
    }

    //Separate callback for repeated log probes
    function u_newLogData(dataObj)
    {
        //name of callback
        utilityNewLogData(dataObj);

    }

    function u_repeatLogProbe(milliseconds)
    {
        console.log("DEBUG: probing for new data every " + milliseconds + "ms.");
        setInterval('u_checkIfNewLogData()', milliseconds);
    }

    function u_checkIfNewLogData() 
    {
        $.ajax({
            url: '../live/dbapi.php',
            data: {'action': "idcheck"},
            success: function(data) {
                var obj = jQuery.parseJSON(data);
                latestId = parseInt(obj.id_system);

                if(!isNaN(latestId) && latestId !== currentId) {
                    currentId = latestId;
                    u_getLatestData("getGpsData");
                    u_getLatestData("getCourseCalculationData");
                    u_getLatestData("getWindSensorData");
                    u_getLatestData("getSystemData");
                    u_getLatestData("getCompassData");
                }

            },
            error: function(errorThrown) {
                console.log(u_errorMessage + errorThrown);
            }
        });

    }

    //Check, run individual retrievals, implement callback with object argument
    function u_getLatestData(table) 
    {
        console.log("DEBUG: new data from " + table);

        $.ajax({
            url: '../live/dbapi.php',
            data: {'action': table},
            success: function(data) {
                u_newLogData(jQuery.parseJSON(data));
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
    }


    //Pushes a json string of waypoints to the server. Does not currently implement callback (not needed..?)
    function u_pushWaypoints(waypointString)
    {
        $.ajax({
			type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
			url         : '../config/insertWaypoint.php', // the url where we want to POST
			data        : waypointString, // our data object
			dataType    : 'text', // what type of data do we expect back from the server
			   error: function(xhr, ajaxOptions, thrownError){
				   console.log(u_errorMessage + errorThrown);
			   }
		})
			.done(function(data) {

			});
    }

    //Creates json array of all waypoints
    function u_initWaypoints() 
    {
        u_runningThreads++;
        $.ajax({
            url: '../live/dbapi.php',
            data: {'action': "getWaypoints"},
            success: function(data) {
                u_waypointsObj = jQuery.parseJSON(data);
            },
            error: function(errorThrown) {
                console.log(u_errorMessage + errorThrown);
            },
            complete: function(){
                u_ajaxFinished("u_initWaypoints: Thread Finished");
            }
        });
    }

    function u_initIdData()
    {
        u_runningThreads++;
        $.ajax({
            url: '../live/dbapi.php',
            data: {'action': "idcheck"},
            success: function(data) {
                u_idDataObj = jQuery.parseJSON(data);
            },
            error: function(errorThrown) {
                console.log(u_errorMessage + errorThrown);
            },
            complete: function(){
                u_ajaxFinished("u_initIdData: Thread Finished");
            }
        });
    }

    function u_initRouteData()
    {
        u_runningThreads++;
        $.ajax({
            url: '../log/dbapi.php',
            data: {
                'action': "getAllRoutes"
            },
            success: function(data) {
                console.log("Trying to parse route data");
                //console.log(data);
                u_routeDataObj = jQuery.parseJSON(data);
            },
            error: function(errorThrown) {
                console.log(u_errorMessage + errorThrown);
            },
            complete: function(){
                u_ajaxFinished("u_initRouteData: Thread Finished");
            }
        });
    }

    //Gets all available system logs
    function u_initLogData()
    {
        u_runningThreads++;
        $.ajax({
            url: '../log/dbapi.php',
            data: {'action': "getAll"},
            success: function(data) {
                data = data.replace('[',''); //Special formatting needed in log section, see log/js/jsfunctions
                data = data.replace(']','');
                u_logsDataObj = jQuery.parseJSON(data);
            },
            error: function(errorThrown) {
                console.log(u_errorMessage + errorThrown);
            },
            complete: function(){
                u_ajaxFinished("u_initLogData: Thread Finished");
            }
        });
    }

    function u_initGpsData()
    {
        u_runningThreads++;
        $.ajax({
            url: '../live/dbapi.php',
            data: {'action': "getGpsData"},
            type: 'POST',
            success: function(data) {
                u_gpsDataObj = jQuery.parseJSON(data);
            },
            error: function(errorThrown) {
                console.log(u_errorMessage + errorThrown);
            },
            complete: function(){
                u_ajaxFinished("u_initGpsData: Thread Finished");
            }
        });
    }
