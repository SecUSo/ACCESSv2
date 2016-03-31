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
 * Class DiscussionController
 * @desc controller for sql input/output of discussion data
 */
class DiscussionController
{
    private $dbController;
    const defaultThreadStatus = 'active';

    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    public function addAuthComment($authId, $content, $authorId)
    {
        $sqlData = "INSERT INTO discuss_auth " .
            "(id, foreignid, post_type, post_threadstatus, post_content, post_authorid, post_date) " .
            "VALUES(0," .
            $this->dbController->escapeStripString($authId) . ", " .
            "'comment'," .
            "'" . DiscussionController::defaultThreadStatus . "', " .
            "'" . $this->dbController->escapeStripString($content) . "'," .
            "'" . $this->dbController->escapeStripString($authorId) . "'," .
            "NOW() " .
            ");";

        $this->dbController->secureSet($sqlData);
        return true;
    }

    public function addAuthSuggestion($authId, $content, $authorId)
    {
        $sqlData = "INSERT INTO discuss_auth " .
            "(id, foreignid, post_type, post_threadstatus, post_content, post_authorid, post_date) " .
            "VALUES(0," .
            $this->dbController->escapeStripString($authId) . ", " .
            "'suggestion'," .
            "'" . DiscussionController::defaultThreadStatus . "', " .
            "'" . $this->dbController->escapeStripString($content) . "'," .
            "'" . $this->dbController->escapeStripString($authorId) . "'," .
            "NOW()" .
            ");";
        $this->dbController->secureSet($sqlData);
        return true;
    }

    public function addAuthThreadComment($authId, $content, $authorId, $threadId)
    {
        $sqlData = "INSERT INTO discuss_thread_auth " .
            "(id, foreignid, thread_id, post_content, post_authorid, post_date) " .
            "VALUES(0," .
            $this->dbController->escapeStripString($authId) . ", " .
            $this->dbController->escapeStripString($threadId) . ", " .
            "'" . $this->dbController->escapeStripString($content) . "'," .
            "'" . $this->dbController->escapeStripString($authorId) . "'," .
            "NOW() " .
            ");";

        $this->dbController->secureSet($sqlData);
        return true;
    }

    public function addFeatureComment($authId, $content, $authorId)
    {
        $sqlData = "INSERT INTO discuss_feature " .
            "(id, foreignid, post_type, post_threadstatus, post_content, post_authorid, post_date) " .
            "VALUES(0," .
            $this->dbController->escapeStripString($authId) . ", " .
            "'comment'," .
            "'" . DiscussionController::defaultThreadStatus . "', " .
            "'" . $this->dbController->escapeStripString($content) . "'," .
            "'" . $this->dbController->escapeStripString($authorId) . "'," .
            "NOW() " .
            ");";

        $this->dbController->secureSet($sqlData);
        return true;
    }

    public function addFeatureSuggestion($authId, $content, $authorId)
    {
        $sqlData = "INSERT INTO discuss_feature " .
            "(id, foreignid, post_type, post_threadstatus, post_content, post_authorid, post_date) " .
            "VALUES(0," .
            $this->dbController->escapeStripString($authId) . ", " .
            "'suggestion'," .
            "'" . DiscussionController::defaultThreadStatus . "', " .
            "'" . $this->dbController->escapeStripString($content) . "'," .
            "'" . $this->dbController->escapeStripString($authorId) . "'," .
            "NOW() " .
            ");";

        $this->dbController->secureSet($sqlData);
        return true;
    }

    public function addFeatureThreadComment($authId, $content, $authorId, $threadId)
    {
        $sqlData = "INSERT INTO discuss_thread_feature " .
            "(id, foreignid, thread_id, post_content, post_authorid, post_date) " .
            "VALUES(0," .
            $this->dbController->escapeStripString($authId) . ", " .
            $this->dbController->escapeStripString($threadId) . ", " .
            "'" . $this->dbController->escapeStripString($content) . "'," .
            "'" . $this->dbController->escapeStripString($authorId) . "'," .
            "NOW() " .
            ");";

        $this->dbController->secureSet($sqlData);
        return true;
    }

    public function addSubfeatureComment($authId, $content, $authorId)
    {
        $sqlData = "INSERT INTO discuss_subfeature " .
            "(id, foreignid, post_type, post_threadstatus, post_content, post_authorid, post_date) " .
            "VALUES(0," .
            $this->dbController->escapeStripString($authId) . ", " .
            "'comment'," .
            "'" . DiscussionController::defaultThreadStatus . "', " .
            "'" . $this->dbController->escapeStripString($content) . "'," .
            "'" . $this->dbController->escapeStripString($authorId) . "'," .
            "NOW() " .
            ");";

        $this->dbController->secureSet($sqlData);
        return true;
    }

    public function addSubfeatureSuggestion($authId, $content, $authorId)
    {
        $sqlData = "INSERT INTO discuss_subfeature " .
            "(id, foreignid, post_type, post_threadstatus, post_content, post_authorid, post_date) " .
            "VALUES(0," .
            $this->dbController->escapeStripString($authId) . ", " .
            "'suggestion'," .
            "'" . DiscussionController::defaultThreadStatus . "', " .
            "'" . $this->dbController->escapeStripString($content) . "'," .
            "'" . $this->dbController->escapeStripString($authorId) . "'," .
            "NOW() " .
            ");";

        $this->dbController->secureSet($sqlData);
        return true;
    }

    public function addSubfeatureThreadComment($authId, $content, $authorId, $threadId)
    {
        $sqlData = "INSERT INTO discuss_thread_subfeature " .
            "(id, foreignid, thread_id, post_content, post_authorid, post_date) " .
            "VALUES(0," .
            $this->dbController->escapeStripString($authId) . ", " .
            $this->dbController->escapeStripString($threadId) . ", " .
            "'" . $this->dbController->escapeStripString($content) . "'," .
            "'" . $this->dbController->escapeStripString($authorId) . "'," .
            "NOW() " .
            ");";

        $this->dbController->secureSet($sqlData);
        return true;
    }

    public function getAuthDiscussion($authId)
    {
        $sqlData = "SELECT discuss_auth.id, post_type, post_threadstatus, post_content, post_date, LastName, FirstName, Title, Organization FROM discuss_auth LEFT JOIN users ON discuss_auth.post_authorid=users.Id WHERE foreignid=" . $this->dbController->escapeStripString($authId);

        return $this->dbController->secureGet($sqlData);
    }

    public function getAuthDiscussionThread($authId)
    {
        $sqlData = "SELECT discuss_thread_auth.id, thread_id, post_content, post_date, LastName, FirstName, Title, Organization FROM discuss_thread_auth LEFT JOIN users ON discuss_thread_auth.post_authorid=users.Id WHERE foreignid=" . $this->dbController->escapeStripString($authId);

        return $this->dbController->secureGet($sqlData);
    }

    public function getFeatureDiscussion($authId)
    {
        $sqlData = "SELECT discuss_feature.id, post_type, post_threadstatus, post_content, post_date, LastName, FirstName, Title, Organization FROM discuss_feature LEFT JOIN users ON discuss_feature.post_authorid=users.Id WHERE foreignid=" . $this->dbController->escapeStripString($authId);

        return $this->dbController->secureGet($sqlData);
    }

    public function getFeatureDiscussionThread($authId)
    {
        $sqlData = "SELECT discuss_thread_feature.id, thread_id, post_content, post_date, LastName, FirstName, Title, Organization FROM discuss_thread_feature LEFT JOIN users ON discuss_thread_feature.post_authorid=users.Id WHERE foreignid=" . $this->dbController->escapeStripString($authId);

        return $this->dbController->secureGet($sqlData);
    }

    public function getSubfeatureDiscussion($authId)
    {
        $sqlData = "SELECT discuss_subfeature.id, post_type, post_threadstatus, post_content, post_date, LastName, FirstName, Title, Organization FROM discuss_subfeature LEFT JOIN users ON discuss_subfeature.post_authorid=users.Id WHERE foreignid=" . $this->dbController->escapeStripString($authId);

        return $this->dbController->secureGet($sqlData);
    }

    public function getSubfeatureDiscussionThread($authId)
    {
        $sqlData = "SELECT discuss_thread_subfeature.id, thread_id, post_content, post_date, LastName, FirstName, Title, Organization FROM discuss_thread_subfeature LEFT JOIN users ON discuss_thread_subfeature.post_authorid=users.Id WHERE foreignid=" . $this->dbController->escapeStripString($authId);

        return $this->dbController->secureGet($sqlData);
    }

    public function deleteSubFeatureDiscussion($id)
    {
        $sqlData1 = "SELECT foreignid FROM discuss_subfeature WHERE id=" . $this->dbController->escapeStripString($id);

        $tempEntry = $this->dbController->secureGet($sqlData1);

        if (count($tempEntry) == 0)
            return -1;
        else
            $this->deleteSubFeatureThreads($id, $tempEntry[0]['foreignid']);

        $sqlData = "DELETE FROM discuss_subfeature WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function deleteSubFeatureThreads($id, $foreignid)
    {
        $sqlData = "DELETE FROM discuss_thread_subfeature WHERE thread_id=" . $this->dbController->escapeStripString($id) . " AND foreignid=" . $foreignid;

        return $this->dbController->secureGet($sqlData);
    }

    public function deleteFeatureDiscussion($id)
    {
        $sqlData1 = "SELECT foreignid FROM discuss_feature WHERE id=" . $this->dbController->escapeStripString($id);

        $tempEntry = $this->dbController->secureGet($sqlData1);

        if (count($tempEntry) == 0)
            return -1;
        else
            $this->deleteFeatureThreads($id, $tempEntry[0]['foreignid']);

        $sqlData = "DELETE FROM discuss_feature WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function deleteFeatureThreads($id, $foreignid)
    {
        $sqlData = "DELETE FROM discuss_thread_feature WHERE thread_id=" . $this->dbController->escapeStripString($id) . " AND foreignid=" . $foreignid;

        return $this->dbController->secureGet($sqlData);
    }

    public function deleteAuthDiscussion($id)
    {
        $sqlData1 = "SELECT foreignid FROM discuss_auth WHERE id=" . $this->dbController->escapeStripString($id);

        $tempEntry = $this->dbController->secureGet($sqlData1);

        if (count($tempEntry) == 0)
            return -1;
        else
            $this->deleteAuthThreads($id, $tempEntry[0]['foreignid']);

        $sqlData = "DELETE FROM discuss_auth WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function deleteAuthThreads($id, $foreignid)
    {
        $sqlData = "DELETE FROM discuss_thread_auth WHERE thread_id=" . $this->dbController->escapeStripString($id) . " AND foreignid=" . $foreignid;

        return $this->dbController->secureGet($sqlData);
    }


    public function deleteAuthSubthread($id)
    {
        $sqlData = "DELETE FROM discuss_thread_auth WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function deleteFeatureSubthread($id)
    {
        $sqlData = "DELETE FROM discuss_thread_feature WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function deleteSubfeatureSubthread($id)
    {
        $sqlData = "DELETE FROM discuss_thread_subfeature WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function acceptAuthSuggestion($id)
    {
        $sqlData = "UPDATE discuss_auth SET post_threadstatus='accepted' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function acceptFeatureSuggestion($id)
    {
        $sqlData = "UPDATE discuss_feature SET post_threadstatus='accepted' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function acceptSubfeatureSuggestion($id)
    {
        $sqlData = "UPDATE discuss_subfeature SET post_threadstatus='accepted' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function rejecttAuthSuggestion($id)
    {
        $sqlData = "UPDATE discuss_auth SET post_threadstatus='rejected' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function rejectFeatureSuggestion($id)
    {
        $sqlData = "UPDATE discuss_feature SET post_threadstatus='rejected' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function rejectSubfeatureSuggestion($id)
    {
        $sqlData = "UPDATE discuss_subfeature SET post_threadstatus='rejected' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);
    }

    public function getLatestAuthThreads($countOfEntrys)
    {
        $sqlData = "SELECT DISTINCT foreignid, post_date as times FROM discuss_auth ORDER BY post_date DESC LIMIT " . $this->dbController->escapeStripString($countOfEntrys);

        return $this->dbController->secureGet($sqlData);
    }


    public function getLatestFeatureThreads($countOfEntrys)
    {
        $sqlData = "SELECT DISTINCT foreignid, post_date as times FROM discuss_feature ORDER BY post_date DESC LIMIT " . $this->dbController->escapeStripString($countOfEntrys);

        return $this->dbController->secureGet($sqlData);
    }

    public function getLatestSubfeatureThreads($countOfEntrys)
    {
        $sqlData = "SELECT DISTINCT foreignid, post_date as times FROM discuss_subfeature ORDER BY post_date DESC LIMIT " . $this->dbController->escapeStripString($countOfEntrys);

        return $this->dbController->secureGet($sqlData);
    }
}

?>
