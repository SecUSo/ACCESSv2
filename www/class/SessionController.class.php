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
 * Class SessionController
 * @desc controller for sql input/output of session data
 */
class SessionController
{
    private $userController;

    public function __construct()
    {
        $this->userController = new UserController();
        $this->startSession();
    }

    public function startSession()
    {
        session_start();
    }

    public function getId()
    {
        if (!$this->isSessionValid())
            return -1;

        return $_SESSION['id'];
    }

    public function getIsAdmin()
    {
        if (!$this->isSessionValid())
            return 0;

        return $_SESSION['isAdmin'];
    }

    public function getName()
    {
        if (!$this->isSessionValid())
            return -1;

        $tempUser = $this->userController->getUserById($_SESSION["id"]);

        return $tempUser[0]['FirstName'] . " " . $tempUser[0]['LastName'];
    }

    public function setSessionData($id)
    {
        $this->userController->updateSessionIp($id);
        $_SESSION['id'] = $id;
        $_SESSION['isAdmin'] = $this->userController->isUserAdmin($id);
        $_SESSION['accountStatus'] = $this->userController->isAccountEnabled($id);

    }

    public function isSessionValid()
    {
        if (!isset($_SESSION['id'])) {
            $this->endSession();
            return false;
        }

        $tempUser = $this->userController->getUserById($_SESSION["id"]);

        if (($_SERVER['REMOTE_ADDR'] != $tempUser[0]['SessionIpv4']) || ($tempUser[0]['AccountStatus'] == 'disabled') || ($tempUser[0]['IsAdmin'] != $_SESSION['isAdmin'])) {
            $this->endSession();
            return false;
        }

        return true;
    }

    public function endSession()
    {
        //session_unset();
        if (session_id() != '' && isset($_SESSION))
            session_destroy();
    }
}

?>
