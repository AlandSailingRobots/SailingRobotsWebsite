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
        this.initialZoomLevel = initialZoomLevel;
        this.maxZoomLevel = maxZoomLevel;
        this.geoJsonWaterDepth = L.geoJSON(undefined,
            {style: this.polyStyle, onEachFeature: this.onEachFeature}
        );
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
            "water_depth": this.geoJsonWaterDepth,
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

    CalculateWaterDepthPointsFromServer() {
        this.requestGeoJson("calculateProcess", {"limitDepth": 10}, true);
    }

    GetWaterDepthAreaFromServer(boat_depth) {
        var depth = this.requestGeoJson("getWaterDepthArea", {"limitDepth": 10, "boatDepth": parseFloat(boat_depth)});
        this.geoJsonWaterDepth.clearLayers();
        this.geoJsonWaterDepth.addData(depth);
    }


    requestGeoJson(jsonUrl, extra, async = false) {
        var returnData;
        $.ajax({
            type: "POST",
            url: `http://127.0.0.1:80/${jsonUrl}`,
            async: async,
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
        let currentZoomLevel = mymap.getZoom();
        if (currentZoomLevel < this.initialZoomLevel || currentZoomLevel > this.maxZoomLevel) {
            if (this.mymap.hasLayer(this.geoJsonWaterDepth)) {
                this.mymap.removeLayer(this.geoJsonWaterDepth);
            }
            return null;
        }

        if (missionUseBoatDepth) {
            if (!this.mymap.hasLayer(this.geoJsonWaterDepth)) {
                this.mymap.addLayer(this.geoJsonWaterDepth);
            }
            this.GetWaterDepthAreaFromServer(missonBoatDepth);
            this.CalculateWaterDepthPointsFromServer();
            return "Succes";
        } else {
            if (this.mymap.hasLayer(this.geoJsonWaterDepth)) {
                this.mymap.removeLayer(this.geoJsonWaterDepth);
            }
        }
    }
}