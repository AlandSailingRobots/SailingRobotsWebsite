class WaterDepthHandler {
    polyStyle(feature) {
        return {
            fillColor: "#ff0000",
            color: "rgba(0,0,0,0.98)",
            weight: 5,
            opacity: 0.65
        };
    }

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
        this.geoJsonWaterDepth = L.geoJSON(undefined,
            {style: this.polyStyle, onEachFeature: this.onEachFeature}
        );
        this.localGeoJsonWaterLayer = L.geoJSON(undefined);
        this.calculateWaterDepth = L.marker([60.2, 19.937]);
    }

    onEachFeature(feature, layer) {
        var total_size = feature.properties.total_area;
        var area_size = feature.properties.area_size;
        var percentage = feature.properties.area_size / feature.properties.total_area * 100;
        layer.bindPopup(`This non-sailable area is ${area_size.toFixed(2)} square kilometres 
        of a total of ${total_size.toFixed(2)} square kilometres
        which is ${percentage.toFixed(2)}%`);
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
        this.geoJsonWaterDepth.addData(depth);
    }

    CalculateWaterDepthPointsFromServer() {
        var depth = this.requestGeoJson("calculateProcess", {"limitDepth": 10});
    }

    GetWaterDepthAreaFromServer(boat_depth) {
        var depth = this.requestGeoJson("getWaterDepthArea", {"limitDepth": 10, "boatDepth": parseFloat(boat_depth)});
        this.geoJsonWaterDepth.clearLayers();
        this.geoJsonWaterDepth.addData(depth);
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
            success: function (data, textStatus, request) {
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

        if (this.previous_bounds === undefined || !this.previous_bounds.contains(mymap.getBounds())) {
            this.previous_bounds = mymap.getBounds();
        }

        if (mymap.hasLayer(this.calculateWaterDepth)) {
            this.CalculateWaterDepthPointsFromServer();
        }
        if (mymap.hasLayer(this.geoJsonWaterDepth)) {
            this.GetWaterDepthAreaFromServer(missonBoatDepth);
        }

    }

    onAdd(map) {
        this._div = this.L.DomUtil.create('div', 'info'); // create a div with a class "info"
        this.update();
        return this._div;
    }

// method that we will use to update the control based on feature properties passed
    updateInfo(props) {
        this._div.innerHTML = '<h4>US Population Density</h4>' + (props ?
            '<b>' + props.area_size + '</b><br />' + props.total_area + ' people / mi<sup>2</sup>'
            : 'Hover over a state');
    }

}