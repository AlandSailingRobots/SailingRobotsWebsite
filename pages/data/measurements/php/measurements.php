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

    # ! LIMIT 10 FOR NOW
    public function getSensorLogData() {
      $pdo = new PDO($this->dsn, $this->usr, $this->pwd, $this->opt);
      $stmt = $pdo->prepare("SELECT * FROM dataLogs_marine_sensors LIMIT 10");
      $stmt->execute();
      $allRows = $stmt->fetchAll();

      return $allRows;
    }

    public function __set($name, $value) {
      $this->$name = $value;
    }

    public function __get($name) {
      return $this->$name;
    }

    public function __toString() {
      $someHTMLString = null;
      $someHTMLString .= 'LIMIT = 10 for now';
      $allRows = $this->getSensorLogData();
      foreach ($allRows as $key) {
        #$key = (int) $key;

        #DEBUG
        echo '<pre>',print_r($key),'</pre>';
      }
      //generate table data as html for template
      return $someHTMLString;
    }
  }
?>
