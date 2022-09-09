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
 * Class AdminUser
 * @desc output single user by id
 */
class AdminUser
{
    private $sessionController;
    private $userController;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->userController = new UserController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->initTemplate();
    }

    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - USER";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_userData = $this->userController->getUserById($_GET['id']);
        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/user.php");
        include_once("content/footer.php");
    }
}

?>
