<?php
Class Measurements {

    public function __construct() {

    }

    public function prepare() {

        if ($this->boatName == "janet")
        {
            $database_name = $GLOBALS['database_name_testdata'];
        }
        elseif ($this->boatName == "aspire")
        {
            $database_name = $GLOBALS['database_ASPire'];
        }

        #SETUP
        $this->host     = $GLOBALS['hostname'];
        $this->db       = $database_name;
        $this->usr      = $GLOBALS['username'];
        $this->pwd      = $GLOBALS['password'];
        $this->charset  = 'utf8mb4';


        $this->opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";

        $this->fetch_table = ' (SELECT * FROM ithaax_ASPire_config.dataLogs_system) AS mission_dataLogs ';
        $this->fetch_table .='JOIN ithaax_ASPire_config.dataLogs_marine_sensors ';
        $this->fetch_table .='ON mission_dataLogs.marine_sensors_id = dataLogs_marine_sensors.id ';
        $this->fetch_table .='JOIN ithaax_ASPire_config.dataLogs_gps ';
        $this->fetch_table .='ON mission_dataLogs.gps_id = dataLogs_gps.id ';
        $this->fetch_table .='JOIN ithaax_ASPire_config.currentMission ';
        $this->fetch_table .='ON mission_dataLogs.current_mission_id = currentMission.id ';
        $this->fetch_table .='JOIN ithaax_mission.mission ';
        $this->fetch_table .='ON ithaax_ASPire_config.currentMission.id_mission = mission.id ';
    }

    # Counts nr of pages
    function getPages($table)
    {
        $pdo = new PDO($this->dsn, $this->usr, $this->pwd, $this->opt);

        $total = $pdo->query("SELECT COUNT(*) as rows FROM $table ;") ->fetch(PDO::FETCH_OBJ);
        $perpage = $this->limit;
        $posts   = $total->rows;
        $pages   = ceil($posts / $perpage);

        return $pages;
    }

    # Writes the colum name from DB
    public function getColumnNames($sqlResult) {
        $someHTMLString = '';

        if (!empty($sqlResult)) {
            $firstRow = $sqlResult[0];

            foreach (array_keys($firstRow) as $keyName) {
                $someHTMLString .= '<th>'.$keyName.'</th>';
            }
        }
        return $someHTMLString;
    }

    # Writes the colum data
    public function getColumnData($sqlResult) {
        $someHTMLString = '';

        foreach ($sqlResult as $key => $dataArray) {
            $someHTMLString .= '<tr>';

            foreach ($dataArray as $sensorValue) {
                $someHTMLString .= '<td>'.$sensorValue.'</td>';
            }

            $someHTMLString .= '</tr>';
        }
        return $someHTMLString;
    }

    # building the SQL query
    public function getSensorLogData($offset, $limit) {
        $pdo = new PDO($this->dsn, $this->usr, $this->pwd, $this->opt);
        $pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

        $query = '';
        $query .= 'SELECT dataLogs_gps.time AS timestamp, ';
        $query .= 'ithaax_mission.mission.name AS mission_name, ithaax_mission.mission.id AS mission_id, ';
        $query .= 'dataLogs_marine_sensors.*, ';
        $query .= 'dataLogs_gps.latitude, dataLogs_gps.longitude ';
        $query .= 'FROM ';
        $query .= $this->fetch_table;
        $query .= 'LIMIT :offset, :limit;';


        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':offset', $offset,PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit,PDO::PARAM_INT);

        $stmt->execute();
        $sqlResult = $stmt->fetchAll();

        return $sqlResult;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    # Generate table data as html for template
    public function __toString() {
        $someHTMLString = null;
        $sqlResult = $this->getSensorLogData($this->offset, $this->limit);
        $someHTMLString .= $this->getColumnNames($sqlResult);
        $someHTMLString .= $this->getColumnData($sqlResult);

        return $someHTMLString;
    }
}

Class Pager {

    public function __construct($numPages) {
        $this->pages = $numPages;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }


    public function __toString() {
        $firstPage = 1;
        $midRange = 5;
        $lastPage = $this->pages;

        $pagerLinks = null;
        $pagerLinks .= '<ul class="pagination">';

        #check previous
        if($this->currentPage == 1) {
            $pagerLinks .= '<li class="disabled"><a href="#"><<</a></li>';
            $pagerLinks .= '<li class="disabled"><a href="#"><</a></li>';
        } else {
            $prevPage = $this->currentPage -1;
            $pagerLinks .= '<li><a href="index.php?page='.$firstPage.'"><<</a></li>';
            $pagerLinks .= '<li><a href="index.php?page='.$prevPage.'"><</a></li>';
        }

        $startRange;
        $stopRange;
        if($this->currentPage < $midRange) {
            $startRange = 1;
            $stopRange = 10;
        }
        if($this->currentPage >= $midRange) {
            $startRange = $this->currentPage - $midRange+1;
            $stopRange = $this->pages;
        }
        if($this->currentPage > ($this->pages - $midRange)) {
            $startRange = $this->pages - 9;
            $stopRange = $this->pages;
        }

        #pages
        for ($x = 0; $x < $this->pagesToShow; $x++) {

            $pageNum = $x + $startRange;
            if ($pageNum == $this->currentPage) {
                $pagerLinks .= '<li class="active"><a href=#">'.$pageNum.'</a></li>';
            } else {
                $pagerLinks .= '<li><a href="index.php?page='.$pageNum.'">'.$pageNum.'</a></li>';
            }
        }

        #check next
        if($this->currentPage == $this->pages) {
            $pagerLinks .= '<li class="disabled"><a href="#">></a></li>';
            $pagerLinks .= '<li class="disabled"><a href="#">>></a></li>';
        } else {
            $nextPage = $this->currentPage +1;
            $pagerLinks .= '<li><a href="index.php?page='.$nextPage.'">></a></li>';
            $pagerLinks .= '<li><a href="index.php?page='.$lastPage.'">>></a></li>';
        }
        $pagerLinks .= '</ul>';

        return $pagerLinks;
    }
}
?>
