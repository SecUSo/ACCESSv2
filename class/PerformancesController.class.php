<?php
/**
 * #####################################################################################################################
 * Copyright (C) 2016   Christian Engelbert
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
 * Class PerformancesController
 * @desc Controller for the AdminPerformances Classes.
 * @var $dbController - The Database-Controller
 */
class PerformancesController
{
    private $dbController;

    /**
     * PerformancesController constructor.
     */
    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    /**
     * getIndexContent($featureName)
     * @desc Gets all Authentications and their Scale Values for a selected Feature.
     * @param $featureName - The Name of the Feature
     * @return array - Multidimensional Array that holds Authentication-Scheme Names and their Scale-Values.
     *                  Array (
     *                          [Authentication-Scheme-Name] => Array (
     *                                                                  [Authentication-Scheme-Name] => Scale-Value,
     *                                                                  [Authentication-Scheme-Name] => Scale-Value,
     *                                                                  ...
     *                                                                ),
     *                          [Authentication-SchemeName] => Array ( .. ),
     *                          ..
     *                        )
     */
    public function getIndexContent($featureName)
    {
        $query = "SELECT auth_authentication_1, auth_authentication_2, value
                  FROM feature_authentications_value
                  WHERE cat_feature=%d
                  ORDER BY auth_authentication_1, auth_authentication_2 ASC";
        $query = sprintf(
            $query,
            $this->getFeatureID($featureName)
        );
        $result = $this->dbController->secureGet($query);
        $output = array();

        foreach($result as $row){
            $output[$row['auth_authentication_1']][$row['auth_authentication_2']] = $row['value'];
        }

        return $output;
    }

    /**
     * getIndexHeaders()
     * @desc Gets all Authentication Scheme Names of the System.
     * @return array - Array that holds all Authentication Scheme Names
     *                  Array (
     *                          [0] => "Authentication-Scheme-Name",
     *                          [1] => "Authentication-Scheme-Name",
     *                          ...
     *                        )
     */
    public function getIndexHeaders()
    {
        $query = "SELECT name FROM auth_authentications ORDER BY id ASC";
        $result = $this->dbController->secureGet($query);
        $output = array();

        foreach($result as $row){
            $output[] = $row['name'];
        }

        return $output;
    }

    /**
     * getPerformances($tableContent)
     * @desc Gets the Performances for the Authentication Schemes calculated by their Scale-Values.
     * @param $tableContent - Multidimensional Array that holds Authentication-Scheme-IDs and their Scale-Values.
     * @return array - Array that contains Authentication-Scheme-IDs as Index Values and the Performances as Values.
     *                  Array (
     *                          [Authentication-Scheme-ID] => Performance-Value,
     *                          [Authentication-Scheme-ID] => Performance-Value,
     *                          ..
     *                        )
     */
    public function getPerformances($tableContent)
    {
        $bj_values = array();
        $bj_rowValues = array();
        $numberOfAuths = 0;
        $rowSums = array();

        foreach($tableContent as $row => $arr){
            foreach($arr as $col => $val){
                $bj_rowValues[$col][] = $val;
            }
            $bj_values[$row] = 0;
        }

        $numberOfAuths = sizeof($tableContent);

        foreach($bj_rowValues as $col => $arr){
            foreach($arr as $val){
                $bj_values[$col] += $val;
            }
        }

        foreach($tableContent as $row => $arr){
            $temp = 0;
            foreach($arr as $col => $val){
                $temp += $val;
            }
            $rowSums[$row] = $temp / $numberOfAuths;
        }

        return $rowSums;
    }

    /**
     * getConsistency($tableContent, $performances)
     * @desc Computes the Consistency Ratio with Scale-Values and Performance-Values
     * @param $tableContent - The Scale-Values
     * @param $performances - The Performance-Values
     * @return string - An Output-String with the Consistency Ratio
     *                  "CR = [Consistency-Index] / [RI-Value] = [Consistency-Index/RI-Value]"
     */
    public function getConsistency($tableContent, $performances)
    {
        $bj_values = array();
        $bj_rowValues = array();
        $numberOfAuths = sizeof($performances);
        $col_cis = array();
        $lambdaVal = 0;

        foreach($tableContent as $row => $arr){
            foreach($arr as $col => $val){
                $bj_rowValues[$col][] = $val;
            }
            $bj_values[$row] = 0;
        }

        foreach($bj_rowValues as $col => $arr){
            foreach($arr as $val){
                $bj_values[$col] += $val;
            }
        }

        foreach($bj_values as $col => $val){
            $col_cis[$col] = $val*$performances[$col]/100;
        }

        foreach($col_cis as $ci){
            $lambdaVal += $ci;
        }

        $ci = ($lambdaVal - $numberOfAuths) / ($numberOfAuths - 1);
        $ri = $this->getNearestRI($numberOfAuths);

        return "CR = " .
                number_format($ci, 2, '.', '') . "% / " .
                number_format($ri, 2, '.', '') . "% = " .
                number_format($ci/$ri*100, 2, '.', '') . "%";
    }

    /**
     * setPerformances($featureName, $performances)
     * @desc Stores Performance-Values for a given Feature in the Database
     * @param $featureName - The Feature-Name
     * @param $performances - Array that holds the Performance-Values
     */
    public function setPerformances($featureName, $performances)
    {
        $featureID = $this->getFeatureID($featureName);

        $query = "INSERT INTO
                    auth_performances (auth_authentication, cat_feature, value)
                  VALUES
                    %s
                  ON DUPLICATE KEY UPDATE
                    value=VALUES(value)";

        $values = "";

        foreach($performances as $auth => $val){
            $values .= "(%d, %d, %f),";
            $values = sprintf(
                $values,
                $this->dbController->escapeStripString($auth),
                $featureID,
                $this->dbController->escapeStripString($val)
            );
        }

        $values = substr($values, 0, -1);
        $query = sprintf($query, $values);

        $this->dbController->secureSet($query);
    }

    /**
     * getNearestRI($value)
     * @desc Gets the approximation of an RI Value for a given Dimension of a n x n Matrix
     * @param $value - The Dimension n of the Matrix
     * @return float - The RI-Value
     */
    private function getNearestRI($value)
    {
        $query = "SELECT
                    ci_value,
                    `order`,
                    ABS(`order` - %d) as distance
                  FROM
                    avg_ci_donegan_dodd
                  ORDER BY distance
                  LIMIT 2";

        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($value)
        );

        $result = $this->dbController->secureGet($query);

        $riValueApprox = min($result[0]['ci_value'], $result[1]['ci_value']) +
                        (
                            min($result[0]['distance'], $result[1]['distance']) *
                            (   abs($result[0]['ci_value'] - $result[1]['ci_value']) /
                                ($result[0]['distance'] + $result[1]['distance'])
                            )
                        );

        return $riValueApprox;
    }

    /**
     * getFeatureID($featureName)
     * @desc Gets the ID of a given Feature-Name from the Database
     * @param $featureName - The Feature-Name
     * @return int - The ID of the Feature
     */
    private function getFeatureID($featureName)
    {
        $query = "SELECT id FROM cat_features WHERE name='%s' LIMIT 1";
        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($featureName)
        );

        $result = $this->dbController->secureGet($query);

        return $result[0]['id'];
    }

}
?>