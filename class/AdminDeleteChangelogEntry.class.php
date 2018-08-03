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
 * Class AdminDeleteChangelogEntry
 * @desc JSON API: delete discussion entry by id and type
 */


/* Info: Admin can't delete timeline entries anymore, button in content.php for this action was deleted */
class AdminDeleteChangelogEntry
{
    public static $isViewable = TRUE;
    private $sessionController;
    private $suggestionController;

    private $sChangelogId;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->suggestionController = new SuggestionController();

        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");

        $this->getParams();

        //delete timeline entry
        $this->suggestionController->deleteChangelogEntryById($this->sChangelogId);

        $this->returnStatus("1");
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $jsonArr = json_decode($_POST['json_data'], true);
            if (isset($jsonArr["id"])) $this->sChangelogId = $jsonArr["id"];
        }
    }


    private function returnStatus($success)
    {
        die('{"status": "' . $success . '"}');
    }
}
?>
