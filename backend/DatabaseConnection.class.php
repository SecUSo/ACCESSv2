<?php

class DatabaseConnection extends DatabaseConfig    {
  private $connection;
  private $dbLink;

  public function __construct($host,$user,$pass,$database)
  {
    parent :: __construct();
    $this->$hostName = $host;
    $this->$userName = $user;
    $this->$password = $pass;
    $this->$dbName = $database;

    $this->$connection = mysql_connect($this->$hostName, $this->$userName, $this->$password);

    if(!$this->$connection)
    {
        die("Can't connect to mysql server: ".mysql_error());
    }

    $dbLink = mysql_select_db($this->$dbName, $this->$connection);

    if(!$this->$dbLink)
    {
        die("Can't connect to database: ".mysql_error());
    }
  }

  function close()
  {
    mysql_close($this->$connection);
    $this->$hostName = "";
    $this->$userName = "";
    $this->$password = "";
    $this->$dbName = "";
    $this->$dbLink = "";
  }

    function set($query)
    {
      mysql_query($query,$this->$dbLink);
    }

    function get($query)
    {
      $get_result = mysql_query($query);
      $get_data = array();

      while ($get_row = mysql_fetch_array($result, MYSQL_BOTH)) {
      {
        array_push($get_data,$get_row);
      }
      return $get_data;
    }
}
?>
