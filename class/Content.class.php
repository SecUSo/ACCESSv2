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
 * Class Content
 * @desc output content entry by id
 */
class Content
{
    public static $isViewable = TRUE;
    private $contentController;

    private $contentId;
    private $sessionController;
    private $discussionController;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->contentController = new ContentController();
        $this->discussionController = new DiscussionController();
        $this->getParams();
        $this->initTemplate();
    }

    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - CONTENT";
        $contentData = $this->getInfo();
        $content_name = $contentData[0]["name"];
        $content_category = $contentData[0]["category"];
        $content_description = $contentData[0]["description"];
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_subfeatures = $this->contentController->getSubFeaturesForAuthIndex($this->contentId);
        $data_discussion = $this->discussionController->getAuthDiscussion($this->contentId);
        $data_discussion_subthreads = $this->discussionController->getAuthDiscussionThread($this->contentId);
        $data_isUser = $this->sessionController->isSessionValid();
        $data_contentId = $_GET['id'];
        $data_contentType = "auth";
        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/content.php");
        include_once("content/footer.php");
    }

    private function getParams()
    {
        if (isset($_GET['id'])) $this->contentId = $_GET["id"];
    }

    private function checkParams()
    {
        if ($this->contentId == '')
            return FALSE;

        return TRUE;
    }

    public function getInfo()
    {
        if (!$this->checkParams())
            die("error: bad input!");

        $tempContent = $this->contentController->getContent($this->contentId);

        if (count($tempContent) == 0) {
            die("error: no content found!");
        }
        return $tempContent;
    }

}

?>
