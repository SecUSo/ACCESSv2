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
 * Class UserLogin
 * @desc JSON API: login by email and password
 */
class UserLogin
{
    public static $isViewable = TRUE;
    private $userController;
    private $sessionController;

    private $sEMail;
    private $sPassword;

    public function __construct()
    {
        $this->userController = new UserController();
        $this->sessionController = new SessionController();
        $this->getParams();
        $this->processLogin();
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $jsonArr = json_decode($_POST['json_data'], true);
            if (isset($jsonArr["EMail"])) $this->sEMail = $jsonArr["EMail"];
            if (isset($jsonArr["Password"])) $this->sPassword = $jsonArr["Password"];
        }
    }

    private function checkParams()
    {
        if ($this->sEMail == '' ||
            $this->sPassword == ''
        ) {
            return FALSE;
        }
        return TRUE;
    }

    /*
 * return code:
 * 0 - success
 * 1 - wrong input
 * 2 - user not found
 * 3 - wrong password
 * format: { "status": code }
 */
    private function processLogin()
    {
        if (!$this->checkParams())
            $this->returnStatus(1);

        $tempUser = $this->userController->getUserByEMail($this->sEMail);
        if (count($tempUser) == 0) {
            $this->returnStatus(2);
        }

        // Migrating legacy passwords to better security on the fly
        // First we check whether right mail and pwd has been provided
        if ((($tempUser[0]['EMail'] == $this->sEMail) && ($tempUser[0]['Password'] == md5($this->sPassword)))) {
            // Then we update the hash in the DB, but do not login, this is done in the next check
            $this->userController->updateUser($tempUser[0]['Id'],
                                              $tempUser[0]['LastName'],
                                              $tempUser[0]['FirstName'],
                                              $tempUser[0]['Title'],
                                              $tempUser[0]['EMail'],
                                              $tempUser[0]['Organization'],
                                              password_hash($this->sPassword, PASSWORD_DEFAULT),
                                              $tempUser[0]['IsAdmin']);
            // Update the data on the user for use in the authentication
            $tempUser = $this->userController->getUserByEMail($this->sEMail);
        }

        if (!(($tempUser[0]['EMail'] == $this->sEMail) && (password_verify($this->sPassword, $tempUser[0]['Password'])))) {
            $this->returnStatus(3);
        }

        $this->sessionController->setSessionData($tempUser[0]['Id']);

        $this->returnStatus(0);
    }

    private function returnStatus($success)
    {
        die('{"status": "' . $success . '"}');
    }
}

?>
