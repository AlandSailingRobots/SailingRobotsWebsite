class Point {
    constructor(id, id_mission, rankInMission, name, lat, lon, decl, radius, stay_time, harvested) {
        this.id = id; // Different to the primary key of the DB when it comes to add new points.
        this.id_mission = parseInt(id_mission);
        this.rankInMission = parseInt(rankInMission);
        this.name = name;
        this.latitude = parseFloat(lat);
        this.longitude = parseFloat(lon);
        this.declination = parseFloat(decl);
        this.radius = parseInt(radius);
        this.stay_time = parseInt(stay_time);
        // If no argument is provided, then this.harvested = false, otherwise, the provided argument
        this.harvested = (harvested === undefined ? 0 : harvested);
    }

    print(type) {
        return `The ${type} ${this.rankInMission} - ${this.name} is located at the coordinates (${this.latitude}, ${this.longitude})
                Radius: ${this.radius} (m) | Declination: ${this.declination} | Stay time: ${this.stay_time} (sec)`;
    }

    getDBFormat(isCheckpoint) {
        return {
            "id": this.id,
            "id_mission": this.id_mission,
            "rankInMission": this.rankInMission,
            "isCheckpoint": isCheckpoint,
            "name": this.name,
            "latitude": this.latitude,
            "longitude": this.longitude,
            "declination": this.declination,
            "radius": this.radius,
            "stay_time": this.stay_time,
            "harvested": this.harvested
        };
    }

    click(type) {
        return type + ": " + this.rankInMission + " - " + this.name + "<br /> \n" +
            "Position: " + this.latitude + ", " + this.longitude + "<br /> \n" +
            "Radius: " + this.radius + " (m) | Stay_time: " + this.stay_time + " (sec) <br /> \n" +
            "<br /> \n" +
            "<div> \n" +
            "<button name='deletePointButton' class='btn btn-danger deletePoint'  id='rankInMission:" + this.rankInMission + "|id:" + this.id + "' >Delete Point</button> \n" +
            "<button name='editPointButton'   class='btn btn-info   editPointButton " + type + "' id='rankInMission:" + this.rankInMission + "|id:" + this.id + "' >Edit Point</button> \n" +
            "</div>";
    }

    equals(other) {
        return (
            this.name === other.name &&
            this.latitude === other.latitude &&
            this.longitude === other.longitude &&
            this.radius === other.radius &&
            this.stay_time === other.stay_time &&
            this.declination === other.declination);
    }
}


class WayPoint extends Point {
    constructor(id, id_mission, rankInMission, name, lat, lon, decl, radius, stay_time, harvested) {
        super(id, id_mission, rankInMission, name, lat, lon, decl, radius, stay_time, harvested);
        this.text = "waypoint";
        this.classText = "isWaypoint";
        this.defaultRadius = 50;
        this.defaultStay_time = 1;
        this.icon_color = blueIcon;
    }

    print() {
        return super.print(this.text);
    }

    click() {
        return super.click("Waypoint");
    }

    getDBFormat() {
        return super.getDBFormat(0);
    }
}

class CheckPoint extends Point {
    constructor(id, id_mission, rankInMission, name, lat, lon, decl, radius, stay_time, harvested) {
        super(id, id_mission, rankInMission, name, lat, lon, decl, radius, stay_time, harvested);

        this.text = "checkpoint";
        this.classText = "isCheckpoint";
        this.defaultRadius = 15;
        this.defaultStay_time = 5;
        this.icon_color = greenIcon;
    }

    print() {
        return super.print(this.text);
    }

    click() {
        return super.click("Checkpoint");
    }

    getDBFormat() {
        return super.getDBFormat(1);
    }
}