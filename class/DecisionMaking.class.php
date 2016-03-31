<?
/**
 * #####################################################################################################################
 * Copyright (C) 2016   Christian Mancosu, Christian Engelbert
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
 * Class DecisionMaking
 * @desc
 * This class simply initiates the template for the decision making platform.
 * Contents are inserted by embedding of the file decision.php.
 *
 */
class DecisionMaking
{
    private $sessionController;
    /**
     * @desc
     * This function initiates the template for the Decision Platform.
     * After the session handling, the decision.php file containing
     * all the relevant fields for the decision making is loaded.
     */
    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - DECISION"; // title must be dynamically changeable!
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();


        $list_item_id = "";
        $li_content_string = "";
        $list_item_string = "<li id=\"{$list_item_id}\">{$li_content_string}<\\li>";

        // get all the categories, features and subfeatures from database
        $cat_feat_subfeat = $this->decisionController->getIndexContent();

        // Get all Descriptions for the Features
        $data_featureDescriptions = $this->decisionController->getFeatureDescriptions();

        // Get all Descriptions for the SubFeatures
        $data_subFeatureDescriptions = $this->decisionController->getSubFeatureDescriptions();

        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/decision.php"); // contents are loaded in this file
        include_once("content/footer.php");
    }

    private $decisionController;

    /**
     * DecisionMaking constructor.
	 * @desc
     * Create a session and a decision controller for the communication with the backend.
     */
    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->decisionController = new DecisionController();
        $this->initTemplate();
    }
}

?>
