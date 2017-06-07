<?php
/**
 * #####################################################################################################################
 * Copyright (C) 2017   Christian Mancosu, Christian Engelbert, Philip Stumpf
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
 * Class DecisionEvaluation
 * @desc
 * This class gets the result, i.e. the evaluation, of the decision making process by calling the respective functions
 * on Decision Controller and returns it to the calling class (DecisionMaking.class).
 */
class DecisionEvaluation
{
	private $sessionController;
	private $decisionController;

    private $featureArray;
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

		$output = json_encode($this->decisionController->getDecisionResult($this->featureArray, $this->subfeatureArray, $this->subfeatureOrArray));
		$output .= '#';
		$output .= json_encode($this->decisionController->getAuthenticationDescriptions());
        $output .= '#';
        $output .= json_encode($this->decisionController->getPerformances($this->compareArray));
        $output .= '#';
        $output .= json_encode($this->decisionController->getDecisionResult($this->featureArray, $this->compareArray, $this->emptyArray));
        $output .= '#';
        $output .= json_encode($this->decisionController->getFails($this->featureArray, $this->subfeatureArray, $this->subfeatureOrArray));


		echo $output;
	}

	/**
	 * Get the parameters for the decision making.
	 */
	private function getParams()
	{
		if (isset($_POST["features"]))
		{
            $this->featureArray = json_decode($_POST["features"], true);
            $this->subfeatureArray = json_decode($_POST["subfeatures"], true);
            $this->subfeatureOrArray = json_decode($_POST["subfeaturesor"], true);
            $this->compareArray = json_decode($_POST["compares"], true);
		}
	}

}

?>