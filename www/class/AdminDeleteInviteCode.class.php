<?
/**
 * #####################################################################################################################
 * Copyright (C) 2017   Thomas Weber
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
 * Class AdminDeleteInviteCode
 * @desc JSON API: delete user by ID
 */
class AdminDeleteInviteCode
{
    public static $isViewable = TRUE;
    private $inviteController;
    private $sessionController;

    private $sId;

    public function __construct()
    {
        $this->inviteController = new InviteSystemController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
        $this->inviteController->deleteInviteCode($this->sId);

        die('{"status": "1"}');
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $jsonArr = json_decode($_POST['json_data'], true);
            if (isset($jsonArr["id"])) $this->sId = $jsonArr["id"];
        }
    }


}

?>
