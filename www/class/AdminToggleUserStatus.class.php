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
 * Class AdminToggleUserStatus
 * @desc JSON API: (admin) toggle user status (activate/deactivate) by id
 */
class AdminToggleUserStatus
{
    public static $isViewable = TRUE;
    private $userController;
    private $sessionController;

    private $sId;

    public function __construct()
    {
        $this->userController = new UserController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
        $this->processToggleAccount();
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $jsonArr = json_decode($_POST['json_data'], true);
            if (isset($jsonArr["id"])) $this->sId = $jsonArr["id"];
        }
    }

    private function checkParams()
    {
        if ($this->sId == ''
        ) {
            return FALSE;
        }
        return TRUE;
    }

    /*
 * return code
 * -1 - user disabled (success)
 * 0 - user activated (success)
 * 1 - wrong input
 * 2 - user not found
 * format: { "status": code }
 */
    private function processToggleAccount()
    {
        if (!$this->checkParams())
            $this->returnStatus(1);

        $tempUser = $this->userController->getUserById($this->sId);
        if (count($tempUser) == 0) {
            $this->returnStatus(2);
        }

        if ($tempUser[0]['AccountStatus'] == 'activated') {
            $this->userController->disableUser($this->sId);
            $this->returnStatus(-1);
        } else {
            $this->userController->activateUser($this->sId);
            $this->returnStatus(0);
        }

        $this->returnStatus(0);
    }

    private function returnStatus($success)
    {
        die('{"status": "' . $success . '"}');
    }
}

?>
