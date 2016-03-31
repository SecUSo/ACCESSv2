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
 * Class AddDiscussion
 * @desc JSON API for adding comments/suggestions to auths, features and subfeatures
 */
class AddDiscussion
{
    public static $isViewable = TRUE;
    private $sessionController;
    private $discussionController;

    private $sContentId;
    private $sContentType;
    private $sCommentType;
    private $sComment;
    private $sThreadId;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->discussionController = new DiscussionController();
        if (!$this->sessionController->isSessionValid())
            die("error: no access!");
        $this->getParams();
        $this->processAddAuthDiscussion();
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $jsonArr = json_decode($_POST['json_data'], true);
            if (isset($jsonArr["contentId"])) $this->sContentId = $jsonArr["contentId"];
            if (isset($jsonArr["contentType"])) $this->sContentType = $jsonArr["contentType"];
            if (isset($jsonArr["commentType"])) $this->sCommentType = $jsonArr["commentType"];
            if (isset($jsonArr["comment"])) $this->sComment = $jsonArr["comment"];
            if (isset($jsonArr["threadId"])) $this->sThreadId = $jsonArr["threadId"];

        }
    }

    private function checkParams()
    {
        if ($this->sContentId == '' ||
            $this->sContentType == '' ||
            $this->sCommentType == '' ||
            $this->sComment == ''
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
    private function processAddAuthDiscussion()
    {
        if (!$this->checkParams())
            $this->returnStatus(1);

        if ($this->sContentType == "auth") {
            if ($this->sCommentType == "suggestion") {
                $this->discussionController->addAuthSuggestion($this->sContentId, $this->sComment, $this->sessionController->getId());
            } else if ($this->sCommentType == "subthread") {
                $this->discussionController->addAuthThreadComment($this->sContentId, $this->sComment, $this->sessionController->getId(), $this->sThreadId);
            } else
                $this->discussionController->addAuthComment($this->sContentId, $this->sComment, $this->sessionController->getId());
        } else if ($this->sContentType == "feature") {
            if ($this->sCommentType == "suggestion")
                $this->discussionController->addFeatureSuggestion($this->sContentId, $this->sComment, $this->sessionController->getId());
            else if ($this->sCommentType == "subthread") {
                $this->discussionController->addFeatureThreadComment($this->sContentId, $this->sComment, $this->sessionController->getId(), $this->sThreadId);
            } else
                $this->discussionController->addFeatureComment($this->sContentId, $this->sComment, $this->sessionController->getId());
        } else if ($this->sContentType == "subfeature") {
            if ($this->sCommentType == "suggestion")
                $this->discussionController->addSubfeatureSuggestion($this->sContentId, $this->sComment, $this->sessionController->getId());
            else if ($this->sCommentType == "subthread") {
                $this->discussionController->addSubfeatureThreadComment($this->sContentId, $this->sComment, $this->sessionController->getId(), $this->sThreadId);
            } else
                $this->discussionController->addSubfeatureComment($this->sContentId, $this->sComment, $this->sessionController->getId());
        }
        $this->returnStatus(0);
    }

    private function returnStatus($success)
    {
        die('{"status": "' . $success . '"}');
    }
}

?>
