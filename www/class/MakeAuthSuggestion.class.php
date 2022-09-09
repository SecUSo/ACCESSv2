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
 * Class MakeAuthSuggestion
 * @desc API to insert scheme suggestions
 */
class MakeAuthSuggestion
{
    private $userController;
    private $sessionController;
    private $authController;
    private $suggestionController;
    private $jsonArr;

    public function __construct()
    {
        $this->userController = new UserController();
        $this->sessionController = new SessionController();
        $this->authController = new AuthenticationsController();
        $this->suggestionController = new SuggestionController();

        if (!$this->sessionController->isSessionValid())
            die("error: no access!");

        $this->getParams();

        //get suggestion id for later usage
        $insertionId = $this->suggestionController->insertSingleAuthenticationSuggestion($this->jsonArr["name"], $this->jsonArr["description"], $this->jsonArr["category"], $this->sessionController->getId());

        $activeSubFeatures = array();

        //receive all set subfeatures
        foreach ($this->jsonArr["subfeatures"] as $subfeature) {
            if (current($subfeature) == 1) {
                array_push($activeSubFeatures, key($subfeature));
            }
        }

        //insert subfeatures of scheme suggestion
        $this->suggestionController->insertAuthSubFeatures($insertionId, $activeSubFeatures);

        //set all classvalues - new classes are stored separately
        foreach ($this->jsonArr["classvalues"] as $subclass) {
            if (array_key_exists("data", $subclass)) {
                $this->suggestionController->setClassificationValuesSuggestion($insertionId, $subclass["class"], $subclass["feature"], $subclass["data"]);
            } else if (array_key_exists("new_class", $subclass)) {
                $this->suggestionController->insertNewClass($insertionId, $subclass["feature_id"], $subclass["new_class"]);
            }
        }
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $this->jsonArr = json_decode($_POST['json_data'], true);
        }
    }
}

?>
