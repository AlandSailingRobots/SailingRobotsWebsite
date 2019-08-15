class WaterDepthHandler {
    constructor(L, mymap, initialZoomLevel, maxZoomLevel) {
        this.L = L;
        this.mymap = mymap;
        this.geoJsonWaterDepth = L.geoJSON(undefined);
        this.previous_bounds = undefined;
        this.initialZoomLevel = initialZoomLevel;
        this.maxZoomLevel = maxZoomLevel;
        this.geoCallSucces = false;
        var golden = L.marker([60.1, 19.935]).bindPopup("This is the center point");
        var other = L.marker([60.2, 19.937]).bindPopup("This is the double point");
        this.depth_points = L.layerGroup([golden, other]);
        this.geoJsonWaterLayer = L.geoJSON(undefined);
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

    overlapsArea(geoJsonWaterLayer, bounds) {
        let overlaps = false;
        geoJsonWaterLayer.eachLayer(function (layer) {
            if (bounds.overlaps(layer.getBounds())) {
                overlaps = true;
            }
        });
        return overlaps;
    }

    checkPoint(point) {
        let contained = false;
        this.geoJsonWaterDepth.eachLayer(function (layer) {
            if (layer.contains(point)) {
                contained = true;
            }
        });
        return contained;
    }

    boundAroundPoint(sizeInMeters, overall_boundary) {
        let listOfPoints = [];
        let currentBound = overall_boundary.getNorthWest().toBounds(sizeInMeters);
        let count = 0;
        let max = 1000;
        let new_latlng = currentBound.getSouthEast();
        let previousBound = currentBound;
        while (overall_boundary.contains(new_latlng)) {
            while (overall_boundary.contains(new_latlng)) {
                if (this.checkPoint(new_latlng) && listOfPoints.includes(new_latlng) === false) {
                    listOfPoints.push(new_latlng);
                    count += 1;
                }
                currentBound = new_latlng.toBounds(sizeInMeters);
                currentBound = this.L.latLng(currentBound.getNorth(), currentBound.getEast()).toBounds(sizeInMeters);
                if (count === max) {
                    break;
                }
                new_latlng = currentBound.getSouthEast();
            }
            if (count === max) {
                break;
            }
            currentBound = this.L.latLng(previousBound.getSouth(), overall_boundary.getWest()).toBounds(sizeInMeters);
            new_latlng = currentBound.getSouthEast();
            previousBound = currentBound;
        }
        // for (let i = 0; i < listOfPoints.length; i++) {
        //     L.marker(listOfPoints[i]).addTo(mymap);
        // }
        return listOfPoints;
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
        this.requestGeoJson("getWaterDepthPoints");
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

    getMapBoundingBoxAndSendToBeProcessed(mymap) {
        this.mymap = mymap;
        if (!mymap.hasLayer(this.depth_points)) {
            return null;
        }
        let currentZoomLevel = mymap.getZoom();
        if (currentZoomLevel < this.initialZoomLevel || currentZoomLevel > this.maxZoomLevel) {
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
            this.GetWaterDepthPointsFromServer();
        }
    }
}