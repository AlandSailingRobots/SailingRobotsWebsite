class WaterDepthHandler {
    constructor(L, mymap, initialZoomLevel, maxZoomLevel) {
        this.L = L;
        this.mymap = mymap;

        this.previous_bounds = undefined;
        this.initialZoomLevel = initialZoomLevel;
        this.maxZoomLevel = maxZoomLevel;
        this.geoCallSucces = false;
        var golden = L.marker([60.1, 19.935]).bindPopup("This is the center point");
        var other = L.marker([60.2, 19.937]).bindPopup("This is the double point");
        this.depth_points = L.layerGroup([golden, other]);
        this.geoJsonWaterLayer = L.geoJSON(undefined);
        this.geoJsonWaterDepth = L.geoJSON(undefined);
        this.localGeoJsonWaterLayer = L.geoJSON(undefined);
        this.calculateWaterDepth = L.marker([60.2, 19.937]);
    }

    getOverlays() {
        return {
            "depth_overlay": this.depth_points,
            "water_overlay": this.geoJsonWaterLayer,
            "local_overlay": this.localGeoJsonWaterLayer,
            "water_depth": this.geoJsonWaterDepth,
            "calculate_water_depth": this.calculateWaterDepth
        };
    }

    getDataForGeoJson(extra) {
        let bounds = this.mymap.getBounds();
        let jsoned = {
            "zoom": this.mymap.getZoom(),
            "box": {
                "ne": bounds.getNorthEast(),
                "sw": bounds.getSouthWest(),
                "nw": bounds.getNorthWest(),
                "se": bounds.getSouthEast()
            },
            "crs": "epsg:4326",
            "extra": extra
        };

        return JSON.stringify(jsoned);
    }


    GetGeoJsonForCurrentBoundingBox() {
        this.geoJsonWaterLayer.addData(this.requestGeoJson("getGeoJson"));
    }

    GetWaterDepthPointsFromServer() {
        var depth = this.requestGeoJson("getWaterDepthPoints", {"limitDepth": 10});
        console.log(depth);
        this.geoJsonWaterDepth.addData(depth);
    }

    CalculateWaterDepthPointsFromServer() {
        var depth = this.requestGeoJson("calculateProcess", {"limitDepth": 10});
    }

    GetWaterDepthAreaFromServer(boat_depth) {
        console.log(boat_depth)
        var depth = this.requestGeoJson("getWaterDepthArea", {"limitDepth": 10, "boatDepth": parseFloat(boat_depth)});
        console.log(depth);
        this.geoJsonWaterDepth.addData(depth);
    }

    getLocalGeoJson() {
        this.localGeoJsonWaterLayer.clearLayers();
        this.localGeoJsonWaterLayer.addData(this.requestGeoJson("getLocalGeoJson"));

    }

    getWaterDepth() {
        this.geoJsonWaterDepth.addData(this.requestGeoJson("getWaterDepthAreas", {"limitDepth": 10}));
    }


    requestGeoJson(jsonUrl, extra) {
        var returnData;
        $.ajax({
            type: "POST",
            url: `http://127.0.0.1:80/${jsonUrl}`,
            async: false,
            timeout: 3000,
            contentType: "application/json; charset=utf-8",
            data: this.getDataForGeoJson(extra),
            success: function (data) {
                returnData = data;
                this.geoCallSucces = true;
            },
            error: function (errorMessage) {
                this.geoCallSucces = false;
            }
        });
        return returnData;
    }

    getMapBoundingBoxAndSendToBeProcessed(mymap, missionUseBoatDepth, missonBoatDepth) {
        this.mymap = mymap;
        if (!mymap.hasLayer(this.depth_points)) {
            return null;
        }
        let currentZoomLevel = mymap.getZoom();
        if (currentZoomLevel < this.initialZoomLevel || currentZoomLevel > this.maxZoomLevel) {
            return null;
        }
        if (!missionUseBoatDepth) {
            return null;
        }

        // GetGeoJsonForCurrentBoundingBox();
        // overlapsArea()
        if (this.previous_bounds === undefined || !this.previous_bounds.contains(mymap.getBounds())) {
            this.getLocalGeoJson();
            if (mymap.hasLayer(this.geoJsonWaterDepth)) {
                this.getWaterDepth();
            }
            this.previous_bounds = mymap.getBounds();
        }

        if (mymap.hasLayer(this.calculateWaterDepth)) {
            this.CalculateWaterDepthPointsFromServer();
        }
        // this.GetWaterDepthAreaFromServer(missonBoatDepth);
        if (mymap.hasLayer(this.geoJsonWaterDepth)) {
            this.GetWaterDepthAreaFromServer(missonBoatDepth);
        }
    }

}