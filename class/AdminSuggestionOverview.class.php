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
 * Class AdminSuggestionOverView
 * @desc draw interface for suggestions
 */
class AdminSuggestionOverView
{
    private $sessionController;
    private $suggestionController;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->suggestionController = new SuggestionController();

        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");

        $this->initTemplate();
    }

    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - SUGGESTION OVERVIEW";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_scheme_suggestions = $this->suggestionController->getSchemeSuggestionOverview();
        $data_subfeature_suggestions = $this->suggestionController->getSubfeatureSuggestionOverview();
        $data_classification_suggestions = $this->suggestionController->getClassificationSuggestionOverview();

        //zero class aliases for nullclasses
        $zeroClassAliases = array( //size 22
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
            "Resilient-to-Leaks-form-Other-Verifiers" => "Non-Resilient-to-Leaks-form-Other-Verifiers",
            "Resilient-to-Phishing" => "Non-Resilient-to-Phishing",
            "Resilient-to-Theft" => "Non-Resilient-to-Theft",
            "Resilient-to-Third-Party" => "Non-Resilient-to-Third-Party",
            "Requiring-Explicit-Consent" => "Non-Requiring-Explicit-Consent",
            "Unlinkable" => "Non-Unlinkable",
            "Scalable-for-Users" => "Non-Scalable-for-Users",
            "Easy-to-Learn" => "Non-Easy-to-Learn",
            "Efficient-to-Use" => "Non-Efficient-to-Use",
            "Easy-Recovery-from-Loss" => "Non-Easy-Recovery-from-Loss",
            "Infrequent-Errors" => "Frequent-Errors"
        );

        //append nullclass aliases
        for ($i = 0; $i < count($data_classification_suggestions); $i++) {
            if ($data_classification_suggestions[$i]["class"] == "0") {
                $data_classification_suggestions[$i]["class"] = $zeroClassAliases[$data_classification_suggestions[$i]["feature"]];
            }
        }

        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/suggestionoverview.php");
        include_once("content/footer.php");
    }
}

?>
