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
?>

<?

/**
 * Class AdminEditSchemeSuggestionSubmit
 * @desc API to change scheme suggestion data
 */
class AdminEditSchemeSuggestionSubmit
{
    private $sessionController;
    private $suggestionController;
    private $jsonArr;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->suggestionController = new SuggestionController();

        if (!$this->sessionController->isSessionValid())
            die("error: no access!");

        $this->getParams();
        $this->editScheme();
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $this->jsonArr = json_decode($_POST['json_data'], true);
        }
    }

    private function editScheme()
    {
        //set meta data
        $this->suggestionController->EditMetaForSchemeSuggestion($this->jsonArr["id"], $this->jsonArr["name"], $this->jsonArr["description"], $this->jsonArr["category"]);

        //set classvalues
        $this->suggestionController->EditClassvaluesForSchemeSuggestion($this->jsonArr["id"], $this->jsonArr["classvalues"]);
    }
}

?>
