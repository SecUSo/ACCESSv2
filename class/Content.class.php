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
    private $authenticationFeatureController;
    private $featureController;
    private $classifyAuthenticationsController;
    private $suggestionController;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->contentController = new ContentController();
        $this->discussionController = new DiscussionController();
        $this->authenticationFeatureController = new AuthenticationFeatureController();
        $this->featureController = new FeatureController();
        $this->classifyAuthenticationsController = new ClassifyAuthenticationsController();
        $this->suggestionController = new SuggestionController();
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
        $data_content = $this->authenticationFeatureController->getAuthContent($contentData[0]["name"]); //data for suggestion type 1
        $data_subfeature_descriptions = $this->featureController->getAllSubfeatureDescriptions();
        $data_feature_descriptions = $this->contentController->getAllFeatureDescriptionsWithName();
        $data_timeline = $this->suggestionController->getSuggestionChangelogByAuthId($data_contentId);
        $data_comment_type = "full";

        $selectiveFeaturesWithNullclass = array("Scalable-for-Users",
            "Browser-Compatible",
            "Easy-to-Learn",
            "Easy-Recovery-from-Loss",
            "Negligible-Cost-per-User",
            "Server-Compatible",
            "Browser-Compatible",
            "Non-Proprietary",
            "Resilient-to-Targeted-Impersonation",
            "Resilient-to-Throttled-Guessing",
            "Resilient-to-Unthrottled-Guessing",
            "Resilient-to-Leaks-form-Other-Verifiers", //needs to be fixed *from
            "Resilient-to-Phishing",
            "Resilient-to-Theft",
            "Resilient-to-Third-Party",
            "Requiring-Explicit-Consent",
            "Unlinkable");

        $selectiveFeatures = array("Memorywise-Effortless",
            "Nothing-to-Carry",
            "Physically-Effortless");


        $additiveFeatures = array("Efficient-to-Use",
            "Infrequent-Errors",
            "Accessible",
            "Mature",
            "Resilient-to-Physical-Oberservation", //needs to be fixed *Observation
            "Resilient-to-Internal-Observation");


        /*
        //dirty way of creating a list of correct subclasses for every feature corresponding to the current auth scheme
        $data_feature_list = array();
        $categories = $this->featureController->getCategories();
        for ($iCat = 0; $iCat < count($categories); $iCat++) {
            $category = $categories[$iCat];
            $features = $this->featureController->getFeatures($category['id']);
            for ($iFeature = 0; $iFeature < count($features); $iFeature++) {
                $feature = $features[$iFeature];
                $data_class_content = $this->classifyAuthenticationsController->getClassesContent($feature['name']);
                $subfeatures = $this->featureController->getSubfeatures($feature['id']);

                // start with 1, cause index 0 is the unnecessary zero-class
                for ($iClass = 1; $iClass < count($data_class_content); $iClass++) {
                    $currentClass = explode("+", $data_class_content[$iClass]);
                    $correctClass = true;
                    for ($iSubFeature = 0; $iSubFeature < count($subfeatures); $iSubFeature++) {
                        if ($this->isFeatureInAuthSublist($data_subfeatures, $subfeatures[$iSubFeature]['name']) != $this->isFeatureOfClass($currentClass, $subfeatures[$iSubFeature]['name'])) {
                            $correctClass = false;
                            break;
                        }
                    }
                    if ($correctClass) {
                        $data_feature_list[] = $feature['name'] . ":" . $data_class_content[$iClass];
                        break;
                    }
                }
            }
        }
        */

        $classes = $this->suggestionController->getClassesForAuth($this->contentId);

        $data_feature_list = array();

        /*
        foreach ($classes as $class) {
            if ($class["class"] != "")
                array_push($data_feature_list, $class["feature"] . ":" . $class["class"]);
        }
        */

        $zeroClassAliases = array(
            "Accessible" => "Non-Accessible",
            "Browser-Compatible" => "Non-Browser-Compatible",
            "Negligible-Cost-per-User" => "Non-Negligible-Cost-per-User",
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

        $bOnce = true;

        $firstFeature = "";
        $firstClass = "";

        foreach ($classes as $class) {

            if ($bOnce) {
                $firstFeature = $class["feature"];
                $firstClass = $class["class"];
                $bOnce = false;
            }


            if ($class["class"] != "") {
                if ($class["class"] == "0")
                    $temp_entry = array("feature" => $class["feature"], "class" => $zeroClassAliases[$class["feature"]], "zeroclass" => "1");
                else
                    $temp_entry = array("feature" => $class["feature"], "class" => $class["class"], "zeroclass" => "0");

                array_push($data_feature_list, $temp_entry);
            }
        }

        $data_classes = $this->classifyAuthenticationsController->getTableContent(
            $firstClass,
            $firstFeature
        );


        $data_classes_tableHeader = $this->getTableHeader($data_classes);


        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/content.php");
        include_once("content/footer.php");
        //var_dump($data_feature_list);
    }


    private
    function getTableHeader($data)
    {
        $output = array();
        $output[] = array_keys($data)[0];

        foreach ($data[array_keys($data)[0]] as $name => $val) {
            $output[] = $name;
        }

        return $output;
    }

    private
    function getParams()
    {
        if (isset($_GET['id'])) $this->contentId = $_GET["id"];
    }

    private
    function checkParams()
    {
        if ($this->contentId == '')
            return FALSE;

        return TRUE;
    }

    public
    function getInfo()
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
