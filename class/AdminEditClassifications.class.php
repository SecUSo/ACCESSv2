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
 * Class AdminEditClassifications
 * @desc The Class gets Data in form of the Classification-Values of Authentication Schemes in a selected Class of a
 *          selected Feature for Changes in the Database.
 * @var $isViewable - If the Page has viewable Content
 * @var $classifyAuthenticationsController - The Controller used by the Class
 * @var $sessionController - The Session Controller
 */
class AdminEditClassifications
{
    public static $isViewable = TRUE;
    private $classifyAuthenticationsController;
    private $sessionController;

    /**
     * AdminEditClassifications constructor.
     */
    public function __construct()
    {
        $this->classifyAuthenticationsController = new ClassifyAuthenticationsController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
    }

    /**
     * getParams()
     * @desc Checks if the User is Admin and if the needed Data is set. Afterwards the Data is inserted by the
     *          ClassifyAuthenticationsController.
     */
    private function getParams(){
        // Decode the incoming Data from the Front-End
        if (
            $this->sessionController->getIsAdmin() != 0 &&
            isset($_POST['json']) &&
            isset($_POST['feature']) &&
            isset($_POST['class'])
            ) {
                $class = htmlspecialchars($_POST['class']);
                $feature = htmlspecialchars($_POST['feature']);
                $json = json_decode($_POST['json'], true);
                $this->classifyAuthenticationsController->setClassificationValues($class, $feature, $json);
        }
    }

}
?>