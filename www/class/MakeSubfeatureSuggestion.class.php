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


/**
 * Class MakeSubfeatureSuggestion
 * @desc API to insert subfeature suggestions
 */
class MakeSubfeatureSuggestion
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

        //get subfeature id
        $subfeature_id = $this->suggestionController->getSubFeatureID($this->jsonArr["subfeature"]);

        //open discussion
        $discussion_id = $this->discussionController->addSubFeatureSuggestionForDiscussion(
            $this->jsonArr["scheme"],
            $this->jsonArr["subfeature"],
            $this->jsonArr["value"],
            $this->jsonArr["references"],
            $this->jsonArr["bibtex"],
            $this->jsonArr["comment"],
            $this->sessionController->getId()
        );

        //store subfeature suggestion
        $this->suggestionController->insertSingleSubfeatureSuggestion(
            $this->jsonArr["scheme"],
            $subfeature_id,
            $this->jsonArr["value"],
            $discussion_id,
            $this->sessionController->getId()
        );
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $this->jsonArr = json_decode($_POST['json_data'], true);
        }
    }
}

?>
