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
?>

<?

/**
 * Class AdminEditFeature
 * @desc JSON API: edit feature description by id
 */
class AdminEditFeature
{
    public static $isViewable = TRUE;
    private $featureController;
    private $sessionController;

    private $sId;
    private $sDescription;

    public function __construct()
    {
        $this->featureController = new FeatureController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
        $this->processEditFeature();
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $jsonArr = json_decode($_POST['json_data'], true);
            if (isset($jsonArr["id"])) $this->sId = $jsonArr["id"];
            if (isset($jsonArr["description"])) $this->sDescription = $jsonArr["description"];
        }
    }

    private function checkParams()
    {
        if ($this->sId == '' ||
            $this->sDescription == ''
        ) {
            return FALSE;
        }
        return TRUE;
    }

    /*
 * return code:
 * -1 - success (new entry)
 * 0 - success (updated)
 * 1 - wrong input
 * format: { "status": code }
 */
    private function processEditFeature()
    {
        if (!$this->checkParams())
            $this->returnStatus(1);


        if (!$this->featureController->isInFeatureTable($this->sId)) {
            $this->featureController->addFeature($this->sId, $this->sDescription);
            $this->returnStatus(-1);
        } else {
            $this->featureController->updateFeature($this->sId, $this->sDescription);
            $this->returnStatus(0);
        }
    }

    private
    function returnStatus($success)
    {
        die('{"status": "' . $success . '"}');
    }
}

?>
