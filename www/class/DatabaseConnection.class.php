<?
/**
 * #####################################################################################################################
 * Copyright (C) 2016   Thomas Weber
 * #####################################################################################################################
 * This file is part of AccessV2.
 *
 * AccessV2 is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 * AccessV2 is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *     along with AccessV2.  If not, see <http://www.gnu.org/licenses/>.
 * #####################################################################################################################
 **/
?>


<?
include_once("config.php");

/**
 * Class DatabaseConnection
 * @desc mysqli database connection class
 */
class DatabaseConnection
{
    private $hostName;
    private $userName;
    private $password;
    private $dbName;
    protected $connection;

    public function __construct()
    {
        $this->hostName = DB_HOST;
        $this->userName = DB_USER;
        $this->password = DB_PASSWORD;
        $this->dbName = DB_DB;

        $this->connection = mysqli_connect($this->hostName, $this->userName, $this->password, $this->dbName);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL DB";
            //debug
            //echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }

    private function close()
    {
        mysqli_close($this->connection);
    }

    //TODO: SQL ERROR DEBUG ONLY!
    protected function set($query)
    {
        if (!mysqli_query($this->connection, $query)) {
            echo "SQL ERROR (SET)";
            //debug
            // printf("SQL ERROR: %s\n", mysqli_error($this->connection));
        }
    }

    //TODO: SQL ERROR DEBUG ONLY!
    protected function get($query)
    {
        $get_result = mysqli_query($this->connection, $query);
        if (!$get_result) {
            return array();
            //echo "SQL ERROR (GET)";
            //debug
            //printf("SQL ERROR: %s\n", mysqli_error($this->connection));
        }
        $get_data = array();

        while ($row = mysqli_fetch_assoc($get_result)) {
            $get_data[] = $row;
        }
        return $get_data;
    }

    protected function getLatestInsertionId()
    {
        return mysqli_insert_id($this->connection);
    }

}

?>
