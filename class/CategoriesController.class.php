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
 * Class CategoriesController
 * @desc - A Controller Class needed for several Classes - It has functions to get an modify Class-, Feature- and
 *          SubFeature-related data from the Database
 * @var $dbController : The Database Controller
 */
class CategoriesController
{
    private $dbController;

    /**
     * CategoriesController constructor.
     */
    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    /**
     * getIndexContent()
     * @return array - An Array with the Categories, Features and SubFeatures of the System.
     *  Array(
     *          Category-Name => Array(
     *                                  Feature-Name => Array(
     *                                                          [0] => SubFeature-Name
     *                                                          [1] => SubFeature-Name
     *                                                          ...
     *                                                       )
     *                                  Feature-Name => Array(...)
     *                                  ...
     *                               )
     *          Category-Name => Array(...)
     *          ...
     *       )
     * @desc Used for the "Add/Change Categories" Admin Page.
     */
    public function getIndexContent()
    {
        $sqlQuery = "SELECT category, feature, subfeature FROM category_feature_subfeature";
        $categoriesArray = $this->dbController->secureGet($sqlQuery);
        $output = array();
        foreach ($categoriesArray as $row) {
            $output[$row['category']][$row['feature']][] = $row['subfeature'];
        }
        return $output;
    }

    /**
     * getScaleValuesContent
     * @return array - An Array with all Categories and the Features of the System.
     *  Array(
     *          Category-Name => Array(
     *                                  [0] => Feature-Name
     *                                  [1] => Feature-Name
     *                                  ...
     *                                )
     *          Category-Name => Array(...)
     *          ...
     *       )
     * @desc Used for the "Change Scale Values" Admin Page
     */
    public function getScaleValuesContent()
    {
        $sqlQuery = "SELECT DISTINCT category, feature FROM category_feature_subfeature";
        $categoriesArray = $this->dbController->secureGet($sqlQuery);
        $output = array();
        foreach ($categoriesArray as $row) {
            $output[$row['category']][] = $row['feature'];
        }
        return $output;
    }

    /**
     * getScaleValuesContentForFeature($featureName)
     * @param $featureName - The Name of a Feature
     * @return array - An Array containing Class-Names of the Feature and their relating Scale-Values
     *  Array(
     *          Class-Name => Array(
     *                              Class-Name => Scale-Value
     *                              Class-Name => Scale-Value
     *                              ...
     *                             )
     *          Class-Name => Array(...)
     *          ...
     *       )
     * @desc This Function will return an Associative Array with the Scale-Values from the Database the Scale-Value
     *          has the value 1 if it is stored in the Database or if the Scale-Value is not in the Database.
     */
    public function getScaleValuesContentForFeature($featureName)
    {
        $data = $this->getAuthenticationsSubFeaturesForFeature($featureName);
        $outputArray = array();
        $classNames = array();

        foreach ($data as $authId => $Arr) {
            $temp = "";
            foreach ($Arr as $subFeatureId => $subFeatureName) {
                $temp .= $subFeatureName . '+';
            }
            $temp = substr($temp, 0, -1);
            if (strlen($temp) == 0) {
                $temp = '0';
            }
            array_push($classNames, $temp);
        }
        sort($classNames, SORT_STRING);

        // Pre-Build the Output-Array
        while (sizeof($classNames) > 1) {
            $class1name = array_shift($classNames);
            $outputArray[$class1name] = array();

            foreach ($classNames as $class2name) {
                $outputArray[$class1name][$class2name] = 1;
            }

        }

        $scaleValues = $this->getExistingScaleValuesForFeature($featureName);
        foreach ($scaleValues as $class1name => $cArr) {
            foreach ($cArr as $class2name => $value) {
                $outputArray[$class1name][$class2name] = $value;
            }
        }

        return $outputArray;
    }

    /**
     * setScaleValues($feature, $data)
     * @param $feature - The Feature-Name the Scale-Values in Data are related to
     * @param $data - An Associative Array that has Class-Names an Scale-Values related to the Classes
     *  Array(
     *          Class-Name => Array(
     *                              Class-Name => Scale-Value
     *                              Class-Name => Scale-Value
     *                              ...
     *                             )
     *          Class-Name => Array(...)
     *          ...
     *       )
     * @desc This Function will store Scale-Values related to a Feature in the Database.
     */
    public function setScaleValues($feature, $data)
    {
        $featureID = $this->getFeatureID($feature);
        // Array to hold all the Class Names for the Feature
        $classNames = array();

        // As we just look at the first row of the Table from the Front-End we first push the Row-Name into the Array
        array_push($classNames, array_keys($data)[0]);

        // Then we push all related names of the Title-Column in the Array
        // The Array should now have every Class-Name for the Feature
        foreach ($data[trim($classNames[0])] as $name => $val) {
            array_push(
                $classNames,
                $this->dbController->escapeStripString($name)
            );
        }

        $this->setClasses($featureID, $classNames);
        $classNameToId = $this->getClassIDsForFeature($featureID);

        $insertScalesQuery = "
            INSERT INTO
              `cat_class_value_pair`
              (cat_class_class_1, cat_class_class_2, value)
            VALUES
              %s
            ON DUPLICATE KEY
              UPDATE
            value=VALUES(value)
            ";

        $insertScalesValues = "";

        foreach ($data as $class1name => $arr) {
            foreach ($arr as $class2name => $value) {
                $insertScalesValues .= "(%d,%d,%f),";
                $insertScalesValues = sprintf(
                    $insertScalesValues,
                    $this->dbController->escapeStripString($classNameToId[trim($class1name)]),
                    $this->dbController->escapeStripString($classNameToId[trim($class2name)]),
                    $this->dbController->escapeStripString($value)
                );
            }
        }

        $insertScalesValues = substr($insertScalesValues, 0, -1);
        $insertScalesQuery = sprintf(
            $insertScalesQuery,
            $insertScalesValues
        );

        $this->dbController->secureSet($insertScalesQuery);
    }

    /**
     * getClassIDsForFeature($featureID)
     * @param $featureID - The ID of a Feature
     * @return array - An Array that has all known Class-Names and IDs for the requested Feature-ID
     *  Array(
     *          Class-Name => Class-ID
     *          Class-Name => Class-ID
     *          ...
     *       )
     */
    public function getClassIDsForFeature($featureID)
    {
        // Query to get the IDs of the Class-Names of the Feature we either just added to the Database or already were in
        $getClassIdsQuery = "SELECT id, name FROM `cat_class_feature` WHERE cat_feature=%s";
        $getClassIdsQuery = sprintf(
            $getClassIdsQuery,
            $this->dbController->escapeStripString($featureID)
        );
        $getClassIdsResult = $this->dbController->secureGet($getClassIdsQuery);

        $output = array();

        foreach ($getClassIdsResult as $row) {
            $output[$row['name']] = $row['id'];
        }

        return $output;
    }

    /**
     * getFeatureID($feature)
     * @param $feature - The Name of a Feature
     * @return int - The ID of the requested Feature
     * @desc Gets and returns the ID of the requested Feature from the Database.
     */
    public function getFeatureID($feature)
    {
        // Query to get the ID of the Feature the Scale Values are related to
        $featureIdQuery = "SELECT id FROM `cat_features` WHERE name='%s' LIMIT 1";
        $featureIdQuery = sprintf(
            $featureIdQuery,
            $this->dbController->escapeStripString($feature)
        );

        // Get the Feature-ID
        $featureId = $this->dbController->secureGet($featureIdQuery);

        return $featureId[0]['id'];
    }

    /**
     * getExistingScaleValuesForFeature($featureName)
     * @param $featureName - The Name of a Feature
     * @return array - An Array with all existing Scale-Values for the requested Feature.
     *  Array(
     *          Class-Name => Array(
     *                              Class-Name => Scale-Value
     *                              Class-Name => Scale-Value
     *                              ...
     *                             )
     *          Class-Name => Array(...)
     *          ...
     *       )
     * @desc Gets and returns all Scale-Values that are stored in the Database for a requested Feature-Name.
     */
    public function getExistingScaleValuesForFeature($featureName)
    {
        $query = "
            SELECT
              ccf1.name AS class1name,
              ccf2.name AS class2name,
              ccvp.value AS value
            FROM cat_class_value_pair ccvp
              JOIN cat_class_feature ccf1 ON ccvp.cat_class_class_1 = ccf1.id
              JOIN cat_class_feature ccf2 ON ccvp.cat_class_class_2 = ccf2.id
            WHERE ccvp.cat_class_class_1 IN(
              SELECT ccf.id
              FROM cat_class_feature ccf
              WHERE ccf.cat_feature = (
                SELECT cf.id
                FROM cat_features cf
                WHERE cf.name='%s'
              )
            )
            ORDER BY ccf1.name ASC
            ";

        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($featureName)
        );
        $result = $this->dbController->secureGet($query);
        $output = array();

        foreach ($result as $row) {
            $output[$row['class1name']][$row['class2name']] = $row['value'];
        }

        return $output;
    }

    /**
     * getAuthenticationsSubFeaturesForFeature($featureName)
     * @param $featureName - The Name of a Feature
     * @return array - An Array that contains Authentications and their SubFeatures.
     *  Array(
     *          Authentication-ID => Array(
     *                                      SubFeature-ID => SubFeature-Name
     *                                      SubFeature-ID => SubFeature-Name
     *                                      ...
     *                                    )
     *          Authentication-ID => Array(...)
     *          ...
     *        )
     * @desc Gets a Feature-Name and returns all Authentication Schemes of the System with the SubFeatures they have.
     */
    public function getAuthenticationsSubFeaturesForFeature($featureName)
    {
        // Query to get all Authentications and their SubFeatures for the requested Feature
        $query = "
            SELECT
                a.id AS AId,
                aas.cat_subfeature AS SfId,
                cs.name AS SfName
            FROM
                auth_authentications a
            LEFT JOIN
                (auth_subfeatures aas
                  JOIN (cat_subfeatures cs
                    JOIN cat_features cf
                    ON cf.id=cs.feature AND cf.name='%s')
                  ON cs.id=aas.cat_subfeature)
            ON a.id=aas.auth_authentication
            ORDER BY AId, SfId ASC
            ";

        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($featureName)
        );

        $result = $this->dbController->secureGet($query);
        $output = array();

        foreach ($result as $row) {
            $output[$row['AId']][$row['SfId']] = $row['SfName'];
        }

        return $output;
    }

    /**
     * setClasses($featureID, $classes)
     * @param $featureId - The ID of a Feature
     * @param $classes - An Array of Class-Names
     *  Array(
     *          [0] => Class-Name
     *          [1] => Class-Name
     *          ...
     *       )
     * @desc Inserts the given Class-Names in the Database corresponding to the given Feature-ID
     */
    public function setClasses($featureId, $classes)
    {
        // Query to insert Classes into the Database is they are not already in
        $insertClassesQuery = "
            INSERT IGNORE INTO
              `cat_class_feature`
              (`name`, `cat_feature`)
            VALUES
              %s
            ";

        $insertClassesValues = "";

        // Build up the Insert-Values for the Query with the Array that has all Class-Names and the Feature-ID from the Database
        foreach ($classes as $className) {
            $insertClassesValues .= "('%s',%d),";
            $insertClassesValues = sprintf(
                $insertClassesValues,
                $this->dbController->escapeStripString($className),
                $this->dbController->escapeStripString($featureId)
            );
        }

        $insertClassesValues = substr($insertClassesValues, 0, -1);
        $insertClassesQuery = sprintf($insertClassesQuery, $insertClassesValues);

        // Set the Class-Names in the Database
        $this->dbController->secureSet($insertClassesQuery);
    }

    /**
     * deleteCategories($catNames)
     * @param $catNames - An Array containing Category-Names
     *  Array(
     *          [0] => Category-Name
     *          [1] => Category-Name
     *          ...
     *       )
     * @desc Deletes all Categories from the Database that are NOT in the Input
     */
    public function deleteCategories($catNames)
    {
        // Delete all Categories, not needed anymore
        $deleteCatQuery = "DELETE FROM `cat_categories` WHERE name NOT IN(%s)";
        $deleteCatIn = "";

        foreach ($catNames as $catName) {
            $deleteCatIn .= "'%s',";
            $deleteCatIn = sprintf(
                $deleteCatIn,
                $this->dbController->escapeStripString($catName)
            );
        }

        $deleteCatIn = substr($deleteCatIn, 0, -1);
        $deleteCatQuery = sprintf($deleteCatQuery, $deleteCatIn);

        $this->dbController->secureSet($deleteCatQuery);
    }

    /**
     * deleteFeatures($featNames)
     * @param $featNames - An Array containing Feature-Names
     *  Array(
     *          [0] => Feature-Name
     *          [1] => Feature-Name
     *          ...
     *       )
     * @desc Deletes all Features from the Database that are NOT in the Input.
     */
    public function deleteFeatures($featNames)
    {
        // Delete all the Features, not needed anymore
        $deleteFeatureQuery = "DELETE FROM `cat_features` WHERE name NOT IN(%s)";
        $deleteFeatureIn = "";

        foreach ($featNames as $featureName) {
            $deleteFeatureIn .= "'%s',";
            $deleteFeatureIn = sprintf(
                $deleteFeatureIn,
                $this->dbController->escapeStripString($featureName)
            );
        }

        $deleteFeatureIn = substr($deleteFeatureIn, 0, -1);
        $deleteFeatureQuery = sprintf($deleteFeatureQuery, $deleteFeatureIn);

        $this->dbController->secureSet($deleteFeatureQuery);
    }

    /**
     * deleteSubFeatures($subFeatNames)
     * @param $subFeatNames - An Array containing SubFeature-Names
     *  Array(
     *          [0] => SubFeature-Name
     *          [1] => SubFeature-Name
     *          ...
     *        )
     * @desc Deletes all SubFeatures that are NOT in the Input.
     */
    public function deleteSubFeatures($subFeatNames)
    {
        // Delete all the SubFeatures, not needed anymore
        $deleteSubQuery = "DELETE FROM `cat_subfeatures` WHERE name NOT IN(%s)";
        $deleteSubIn = "";

        foreach ($subFeatNames as $subFeatureName) {
            $deleteSubIn .= "'%s',";
            $deleteSubIn = sprintf(
                $deleteSubIn,
                $this->dbController->escapeStripString($subFeatureName)
            );
        }

        $deleteSubIn = substr($deleteSubIn, 0, -1);
        $deleteSubQuery = sprintf($deleteSubQuery, $deleteSubIn);

        $this->dbController->secureSet($deleteSubQuery);
    }

    /**
     * insertCategories($categoryNames)
     * @param $categoryNames - An Array containing Category-Names
     *  Array(
     *          [0] => Category-Name
     *          [1] => Category-Name
     *          ...
     *       )
     * @desc Inserts all Categories given in the Input in the Database.
     */
    public function insertCategories($categoryNames)
    {
        // Insert all the new Categories
        $insertCatQuery = "INSERT IGNORE INTO `cat_categories` (name) VALUES %s";
        $insertCatValues = "";

        foreach ($categoryNames as $categoryName) {
            $insertCatValues .= "('%s'),";
            $insertCatValues = sprintf(
                $insertCatValues,
                $this->dbController->escapeStripString($categoryName)
            );
        }

        $insertCatValues = substr($insertCatValues, 0, -1);
        $insertCatQuery = sprintf($insertCatQuery, $insertCatValues);

        $this->dbController->secureSet($insertCatQuery);
    }

    /**
     * insertFeatures($categoryFeatureNames)
     * @param $categoryFeatureNames - An Array containing Category-Names and Feature-Names
     *  Array(
     *          Category-Name => Array(
     *                                  [0] => Feature-Name
     *                                  [1] => Feature-Name
     *                                  ...
     *                                )
     *          Category-Name => Array(...)
     *          ...
     *       )
     * @desc Inserts all the Features related to their Categories into the Database.
     */
    public function insertFeatures($categoryFeatureNames)
    {
        // Insert all the new Features
        $insertFeatureQuery = "INSERT IGNORE INTO `cat_features` (category, name) VALUES %s";

        // Get Category-Names and their IDs from the Database
        $categoryQuery = "SELECT id, name FROM `cat_categories`";
        $categories = $this->dbController->secureGet($categoryQuery);

        // Array to convert Category-Names to Category-IDs
        $categoryNameToID = array();

        foreach ($categories as $row) {
            $categoryNameToID[$row['name']] = $row['id'];
        }

        $insertFeatureValues = "";

        foreach ($categoryFeatureNames as $categoryName => $feature) {
            foreach ($feature as $featureName) {
                $insertFeatureValues .= "(%d, '%s'),";
                $insertFeatureValues = sprintf(
                    $insertFeatureValues,
                    $categoryNameToID[trim($this->dbController->escapeStripString($categoryName))],
                    $this->dbController->escapeStripString($featureName)
                );
            }
        }

        $insertFeatureValues = substr($insertFeatureValues, 0, -1);
        $insertFeatureQuery = sprintf($insertFeatureQuery, $insertFeatureValues);

        $this->dbController->secureSet($insertFeatureQuery);
    }

    /**
     * insertSubFeatures($featureSubFeatureNames)
     * @param $featureSubFeatureNames - An Array containing Features and SubFeatures
     *  Array(
     *          Feature-Name => Array(
     *                                  [0] => SubFeature-Name
     *                                  [1] => SubFeature-Name
     *                                  ...
     *                               )
     *          Feature-Name => Array(...)
     *          ...
     *       )
     * @desc Inserts all given SubFeatures related to their Features into the Database.
     */
    public function insertSubFeatures($featureSubFeatureNames)
    {
        // Insert all the new SubFeatures
        $insertSubQuery = "INSERT IGNORE INTO `cat_subfeatures` (feature, name) VALUES %s";

        // Query to get all Feature-Names and their IDs from the Database
        $featureQuery = "SELECT id, name FROM `cat_features`";

        // Array to convert Feature-Names to Feature-IDs
        $featureNameToID = array();
        $features = $this->dbController->secureGet($featureQuery);

        foreach ($features as $row) {
            $featureNameToID[$row['name']] = $row['id'];
        }

        $insertSubValues = "";

        foreach ($featureSubFeatureNames as $featureName => $subFeature) {
            foreach ($subFeature as $subFeatureName) {
                $insertSubValues .= "(%d, '%s'),";
                $insertSubValues = sprintf($insertSubValues,
                    $featureNameToID[trim($this->dbController->escapeStripString($featureName))],
                    $this->dbController->escapeStripString($subFeatureName)
                );
            }
        }

        $insertSubValues = substr($insertSubValues, 0, -1);
        $insertSubQuery = sprintf($insertSubQuery, $insertSubValues);

        $this->dbController->secureSet($insertSubQuery);
    }

}

?>