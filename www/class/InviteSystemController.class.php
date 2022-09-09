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
 * Class InviteSystemController
 * @desc controller for sql input/output of the invite system
 */
class InviteSystemController
{
    private $dbController;

    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    /**
     * addInviteCode($invite_code)
     * @param $invite_code - invite code which can be used for the user registration process
     * @desc This Function will store a new invite code in the database which is valid for 10 days
     */
    public function addInviteCode($invite_code)
    {
        $query = "INSERT INTO invite_codes " .
            "(id, invite_code, expiry_date) " .
            "VALUES(0," .
            "'" . $this->dbController->escapeStripString($invite_code) . "', " .
            "NOW() + INTERVAL 10 DAY);";

        $this->dbController->secureSet($query);
    }

    /**
     * deleteInviteCode($id)
     * @param $id - identifier of the invite code entry which will be deleted
     * @desc Delete an invite code by id
     */
    public function deleteInviteCode($id)
    {
        $sqlData = "DELETE FROM invite_codes WHERE id=" . $this->dbController->escapeStripString($id);

        $this->dbController->secureSet($sqlData);
    }

    /**
     * getInviteCodeOverview()
     * @return All invite code entrys stored in the database
     * @desc Get all invite code entrys
     */
    public function getInviteCodeOverview()
    {
        $sqlData = "SELECT * FROM invite_codes ORDER BY id DESC;";
        $result = $this->dbController->secureGet($sqlData);

        return $result;
    }

    /**
     * checkAndDeleteInviteCode($code)
     * @param $code - invite code to check
     * @return Returns true if code is valid else false
     * @desc Checks if an invite code is valid for user registration process and deletes it afterwards
     * to make sure its used only once
     */
    public function checkAndDeleteInviteCode($code)
    {
        $sqlData = "SELECT * FROM invite_codes WHERE expiry_date > NOW() AND invite_code='" . $this->dbController->escapeStripString($code) . "';";
        $tempData = $this->dbController->secureGet($sqlData);

        if (count($tempData) == 0) {
            return false;
        }

        $index = $tempData[0]["id"];
        $query = "DELETE FROM invite_codes WHERE id=%d;";
        $query = sprintf($query, $index);

        $this->dbController->secureSet($query);

        return true;
    }
}

?>
