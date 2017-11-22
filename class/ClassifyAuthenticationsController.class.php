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
 * Class ClassifyAuthenticationsController
 * @desc Controller used by the AdminClassify Pages.
 * @var $dbController - The Database-Controller
 */
class ClassifyAuthenticationsController
{
    private $dbController;

    /**
     * ClassifyAuthenticationsController constructor.
     */
    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    /**
     * getIndexContent()
     * @desc Function to get all Categories and their related Features of the System.
     * @return array - Multidimensional Array that holds the Output:
     *                  Array (
     *                          [Category-Name] => Array (
     *                                                      [0] => "Feature-Name",
     *                                                      [1] => "Feature-Name",
     *                                                      ..
     *                                                   ),
     *                          [Category-Name] => Array (..),
     *                          ..
     *                        )
     */
    public function getIndexContent()
    {
        $query = "SELECT DISTINCT category, feature FROM `category_feature_subfeature` ORDER BY category, feature ASC";
        $result = $this->dbController->secureGet($query);

        $output = array();

        foreach ($result as $row) {
            $output[$row['category']][] = $row['feature'];
        }

        return $output;
    }

    /**
     * getClassesContent($featureName)
     * @desc Gets the Class-Names of a selected Feature
     * @param $featureName - The Name of the selected Feature
     * @return array - Array that holds all Class-Names of the selected Feature
     *                  Array (
     *                          [0] => "Class-Name",
     *                          [1] => "Class-name",
     *                          ..
     *                        )
     */
    public function getClassesContent($featureName)
    {
        $query = "SELECT
                    name as className
                  FROM
                    `cat_class_feature`
                  WHERE
                     cat_feature=(SELECT DISTINCT id FROM `cat_features` WHERE name='%s')
                 ";

        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($featureName)
        );

        $result = $this->dbController->secureGet($query);

        $output = array();

        foreach ($result as $row) {
            $output[] = $row['className'];
        }

        return $output;
    }

    /**
     * getTableContent($className, $featureName)
     * @desc Gets the Classification Values of all Authentication Schemes of a selected Class in a selected Feature.
     * @param $className - The selected Class-Name
     * @param $featureName - The selected Feature-Name
     * @return array - Multidimensional Array that holds the Class-Names and their related Classification Values
     *                  Array (
     *                          [Authentication-Name] => Array (
     *                                                              [Authentication-Name] => Classification-Value,
     *                                                              [Authentication-Name] => Classification-Value,
     *                                                              ..
     *                                                          ),
     *                          [Authentication-Name] => Array (..),
     *                          ..
     *                        )
     */
    public function getTableContent($className, $featureName)
    {
        $featureID = $this->getFeatureID($featureName);
        $classID = $this->getClassID($className, $featureID);
        $authIDs = $this->getAuthIDs($classID);
        $authIDNames = $this->getAuthIDNames($authIDs);
        $knownScaleValues = $this->getKnownScaleValues($classID, $featureID);
        $temp = $authIDNames;
        $output = array();

        while (sizeof($temp) >= 1) {
            $authName = array_shift($temp);
            $output[$authName] = array();
            foreach ($temp as $auth2Name) {
                $output[$authName][$auth2Name] = 1;
            }
        }

        foreach ($knownScaleValues as $auth1 => $arr) {
            foreach ($arr as $auth2 => $val) {
                $output[$authIDNames[$auth1]][$authIDNames[$auth2]] = $val;
            }
        }

        return $output;
    }

    /**
     * getClassID($className, $featureID)
     * @desc Gets the ID of a Class in a Feature
     * @param $className - The Class-Name
     * @param $featureID - The Feature-ID
     * @return int - The ID of the ClassName in the Feature with the Feature-ID
     */
    private function getClassID($className, $featureID)
    {
        $query = "SELECT id FROM cat_class_feature WHERE name='%s' AND cat_feature=%d LIMIT 1";

        $query = sprintf(
            $query,
            str_replace(" ", "+", $this->dbController->escapeStripString($className)),
            $this->dbController->escapeStripString($featureID)
        );

        $result = $this->dbController->secureGet($query);

        return $result[0]['id'];
    }


    public function getClassIDAndNameBySubFeatureNames($subFeatureArray)
    {
        $query = "SELECT name FROM `cat_subfeatures` WHERE ";
        foreach ($subFeatureArray as $subfeature)
            $query .= "name='" . $this->dbController->escapeStripString($subfeature) . "' OR ";

        //Remove last OR from query
        $query = substr($query, 0, -3);

        $query .= "ORDER by id ASC;";

        $sortedSubfeatureList = $this->dbController->secureGet($query);

        $className = "";

        foreach ($sortedSubfeatureList as $subfeature)
            $className .= $subfeature["name"] . "+";

        //Remove last + from className
        $className = substr($className, 0, -1);

        if (sizeof($subFeatureArray) == 0)
            return array();

        $query = "SELECT id,name FROM cat_class_feature WHERE name='%s' LIMIT 1;";

        $query = sprintf($query, $className);

        $result = $this->dbController->secureGet($query);
        if (sizeof($result) > 0)
            return $result[0];
        else
            return array("new_classname" => $className);
    }

    public function getZeroClassForFeatureId($featureId)
    {
        $query = "SELECT id,name FROM cat_class_feature WHERE name= '0' AND cat_feature=%d;";

        $query = sprintf($query, $this->dbController->escapeStripString($featureId));

        $result = $this->dbController->secureGet($query);
        if (sizeof($result) > 0)
            return $result[0];
        else
            return array();
    }


    /**
     * getFeatureID($featureName)
     * @desc Gets the ID of a Feature-Name from the Database
     * @param $featureName - The Name of the Feature
     * @return int - The ID of the Feature
     */
    public function getFeatureID($featureName)
    {
        $query = "SELECT id FROM cat_features WHERE name='%s' LIMIT 1";

        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($featureName)
        );

        $result = $this->dbController->secureGet($query);

        return $result[0]['id'];
    }

    /**
     * getAuthIDs($classID)
     * @desc Gets the Authentication IDs of the Authentication Schemes in a Class
     * @param $classID - The Class-ID
     * @return array - Array that holds all Authentication Scheme IDs of the Class
     *                  Array (
     *                          [0] => Authentication-ID,
     *                          [1] => Authentication-ID,
     *                          ...
     *                        )
     */
    private function getAuthIDs($classID)
    {
        $query = "SELECT auth_authentication FROM authentication_feature_class WHERE cat_class_feature=%d ORDER BY auth_authentication ASC";

        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($classID)
        );

        $result = $this->dbController->secureGet($query);

        $output = array();

        foreach ($result as $row) {
            $output[] = $row['auth_authentication'];
        }

        return $output;
    }

    /**
     * getAuthIDNames($authIDs)
     * @desc - Gets the Names of Authentication Scheme IDs
     * @param $authIDs - An Array that holds Authentication Scheme IDs
     * @return array - An Array that references Authentication Scheme IDs to Authentication Scheme Names
     *                  Array (
     * +                          [Authentication Scheme ID] => "Authentication Scheme Name",
     *                          [Authentication Scheme ID] => "Authentication Scheme Name",
     *                          ...
     *                        )
     */
    private function getAuthIDNames($authIDs)
    {
        $query = "SELECT id, name FROM auth_authentications WHERE id IN(%s) ORDER BY name ASC";
        $values = "";

        foreach ($authIDs as $authID) {
            $values .= "%d,";
            $values = sprintf(
                $values,
                $this->dbController->escapeStripString($authID)
            );
        }

        $values = substr($values, 0, -1);
        $query = sprintf($query, $values);
        $result = $this->dbController->secureGet($query);
        $output = array();

        foreach ($result as $row) {
            $output[$row['id']] = $row['name'];
        }

        return $output;
    }

    /**
     * getAuthNameIDs($authNames)
     * @desc Gets the Authentication Scheme IDs of given Authentication Scheme Names
     * @param $authNames - An Array that contains Authentication Scheme Names
     * @return array - An Array that references Authentication Scheme Names to Authentication Scheme IDs
     *                  Array (
     *                          ["Authentication-Scheme-Name"] => Authentication-Scheme-ID,
     *                          ["Authentication-Scheme-Name"] => Authentication-Scheme-ID,
     *                          ...
     *                        )
     */
    private function getAuthNameIDs($authNames)
    {
        $query = "SELECT id, name FROM auth_authentications WHERE name IN(%s) ORDER BY name ASC";
        $values = "";

        foreach ($authNames as $authName) {
            $values .= "'%s',";
            $values = sprintf(
                $values,
                $this->dbController->escapeStripString($authName)
            );
        }

        $values = substr($values, 0, -1);
        $query = sprintf($query, $values);
        $result = $this->dbController->secureGet($query);
        $output = array();

        foreach ($result as $row) {
            $output[$row['name']] = $row['id'];
        }

        return $output;
    }


    /**
     * getKnownScaleValues($classID, $featureID)
     * @desc Gets Authentication Schemes and their referenced Scale-Values in a Class of a Feature.
     * @param $classID - The ID of the Class
     * @param $featureID - The ID of the Feature
     * @return array - Multidimensional Array that contains Authentication Scheme Names and their related Scale-Values.
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
    private function getKnownScaleValues($classID, $featureID)
    {
        $query = "SELECT auth_authentication_1, auth_authentication_2, value
                  FROM cat_class_authentications_value_pair
                  WHERE cat_feature=%d AND cat_class_feature=%d
                  ORDER BY auth_authentication_1 ASC";

        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($featureID),
            $this->dbController->escapeStripString($classID)
        );

        $result = $this->dbController->secureGet($query);
        $output = array();

        foreach ($result as $row) {
            $output[$row['auth_authentication_1']][$row['auth_authentication_2']] = $row['value'];
        }

        return $output;
    }


    /**
     * setClassificationValues($class, $feature, $scaleValues)
     * @desc Sets the Scale Values between Authentication Schemes for a Class in a Feature
     * @param $class - The Class Name
     * @param $feature - The Feature Name
     * @param $scaleValues - An Multidimensional Array that holds the Scale-Values.
     *                 Array (
     *                              [Authentication-Scheme-Name] => Array (
     *                                                                      [Authentication-Scheme-Name] => Scale-Value,
     *                                                                      [Authentication-Scheme-Name] => Scale-Value,
     *                                                                      ...
     *                                                                    ),
     *                              [Authentication-SchemeName] => Array ( .. ),
     *                              ..
     *                        )
     */
    public function setClassificationValues($class, $feature, $scaleValues)
    {

        if(count($scaleValues) <= 1)
            return;

        $featureName = $feature;
        $featureID = $this->getFeatureID($featureName);
        $className = $class;
        $classID = $this->getClassID($className, $featureID);
        $authNames = array();
        $authNames[] = array_keys($scaleValues)[0];



        foreach ($scaleValues[array_keys($scaleValues)[0]] as $authName => $val) {
            $authNames[] = $authName;
        }

        $authNameIDs = $this->getAuthNameIDs($authNames);

        $query = "INSERT INTO cat_class_authentications_value_pair
                    (cat_feature, cat_class_feature, auth_authentication_1, auth_authentication_2, value)
                  VALUES %s
                  ON DUPLICATE KEY UPDATE value=VALUES(value)
                 ";

        $values = "";

        foreach ($scaleValues as $auth1name => $cArr) {
            foreach ($cArr as $auth2name => $val) {
                $values .= "(%d,%d,%d,%d,%f),";
                $values = sprintf(
                    $values,
                    $featureID,
                    $classID,
                    $authNameIDs[$this->dbController->escapeStripString($auth1name)],
                    $authNameIDs[$this->dbController->escapeStripString($auth2name)],
                    $this->dbController->escapeStripString($val)
                );
            }
        }

        $values = substr($values, 0, -1);
        $query = sprintf($query, $values);

        $this->dbController->secureSet($query);
    }

}

?>