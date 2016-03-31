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

/**
 * Class DatabaseController
 * @desc wrapper for databaseconnection, adding secure getter/setter
 */
class DatabaseController extends DatabaseConnection
{
    public function __construct()
    {
        parent:: __construct();
    }

    public function secureSet($query)
    {
        $this->set($query);
    }

    public function secureGet($query)
    {
        return $this->get($query);
    }

    public function escapeString($input)
    {
        return mysqli_real_escape_string($this->connection, $input);
    }

    public function escapeStripString($input)
    {
        return mysqli_real_escape_string($this->connection, strip_tags($input));
    }


}

?>
