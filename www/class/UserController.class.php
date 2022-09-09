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
 * Class UserController
 * @desc controller for sql input/output of user data
 */
class UserController
{
    private $dbController;
    const defaultUserRegistrationStatus = 'activated';

    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    public function addUser($lastname, $firstname, $title, $email, $organization, $password)
    {
        $tempIp = getenv('REMOTE_ADDR');
        $sqlData = "INSERT INTO users " .
            "(Id, LastName, FirstName, Title, EMail, Organization, Password, AccountStatus, RegisterDate, SessionIpv4, IsAdmin) " .
            "VALUES(0," .
            "'" . $this->dbController->escapeStripString($lastname) . "', " .
            "'" . $this->dbController->escapeStripString($firstname) . "', " .
            "'" . $this->dbController->escapeStripString($title) . "', " .
            "'" . $this->dbController->escapeStripString($email) . "', " .
            "'" . $this->dbController->escapeStripString($organization) . "', " .
            "'" . $this->dbController->escapeStripString($password) . "', " .
            "'" . UserController::defaultUserRegistrationStatus . "', " .
            "NOW(), " .
            "'" . getenv('REMOTE_ADDR') . "', " .
            "FALSE" .
            ");";

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function disableUser($id)
    {
        $sqlData = "UPDATE users SET " .
            "AccountStatus='disabled' " .
            "WHERE Id=" . $this->dbController->escapeStripString($id) . ";";

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function activateUser($id)
    {
        $sqlData = "UPDATE users SET " .
            "AccountStatus='activated' " .
            "WHERE Id=" . $this->dbController->escapeStripString($id) . ";";

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function updateUser($id, $lastname, $firstname, $title, $email, $organization, $password, $isadmin)
    {
        $sqlData = "UPDATE users SET " .
            "Lastname='" . $this->dbController->escapeStripString($lastname) . "', " .
            "FirstName='" . $this->dbController->escapeStripString($firstname) . "', " .
            "Title='" . $this->dbController->escapeStripString($title) . "', " .
            "EMail='" . $this->dbController->escapeStripString($email) . "', " .
            "Organization='" . $this->dbController->escapeStripString($organization) . "', " .
            "Password='" . $this->dbController->escapeStripString($password) . "', " .
            "IsAdmin='" . $this->dbController->escapeStripString($isadmin) . "' " .
            "WHERE Id=" . $this->dbController->escapeStripString($id) . ";";

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function updateSessionIp($id)
    {
        $sqlData = "UPDATE users SET SessionIpv4='" . getenv('REMOTE_ADDR') . "' WHERE Id=" . $this->dbController->escapeStripString($id) . ";";

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function deleteUser($id)
    {
        $sqlData = "DELETE FROM users WHERE Id=" . $this->dbController->escapeStripString($id);

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function isUserAdmin($id)
    {
        $sqlData = "SELECT IsAdmin FROM users WHERE Id=" . $this->dbController->escapeStripString($id);

        $tempData = $this->dbController->secureGet($sqlData);
        if (count($tempData) == 0) {
            return false;
        }
        if ($tempData[0]['IsAdmin'] == 1)
            return true;

        return false;
    }

    public function isAccountEnabled($id)
    {
        $sqlData = "SELECT AccountStatus FROM users WHERE Id=" . $this->dbController->escapeStripString($id);

        $tempData = $this->dbController->secureGet($sqlData);
        if (count($tempData) == 0) {
            return false;
        }
        if ($tempData[0]['AccountStatus'] == 'activated') {

            return true;
        }

        return false;
    }

    public function getUserById($id)
    {
        $sqlData = "SELECT Id, LastName, FirstName, Title, EMail, Organization, Password, AccountStatus, RegisterDate, SessionIpv4, IsAdmin FROM users WHERE Id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function getUserByEMail($email)
    {
        $sqlData = "SELECT Id, LastName, FirstName, Title, EMail, Organization, Password, AccountStatus, RegisterDate, SessionIpv4, IsAdmin FROM users WHERE EMail='" . $this->dbController->escapeStripString($email) . "'";

        return $this->dbController->secureGet($sqlData);
    }

    public function getUserByIdMinimal($id)
    {
        $sqlData = "SELECT Id, LastName, FirstName, Title, EMail, Organization FROM users WHERE Id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function getUserOverview()
    {
        $sqlData = "SELECT Id, LastName, FirstName, EMail, AccountStatus, IsAdmin FROM users;";

        return $this->dbController->secureGet($sqlData);
    }

}

?>
