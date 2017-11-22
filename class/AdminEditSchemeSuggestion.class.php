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
 * Class AdminEditSchemeSuggestion
 * @desc interface to edit scheme suggestions
 */
class AdminEditSchemeSuggestion
{
    private $sessionController;
    private $suggestionController;
    private $suggestionId;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->suggestionController = new SuggestionController();

        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");

        $this->getParams();
        $this->initTemplate();
    }

    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - USER";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();

        $data_scheme = $this->suggestionController->getSchemesForEditing($this->suggestionId);
        $data_subfeatures = $this->suggestionController->getSubfeaturesForEditing($this->suggestionId);
        $data_classvalues = $this->suggestionController->getClassvaluesForEditing($this->suggestionId);
        $data_new_classes = $this->suggestionController->getNewClassesForEditing($this->suggestionId);
        $data_authIds = $this->suggestionController->getAllAuthIDs();

        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/editschemesuggestion.php");
        include_once("content/footer.php");
    }

    private function getParams()
    {
        if (isset($_GET['id'])) $this->suggestionId = $_GET["id"];
    }
}

?>
