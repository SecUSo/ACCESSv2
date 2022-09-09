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
 * Class AdminDeleteSchemeSuggestion
 * @desc JSON API: delete scheme suggestion
 */
class AdminDeleteSchemeSuggestion
{
    private $sessionController;
    private $suggestionController;
    private $schemeId;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->suggestionController = new SuggestionController();

        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");

        $this->getParams();

        //delete scheme suggestion
        $this->suggestionController->deleteSchemeSuggestion($this->schemeId);

        header('Location: ?AdminSuggestionOverview');
    }

    private function getParams()
    {
        if (isset($_GET['id'])) $this->schemeId = $_GET["id"];
    }
}
?>
