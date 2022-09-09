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
 * Class AdminAcceptSuggestionThread
 * @desc JSON API: (admin) accept suggestion
 */
class AdminAcceptSuggestionThread
{
    private $discussionController;
    private $sId;
    private $sContentType;

    public function __construct()
    {
        $this->discussionController = new DiscussionController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
        $this->processAcceptSuggestion();
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $jsonArr = json_decode($_POST['json_data'], true);
            if (isset($jsonArr["id"])) $this->sId = $jsonArr["id"];
            if (isset($jsonArr["contentType"])) $this->sContentType = $jsonArr["contentType"];
        }
    }

    private function checkParams()
    {
        if ($this->sId == '' ||
            $this->sContentType == ''
        ) {
            return FALSE;
        }
        return TRUE;
    }

    /*
 * return code
 * 0 - success
 * 1 - wrong input
 * format: { "status": code }
 */
    private function processAcceptSuggestion()
    {
        if (!$this->checkParams())
            $this->returnStatus(1);

        if ($this->sContentType == 'auth')
            $this->discussionController->acceptAuthSuggestion($this->sId);
        else if ($this->sContentType == 'feature')
            $this->discussionController->acceptFeatureSuggestion($this->sId);
        else if ($this->sContentType == 'subfeature')
            $this->discussionController->acceptSubfeatureSuggestion($this->sId);

        $this->returnStatus(0);
    }

    private function returnStatus($success)
    {
        die('{"status": "' . $success . '"}');
    }
}

?>
