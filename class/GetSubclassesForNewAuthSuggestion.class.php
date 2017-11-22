<?

/**
 * Class GetSubclassesForNewAuthSuggestion
 * @desc API to get data for a classification suggestion
 */
class GetSubclassesForNewAuthSuggestion
{
    private $sessionController;
    private $classifyAuthenticationsController;
    private $contentController;
    private $jsonArr;

    public function __construct()
    {
        $this->classifyAuthenticationsController = new ClassifyAuthenticationsController();
        $this->sessionController = new SessionController();
        $this->contentController = new ContentController();

        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");

        $this->init();
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $this->jsonArr = json_decode($_POST['json_data'], true);
            return true;
        }
        return false;
    }

    private function init()
    {

        $zeroClassAliases = array(
            "Accessible" => "Non-Accessible",
            "Negligible-Cost-per-User" => "Non-Negligible-Cost-per-User",
            "Server-Compatible" => "Non-Server-Compatible",
            "Browser-Compatible" => "Non-Browser-Compatible",
            "Mature" => "Not-Mature",
            "Non-Proprietary" => "Proprietary",
            "Resilient-to-Physical-Oberservation" => "Non-Resilient-to-Physical-Oberservation",
            "Resilient-to-Targeted-Impersonation" => "Non-Resilient-to-Targeted-Impersonation",
            "Resilient-to-Throttled-Guessing" => "Non-Resilient-to-Throttled-Guessing",
            "Resilient-to-Unthrottled-Guessing" => "Non-Resilient-to-Unthrottled-Guessing",
            "Resilient-to-Internal-Observation" => "Non-Resilient-to-Internal-Observation",
            "Resilient-to-Leaks-form-Other-Verifiers" => "Non-Resilient-to-Leaks-form-Other-Verifiers", // typo needs to be fixed
            "Resilient-to-Phishing" => "Non-Resilient-to-Phishing",
            "Resilient-to-Theft" => "Non-Resilient-to-Theft",
            "Resilient-to-Third-Party" => "Non-Resilient-to-Third-Party",
            "Requiring-Explicit-Consent" => "Non-Requiring-Explicit-Consent",
            "Unlinkable" => "Non-Unlinkable",
            "Scalable-for-Users" => "Non-Scalable-for-Users",
            "Easy-to-Learn" => "Non-Easy-to-Learn",
            "Efficient-to-Use" => "Non-Efficient-to-Use",
            "Infrequent-Errors" => "Frequent-Errors",
            "Easy-Recovery-from-Loss" => "Non-Easy-Recovery-from-Loss"
        );

        $parameters = $this->getParams();

        if (!$parameters)
            die();

        $scheme_descriptions = $this->contentController->getAllDescriptionWithName();

        $tempArray = array();

        foreach ($this->jsonArr as $feature) {
            $processed_subfeatures = [];
            $data_content = [];

            foreach ($feature['subfeatures'] as $subfeature) {
                array_push($processed_subfeatures, $subfeature);
            }

            $featureId = $this->classifyAuthenticationsController->getFeatureID($feature["feature"]);
            $data_content["feature_id"] = $featureId;

            // is it a nullclass?
            if (count($processed_subfeatures) == 0) {
                $classData = $this->classifyAuthenticationsController->getZeroClassForFeatureId($featureId);
                $data_content["feature"] = $feature["feature"];

                //0-class exists
                if (sizeof($classData) > 0) {
                    $data_content["data"] = $this->classifyAuthenticationsController->getTableContent($classData["name"], $feature["feature"]);
                    $data_content["class"] = $zeroClassAliases[$feature["feature"]];
                    $data_content["zeroclass"] = "1";
                }

                array_push($tempArray, $data_content);
                continue;
            }

            $res = ($this->classifyAuthenticationsController->getClassIDAndNameBySubFeatureNames($processed_subfeatures));

            $data_content["feature"] = $feature["feature"];
            $data_content["zeroclass"] = "0";

            // new class - not need anymore, because all classes are initially stored in the knowledge database
            if (array_key_exists('new_classname', $res)) {
                $data_content["new_class"] = $res["new_classname"];
                array_push($tempArray, $data_content);
                continue;
            }
            // regular case - get data
            if (sizeof($res) > 0) {
                $data_content["data"] = $this->classifyAuthenticationsController->getTableContent($res["name"], $feature["feature"]);
                $data_content["class"] = $res["name"];
            }

            array_push($tempArray, $data_content);
        }

        $finalArray = array("classdata" => $tempArray,
            "descriptions" => $scheme_descriptions);

        echo json_encode($finalArray);
    }
}

?>