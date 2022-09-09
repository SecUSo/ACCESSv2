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


/**
 * Class UserRegistration
 * @desc JSON API: register user
 */
class UserRegistration
{
    public static $isViewable = TRUE;
    private $userController;
    private $sessionController;
    private $inviteController;

    private $sFirstName;
    private $sLastName;
    private $sEMail;
    private $sPassword;
    private $sTitle;
    private $sOrganization;
    private $sPGPPublicKey;
    private $sInviteCode;

    public function __construct()
    {
        $this->userController = new UserController();
        $this->sessionController = new SessionController();
        $this->inviteController = new InviteSystemController();
        $this->getParams();
        $this->registration();
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $jsonArr = json_decode($_POST['json_data'], true);
            if (isset($jsonArr["FirstName"])) $this->sFirstName = $jsonArr["FirstName"];
            if (isset($jsonArr["LastName"])) $this->sLastName = $jsonArr["LastName"];
            if (isset($jsonArr["EMail"])) $this->sEMail = $jsonArr["EMail"];
            if (isset($jsonArr["Password"])) $this->sPassword = $jsonArr["Password"];
            if (isset($jsonArr["Title"])) $this->sTitle = $jsonArr["Title"];
            if (isset($jsonArr["Organization"])) $this->sOrganization = $jsonArr["Organization"];
            if (isset($jsonArr["PGPPublicKey"])) $this->sPGPPublicKey = $jsonArr["PGPPublicKey"];
            if (isset($jsonArr["InviteCode"])) $this->sInviteCode = $jsonArr["InviteCode"];
        }
    }

    private function checkParams()
    {
        if ($this->sFirstName == '' ||
            $this->sLastName == '' ||
            $this->sEMail == '' ||
            $this->sPassword == '' ||
            $this->sInviteCode == ''
        ) {
            return FALSE;
        }

        return TRUE;
    }


//TODO: CHECK FOR VALID INPUT
    private function registration()
    {
        if (!$this->checkParams())
            $this->returnStatus(1);

        $checkUniqueEmail = $this->userController->getUserByEMail($this->sEMail);
        if (count($checkUniqueEmail) > 0) {
            $this->returnStatus(3);
        }

        if (!$this->inviteController->checkAndDeleteInviteCode($this->sInviteCode))
            $this->returnStatus(2);

        $this->userController->addUser($this->sLastName, $this->sFirstName, $this->sTitle,
            $this->sEMail, $this->sOrganization, password_hash($this->sPassword, PASSWORD_DEFAULT), $this->sPGPPublicKey);

        $tempUser = $this->userController->getUserByEMail($this->sEMail);
        $this->sessionController->setSessionData($tempUser[0]['Id']);

        $this->returnStatus(0);
    }

    /*
     * return codes:
     * 0 - success
     * 1 - wrong input(param)
     * 2 - wrong invite code
     * 3 - user (email) already exists
     * format: { "status": code }
     */
    private function returnStatus($success)
    {
        die('{"status": "' . $success . '"}');
    }
}

?>
