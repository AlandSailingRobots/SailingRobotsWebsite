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
    }

    function getPages($table)
    {
      $pdo = new PDO($this->dsn, $this->usr, $this->pwd, $this->opt);

      $total = $pdo->query("SELECT COUNT(*) as rows FROM $table") ->fetch(PDO::FETCH_OBJ);
      $perpage = 50;#getPerPage();
      $posts   = $total->rows;
      $pages   = ceil($posts / $perpage);

      return $pages;
    }

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

    # ! LIMIT 10 FOR NOW
    public function getSensorLogData($offset, $limit) {
      $pdo = new PDO($this->dsn, $this->usr, $this->pwd, $this->opt);
      $pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
      $query = '';
      $query .= 'SELECT ithaax_ASPire_config.dataLogs_marine_sensors.*, latitude, longitude ';
      $query .= 'FROM ithaax_ASPire_config.dataLogs_marine_sensors ';
      $query .= 'RIGHT JOIN ithaax_ASPire_config.dataLogs_gps ';
      $query .= 'ON dataLogs_marine_sensors.id = dataLogs_gps.id ';
      $query .= 'LIMIT ?, ?;';
      #$stmt = $pdo->prepare("SELECT * FROM dataLogs_marine_sensors");
      $stmt = $pdo->prepare($query);
      $stmt->bindParam(1, $offset,PDO::PARAM_INT);
      $stmt->bindParam(2, $limit,PDO::PARAM_INT);

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

    public function __toString() {
      $someHTMLString = null;
      $sqlResult = $this->getSensorLogData($this->offset, $this->limit);
      $someHTMLString .= $this->getColumnNames($sqlResult);
      $someHTMLString .= $this->getColumnData($sqlResult);

      //generate table data as html for template
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

      $pagerLinks = null;

      #$currentPage = null;

      #check previous
      if($this->currentPage == 1) {
        $pagerLinks .= '<a href="#"></a>';
      } else {
        $prevPage = $this->currentPage -1;
        $pagerLinks .= '<a href="index.php?page='.$prevPage.'"><<</a>';
      }

      #pages

      #check next
      if($this->currentPage == $this->pages) {
        $pagerLinks .= '<a href="#"></a>';
      } else {
        $nextPage = $this->currentPage +1;
        $pagerLinks .= '<a href="index.php?page='.$nextPage.'">>></a>';
      }

      return $pagerLinks;
    }
  }
?>
