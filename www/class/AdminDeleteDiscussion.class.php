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
 * Class AdminDeleteDiscussion
 * @desc JSON API: delete discussion entry by id and type
 */
class AdminDeleteDiscussion
{
    public static $isViewable = TRUE;
    private $sessionController;
    private $discussionController;

    private $sContentId;
    private $sContentType;
    private $sIsSubthread;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->discussionController = new DiscussionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
        $this->processDeleteDiscussion();
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $jsonArr = json_decode($_POST['json_data'], true);
            if (isset($jsonArr["contentId"])) $this->sContentId = $jsonArr["contentId"];
            if (isset($jsonArr["contentType"])) $this->sContentType = $jsonArr["contentType"];
            if (isset($jsonArr["isSubthread"])) $this->sIsSubthread = $jsonArr["isSubthread"];
            else
                $this->sIsSubthread = "0";
        }
    }

    private function checkParams()
    {
        if ($this->sContentId == '' ||
            $this->sContentType == ''
        ) {
            return FALSE;
        }
        return TRUE;
    }

    /*
 * return code
 * 0 - success (added discussion entry)
 * 1 - wrong input
 * format: { "status": code }
 */
    private function processDeleteDiscussion()
    {
        if (!$this->checkParams())
            $this->returnStatus(1);

        if ($this->sContentType == "auth")
            if ($this->sIsSubthread == "1")
                $this->discussionController->deleteAuthSubthread($this->sContentId);
            else
                $this->discussionController->deleteAuthDiscussion($this->sContentId);
        else if ($this->sContentType == "feature")
            if ($this->sIsSubthread == "1")
                $this->discussionController->deleteFeatureSubthread($this->sContentId);
            else
                $this->discussionController->deleteFeatureDiscussion($this->sContentId);
        else if ($this->sContentType == "subfeature")
            if ($this->sIsSubthread == "1")
                $this->discussionController->deleteSubfeatureSubthread($this->sContentId);
            else
                $this->discussionController->deleteSubFeatureDiscussion($this->sContentId);


        $this->returnStatus(0);
    }

    private function returnStatus($success)
    {
        die('{"status": "' . $success . '"}');
    }
}

?>
