<?
class DatabaseConfig {
    protected $hostName;
    protected $userName;
    protected $password;
    protected $dbName;

    public function __construct()
    {  
        $this->$hostName = 'localhost';
        $this->$userName = 'root';
        $this->$password = 'pass';
        $this->$dbName = 'db';
    }
}
?>
