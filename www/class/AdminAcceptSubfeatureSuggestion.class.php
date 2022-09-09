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
 * Class AdminAcceptSubfeatureSuggestion
 * @desc API to accept subfeature suggestions
 */
class AdminAcceptSubfeatureSuggestion
{
    private $sessionController;
    private $suggestionController;
    private $discussionController;
    private $classifyAuthenticationsController;
    private $performancesController;
    private $suggestionId;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->suggestionController = new SuggestionController();
        $this->discussionController = new DiscussionController();
        $this->classifyAuthenticationsController = new ClassifyAuthenticationsController();
        $this->performancesController = new PerformancesController();

        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");

        $this->getParams();

        //get suggestion data
        $discussion_id = $this->suggestionController->getDataForSubfeatureSuggestionThread($this->suggestionId);

        //add timeline entry
        $this->suggestionController->insertSubfeatureSuggestionChangelog($this->suggestionId);

        //fix consistency of knowledge database
        $this->suggestionController->fixInconsistency();
        $this->suggestionController->setAllClassvalues();

        //accept suggestion
        $this->suggestionController->acceptSubfeatureSuggestion($this->suggestionId);

        //fix consistency of knowledge database
        $this->suggestionController->fixInconsistency();
        $this->suggestionController->setAllClassvalues();

        //close discussion thread
        $this->discussionController->acceptAuthSuggestion($discussion_id);

        //recalculate performance-values
        $this->performancesController->recalculateAllPerformances();

        header('Location: ?AdminSuggestionOverview');
    }

    private function getParams()
    {
        if (isset($_GET['id'])) $this->suggestionId = $_GET["id"];
    }
}
?>
