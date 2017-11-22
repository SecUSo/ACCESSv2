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
 * Class MakeClassificationSuggestion
 * @desc API to insert classification suggestions
 */
class MakeClassificationSuggestion
{
    private $userController;
    private $sessionController;
    private $authController;
    private $suggestionController;
    private $discussionController;
    private $jsonArr;

    public function __construct()
    {
        $this->userController = new UserController();
        $this->sessionController = new SessionController();
        $this->authController = new AuthenticationsController();
        $this->suggestionController = new SuggestionController();
        $this->discussionController = new DiscussionController();

        if (!$this->sessionController->isSessionValid())
            die("error: no access!");

        $this->getParams();

        $featureId = $this->suggestionController->getFeatureID($this->jsonArr["feature"]);
        $classId = $this->suggestionController->getClassID($this->jsonArr["class"], $featureId);

        //get all changed values
        $fixed_classvalues = $this->suggestionController->filterSuggestionClassvalues($featureId, $classId, $this->jsonArr["classvalues"]);

        //nothing changed?
        if (count($fixed_classvalues) <= 0)
            return;

        //open discussion
        $discussion_id = $this->discussionController->addClassificationSuggestionForDiscussion(
            $this->jsonArr["scheme"],
            $this->suggestionController->getAuthNameById($this->jsonArr["scheme"]),
            $this->jsonArr["feature"],
            $this->jsonArr["class"],
            $fixed_classvalues,
            $this->jsonArr["references"],
            $this->jsonArr["comment"],
            $this->sessionController->getId()
        );

        //store main classification data
        $suggestion_id = $this->suggestionController->insertSingleClassificationSuggestion(
            $this->jsonArr["scheme"],
            $featureId,
            $classId,
            $discussion_id,
            $this->sessionController->getId()
        );

        //store all classvalues for current classification suggestion
        foreach ($fixed_classvalues as $classvalue) {
            $this->suggestionController->insertSingleClassificationSuggestionValue(
                $suggestion_id,
                $featureId,
                $classId,
                $this->suggestionController->getAuthID($classvalue["auth_1"]),
                $this->suggestionController->getAuthID($classvalue["auth_2"]),
                $classvalue["value"]
            );
        }
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $this->jsonArr = json_decode($_POST['json_data'], true);
        }
    }
}

?>
