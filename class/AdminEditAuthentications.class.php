<?
/**
 * #####################################################################################################################
 * Copyright (C) 2016   Christian Engelbert
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
 * Class AdminEditAuthentications
 * @var $isViewable : Static Boolean if the page is viewable
 * @var $authenticationsController : Needed Controller for the Class
 * @var $sessionController : The Session-Controller
 */
class AdminEditAuthentications
{
    public static $isViewable = TRUE;
    private $authenticationsController;
    private $sessionController;

    /**
     * AdminEditAuthentications constructor.
     */
    public function __construct()
    {
        $this->authenticationsController = new AuthenticationsController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
    }

    /**
     * getParams()
     * @desc Function to parse the POST-Input und let the AuthenticationsController insert the data into the Database
     * @data The POST-Variable json has the necessary input in a JSON-Format String of the following format:
     *
     * {
     *  "Authentication-Name":"Category-Name",
     *  "Authentication-Name":"Category-Name",
     *  ..
     * }
     */
    private function getParams(){
        // Decode the incoming Data from the Front-End
        if($this->sessionController->getIsAdmin() != 0 && isset($_POST['json'])) {
            $json = json_decode($_POST['json'], true);
            $this->authenticationsController->deleteAuthentications($json);
            $this->authenticationsController->insertAuthentications($json);
        }
    }

}
?>