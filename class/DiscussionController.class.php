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

    public function addClassificationSuggestionForDiscussion($auth_authentication, $auth_name, $feature, $class, $class_values, $reference, $comment, $author_id)
    {

        $zeroClassAliases = array(
            "Accessible" => "Non-Accessible",
            "Negligible-Cost-per-User" => "Non-Negligible-Cost-per-User",
            "Browser-Compatible" => "Non-Browser-Compatible",
            "Server-Compatible" => "Non-Server-Compatible",
            "Mature" => "Not-Mature",
            "Non-Proprietary" => "Proprietary",
            "Resilient-to-Physical-Oberservation" => "Non-Resilient-to-Physical-Oberservation",
            "Resilient-to-Targeted-Impersonation" => "Non-Resilient-to-Targeted-Impersonation",
            "Resilient-to-Throttled-Guessing" => "Non-Resilient-to-Throttled-Guessing",
            "Resilient-to-Unthrottled-Guessing" => "Non-Resilient-to-Unthrottled-Guessing",
            "Resilient-to-Internal-Observation" => "Non-Resilient-to-Internal-Observation",
            "Resilient-to-Leaks-form-Other-Verifiers" => "Non-Resilient-to-Leaks-form-Other-Verifiers",
            "Resilient-to-Phishing" => "Non-Resilient-to-Phishing",
            "Resilient-to-Theft" => "Non-Resilient-to-Theft",
            "Resilient-to-Third-Party" => "Non-Resilient-to-Third-Party",
            "Requiring-Explicit-Consent" => "Non-Requiring-Explicit-Consent",
            "Unlinkable" => "Linkable",
            "Scalable-for-Users" => "Non-Scalable-for-Users",
            "Easy-to-Learn" => "Non-Easy-to-Learn",
            "Efficient-to-Use" => "Non-Efficient-to-Use",
            "Easy-Recovery-from-Loss" => "Non-Easy-Recovery-from-Loss",
            "Infrequent-Errors" => "Frequent-Errors"
        );

        $content = '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Classification Suggestion</h3></div><div class="panel-body">
            <dl class="dl-horizontal"><dt>Feature</dt><dd>' .
            $this->dbController->escapeStripString($feature) .
            '</dd><dt>Class</dt><dd>';

        if ($this->dbController->escapeStripString($class) == "0")
            $content .= $zeroClassAliases[$this->dbController->escapeStripString($feature)];
        else
            $content .= $this->dbController->escapeStripString($class);

        $content .= '</dd><dt>References</dt><dd>' .
            $this->dbController->escapeStripString($reference) .
            '</dd><dt>Comment</dt><dd>' .
            $this->dbController->escapeStripString($comment) .
            '</dd><dt>Classvalues</dt><dd>';

        foreach ($class_values as $class_value) {
            if ($class_value["auth_1"] == $auth_name) {
                if ($this->dbController->escapeStripString($class_value["value"]) == "1.5")
                    $content .= "<span class=\"bg-success\"><b>" . $class_value["auth_1"] . "</b> is better than <b>" . $this->dbController->escapeStripString($class_value["auth_2"]) . "</b></span></br>";
                else if ($this->dbController->escapeStripString($class_value["value"]) == "1")
                    $content .= "<span class=\"bg-warning\"><b>" . $class_value["auth_1"] . "</b> is equal to <b>" . $this->dbController->escapeStripString($class_value["auth_2"]) . "</b></span></br>";
                else
                    $content .= "<span class=\"bg-danger\"><b>" . $class_value["auth_1"] . "</b> is worse than <b>" . $this->dbController->escapeStripString($class_value["auth_2"]) . "</b></span></br>";

            } else {
                if ($this->dbController->escapeStripString($class_value["value"]) == "1.5")
                    $content .= "<span class=\"bg-danger\"><b>" . $class_value["auth_2"] . "</b> is worse than <b>" . $this->dbController->escapeStripString($class_value["auth_1"]) . "</b></span></br>";
                else if ($this->dbController->escapeStripString($class_value["value"]) == "1")
                    $content .= "<span class=\"bg-warning\"><b>" . $class_value["auth_2"] . "</b> is equal to <b>" . $this->dbController->escapeStripString($class_value["auth_1"]) . "</b></span></br>";
                else
                    $content .= "<span class=\"bg-success\"><b>" . $class_value["auth_2"] . "</b> is better than <b>" . $this->dbController->escapeStripString($class_value["auth_1"]) . "</b></span></br>";

            }
        }

        $content .= '</dd></dl></div></div>';


        $sqlData = "INSERT INTO discuss_auth " .
            "(id, foreignid, post_type, post_threadstatus, post_content, post_authorid, post_date) " .
            "VALUES(0," .
            $this->dbController->escapeStripString($auth_authentication) . ", " .
            "'auto-suggestion'," .
            "'" . DiscussionController::defaultThreadStatus . "', " .
            "'" . $content . "'," .
            "'" . $this->dbController->escapeStripString($author_id) . "'," .
            "NOW()" .
            ");";
        $this->dbController->secureSet($sqlData);
        return $this->dbController->getLatestInsertionId();
    }


    public function addSubFeatureSuggestionForDiscussion($auth_authentication, $cat_subfeature, $value, $reference, $comment, $author_id)
    {
        $content = '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Subfeature Suggestion</h3></div><div class="panel-body"><dl class="dl-horizontal"><dt>Subfeature</dt><dd>' .
            $this->dbController->escapeStripString($cat_subfeature) .
            '</dd><dt>Action</dt><dd>';

        if ($this->dbController->escapeStripString($value) == "1")
            $content .= 'Add (+)';
        else
            $content .= 'Remove (-)';

        $content .= '</dd><dt>References</dt><dd>' .
            $this->dbController->escapeStripString($reference) .
            '</dd><dt>Comment</dt><dd>' .
            $this->dbController->escapeStripString($comment) .
            '</dd></dl></div></div>';

        $sqlData = "INSERT INTO discuss_auth " .
            "(id, foreignid, post_type, post_threadstatus, post_content, post_authorid, post_date) " .
            "VALUES(0," .
            $this->dbController->escapeStripString($auth_authentication) . ", " .
            "'auto-suggestion'," .
            "'" . DiscussionController::defaultThreadStatus . "', " .
            "'" . $content . "'," .
            "'" . $this->dbController->escapeStripString($author_id) . "'," .
            "NOW()" .
            ");";
        $this->dbController->secureSet($sqlData);
        return $this->dbController->getLatestInsertionId();
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

        $tempEntry = $this->dbController->secureSet($sqlData1);

        if (count($tempEntry) == 0)
            return -1;
        else
            $this->deleteSubFeatureThreads($id, $tempEntry[0]['foreignid']);

        $sqlData = "DELETE FROM discuss_subfeature WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureSet($sqlData);
    }

    public function deleteSubFeatureThreads($id, $foreignid)
    {
        $sqlData = "DELETE FROM discuss_thread_subfeature WHERE thread_id=" . $this->dbController->escapeStripString($id) . " AND foreignid=" . $foreignid;

        return $this->dbController->secureSet($sqlData);
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

        return $this->dbController->secureSet($sqlData);
    }

    public function deleteFeatureThreads($id, $foreignid)
    {
        $sqlData = "DELETE FROM discuss_thread_feature WHERE thread_id=" . $this->dbController->escapeStripString($id) . " AND foreignid=" . $foreignid;

        return $this->dbController->secureSet($sqlData);
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

        return $this->dbController->secureSet($sqlData);
    }

    public function deleteAuthThreads($id, $foreignid)
    {
        $sqlData = "DELETE FROM discuss_thread_auth WHERE thread_id=" . $this->dbController->escapeStripString($id) . " AND foreignid=" . $foreignid;

        return $this->dbController->secureSet($sqlData);
    }


    public function deleteAuthSubthread($id)
    {
        $sqlData = "DELETE FROM discuss_thread_auth WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureSet($sqlData);
    }

    public function deleteFeatureSubthread($id)
    {
        $sqlData = "DELETE FROM discuss_thread_feature WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureSet($sqlData);
    }

    public function deleteSubfeatureSubthread($id)
    {
        $sqlData = "DELETE FROM discuss_thread_subfeature WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureSet($sqlData);
    }

    public function acceptAuthSuggestion($id)
    {
        $sqlData = "UPDATE discuss_auth SET post_threadstatus='accepted' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureSet($sqlData);
    }

    public function acceptFeatureSuggestion($id)
    {
        $sqlData = "UPDATE discuss_feature SET post_threadstatus='accepted' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureSet($sqlData);
    }

    public function acceptSubfeatureSuggestion($id)
    {
        $sqlData = "UPDATE discuss_subfeature SET post_threadstatus='accepted' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureSet($sqlData);
    }

    public function rejectAuthSuggestion($id)
    {
        $sqlData = "UPDATE discuss_auth SET post_threadstatus='rejected' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureSet($sqlData);
    }

    public function rejectFeatureSuggestion($id)
    {
        $sqlData = "UPDATE discuss_feature SET post_threadstatus='rejected' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureSet($sqlData);
    }

    public function rejectSubfeatureSuggestion($id)
    {
        $sqlData = "UPDATE discuss_subfeature SET post_threadstatus='rejected' WHERE id=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureSet($sqlData);
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
