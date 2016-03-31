
<?php

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
 * Class DecisionEvaluation
 * @desc
 * This class gets the result, i.e. the evaluation, of the decision making process
 * and returns it to the calling class (DecisionMaking.class).
 */
class DecisionEvaluation
{
	private $sessionController;
	private $decisionController;

	private $jsonArray;

	/**
	 * DecisionEvaluation constructor.
	 */
	public function __construct()
	{
		$this->sessionController = new SessionController();
		$this->decisionController = new DecisionController();
		$this->getParams();
		$jsonString = json_encode($this->jsonArray);
		$this->decisionController->logDecision($jsonString);
		$output = json_encode($this->decisionController->getDecisionResult($this->jsonArray));
		$output .= '#';
		$output .= json_encode($this->decisionController->getAuthenticationDescriptions());
		echo $output;
	}

	/**
	 * Get the parameters for the decision making.
	 */
	private function getParams()
	{
		if (isset($_POST["json"]))
		{
			$this->jsonArray = json_decode($_POST["json"], true);
		}
	}

}

?>