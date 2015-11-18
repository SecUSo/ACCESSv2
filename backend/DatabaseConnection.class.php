<?

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

  $this->$connection = mysqli_connect($this->$hostName, $this->$userName, $this->$password);

  if(!$this->$connection)
  {
    die("Can't connect to mysql server: ".mysqli_error());
  }

  $dbLink = mysqli_select_db($this->$dbName, $this->$connection);

  if(!$this->$dbLink)
  {
    die("Can't connect to database: ".mysqli_error());
  }
}

function close()
{
  mysqli_close($this->$connection);
  $this->$hostName = "";
  $this->$userName = "";
  $this->$password = "";
  $this->$dbName = "";
  $this->$dbLink = "";
}

function set($query)
{
  mysqli_query($query,$this->$dbLink);
}

function get($query)
{
  $get_result = mysqli_query($query);
  $get_data = array();

  while ($get_row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    {
      array_push($get_data,$get_row);
    }
    return $get_data;
  }
}
?>
