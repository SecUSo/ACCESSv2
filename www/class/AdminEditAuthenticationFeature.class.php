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
 * Class AdminEditAuthenticationFeature
 * @desc - Class to change the SubFeatures of the Authentication Schemes
 * @var $isViewable : Static Boolean if the page is visible
 * @var $authenticationFeatureController : Needed Controller for the Class
 * @var $sessionController : The session Controller
 */
class AdminEditAuthenticationFeature
{
    public static $isViewable = TRUE;
    private $authenticationFeatureController;
    private $sessionController;

    /**
     * AdminEditAuthenticationFeature constructor.
     */
    public function __construct()
    {
        $this->authenticationFeatureController = new AuthenticationFeatureController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
    }

    /**
     * getParams()
     * @desc Checks if the POST-Data is set and lets the AuthenticationFeatureController change the Data in the Database
     * @data POST-Variable 'json' with a JSON-String in the following format:
     *  {
     *      "Authentication":"Authentication-Name",
     *      "SubFeature-Name":true/false,
     *      "SubFeature-Name":true/false,
     *      ..
     *  }
     */
    private function getParams(){
        // Decode the incoming Data from the Front-End
        if($this->sessionController->getIsAdmin() != 0 && isset($_POST['json'])) {
            $json = json_decode($_POST['json'], true);
            if(isset($json['Authentication'])){
                $authName = $json['Authentication'];
                $subFeaturesToDelete = array();
                $subFeaturesToInsert = array();
                foreach($json as $subFeatureName => $isTrue){
                    if($subFeatureName == "Authentication") {
                        continue;
                    }
                    if($isTrue) {
                        array_push($subFeaturesToInsert, $subFeatureName);
                    }
                    else{
                        array_push($subFeaturesToDelete, $subFeatureName);
                    }
                }
                $this->authenticationFeatureController->deleteAuthSubFeatures($authName, $subFeaturesToDelete);
                $this->authenticationFeatureController->insertAuthSubFeatures($authName, $subFeaturesToInsert);
            }
        }
    }

}
?>