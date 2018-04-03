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
      $query = '';
      $query .= 'SELECT ithaax_ASPire_config.dataLogs_marine_sensors.*, latitude, longitude ';
      $query .= 'FROM ithaax_ASPire_config.dataLogs_marine_sensors ';
      $query .= 'RIGHT JOIN ithaax_ASPire_config.dataLogs_gps ';
      $query .= 'ON dataLogs_marine_sensors.id = dataLogs_gps.id ';
      $query .= 'LIMIT 50;';
      #$stmt = $pdo->prepare("SELECT * FROM dataLogs_marine_sensors");
      $stmt = $pdo->prepare($query);
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
      #$someHTMLString .= 'LIMIT = 10 for now';
      $allRows = $this->getSensorLogData();
      #$index =  count($allRows);

      $someHTMLString .= '<tr>';
      $someHTMLString .= '<th>Mission ID</th>';
      $someHTMLString .= '<th>ph</th>';
      $someHTMLString .= '<th>conductivity</th>';
      $someHTMLString .= '<th>temperature</th>';
      $someHTMLString .= '<th>t_timestamp</th>';
      $someHTMLString .= '<th>longitude</th>';
      $someHTMLString .= '<th>latitude</th>';
      $someHTMLString .= '</tr>';

      foreach ($allRows as $key) {
          $index = count($key);

          for ($x = 0; $x < $index; $x++) {
            echo $key[$x];
            #print_r($key);
          }
      }



      foreach ($allRows as $key) {
        #$key = (int) $key;


        $someHTMLString .= '<tr>';
        $someHTMLString .= '<td>'.$key['id'].'</td>';
        $someHTMLString .= '<td>'.$key['ph'].'</td>';
        $someHTMLString .= '<td>'.$key['conductivity'].'</td>';
        $someHTMLString .= '<td>'.$key['temperature'].'</td>';
        $someHTMLString .= '<td>'.$key['t_timestamp'].'</td>';
        $someHTMLString .= '<td>'.$key['longitude'].'</td>';
        $someHTMLString .= '<td>'.$key['latitude'].'</td>';
        $someHTMLString .= '</tr>';

        #DEBUG
        #echo '<pre>',print_r($key),'</pre>';
      }
      //generate table data as html for template
      return $someHTMLString;
    }
  }
?>
