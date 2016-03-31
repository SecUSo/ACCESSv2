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
 * Class AdminEditUser
 * @desc JSON API: (admin) edit user by id
 */
class AdminEditUser
{
    public static $isViewable = TRUE;
    private $contentController;
    private $userController;
    private $sessionController;

    private $sId;
    private $sFirstName;
    private $sLastName;
    private $sEMail;
    private $sPassword;
    private $sTitle;
    private $sOrganization;
    private $bIsAdmin;


    public function __construct()
    {
        $this->contentController = new ContentController();
        $this->userController = new UserController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
        $this->processEditUser();
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $jsonArr = json_decode($_POST['json_data'], true);
            if (isset($jsonArr["Id"])) $this->sId = $jsonArr["Id"];
            if (isset($jsonArr["FirstName"])) $this->sFirstName = $jsonArr["FirstName"];
            if (isset($jsonArr["LastName"])) $this->sLastName = $jsonArr["LastName"];
            if (isset($jsonArr["EMail"])) $this->sEMail = $jsonArr["EMail"];
            if (isset($jsonArr["Password"])) $this->sPassword = $jsonArr["Password"];
            if (isset($jsonArr["Title"])) $this->sTitle = $jsonArr["Title"];
            if (isset($jsonArr["Organization"])) $this->sOrganization = $jsonArr["Organization"];
            if (isset($jsonArr["IsAdmin"])) $this->bIsAdmin = $jsonArr["IsAdmin"];

        }
    }

    private function checkParams()
    {
        if ($this->sFirstName == '' ||
            $this->sLastName == '' ||
            $this->sEMail == '' ||
            $this->sPassword == '' ||
            $this->sId == ''
        ) {
            return FALSE;
        }
        return TRUE;
    }

    /*
 * return code:
 * 0 - success updated
 * 1 - wrong input
 * 2 - entry not found
 * format: { "status": code }
 */
    private function processEditUser()
    {
        if (!$this->checkParams())
            $this->returnStatus(1);

        $tempUser = $this->userController->getUserById($this->sId);
        if (count($tempUser) == 0) {
            $this->returnStatus(2);
        }

        $this->userController->updateUser($this->sId, $this->sLastName, $this->sFirstName, $this->sTitle, $this->sEMail, $this->sOrganization, $this->sPassword, $this->bIsAdmin);
        $this->returnStatus(0);

    }

    private function returnStatus($success)
    {
        die('{"status": "' . $success . '"}');
    }
}

?>
