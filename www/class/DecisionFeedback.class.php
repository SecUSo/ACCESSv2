<?php
/**
 * #####################################################################################################################
 * Copyright (C) 2017  Philip Stumpf
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
 * Class Decision Feedback
 * @desc
 * This Class gets live Feedback from Decision Controller for given Input. Calls Respective Functions to give for example
 * Counts of Matching Features.
 */
class DecisionFeedback
{
    private $sessionController;
    private $decisionController;

    private $subfeatureArray;
    private $subfeatureOrArray;
    private $compareArray;
    private $emptyArray = array();

    /**
     * DecisionEvaluation constructor.
     */
    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->decisionController = new DecisionController();
        $this->getParams();

        // Get Count of Matching Features
        $output = json_encode($this->decisionController->getResultCount($this->emptyArray, $this->subfeatureArray, $this->subfeatureOrArray));
        $output .= '#';
        $output .= json_encode($this->decisionController->getResultCount($this->emptyArray, $this->compareArray, $this->emptyArray));
        echo $output;
    }

    /**
     * Get the parameters for the decision making.
     */
    private function getParams()
    {
        if (isset($_POST["subfeatures"]))
        {
            $this->subfeatureArray = json_decode($_POST["subfeatures"], true);
            $this->subfeatureOrArray = json_decode($_POST["subfeaturesor"], true);
            $this->compareArray = json_decode($_POST["compares"], true);
        }
    }

}
?>