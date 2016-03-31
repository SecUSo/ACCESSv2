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
 * Class FeatureSet
 * @desc
 * A feature set is a combination of a name (as string) and an attribute checked.
 * The attribute checked stores whether the feature/subfeature with the corresponding
 * name is checked or not.
 */
class FeatureSet
{
    public $name;
    public $checked;
}

/**
 * Class FeatureSet
 * @desc
 * This class is the preparation for the decision making of the platform. It builds an array of categories, features
 * and subfeatures and their description. This array will
 */
class DecisionController
{
    private $dbController;

    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    /**
     * @desc
     * Creates an array of all the categories, it's features and their subfeatures.
     * This will be used for the creation of the decision making template, from
     * which the configuration for the decision making is made.
     * @return array - the result array, containing all the categories, it's features and their subfeatures.
     */
    public function getIndexContent()
    {
        //$sqlData = "SELECT category, feature, subfeature FROM category_feature_subfeature";
        $sqlData = "SELECT   category
                            ,feature
                            ,subfeature
                            ,(SELECT id FROM cat_features WHERE name=feature) as feature_id
                            , (	IF(
                                    (
                                        (SELECT COUNT(*) FROM cat_class_feature WHERE cat_feature=(feature_id) AND name='0')
                                        =0
                                    ),
                                    IF ((( SELECT COUNT(*) FROM cat_subfeatures WHERE feature=feature_id) < (SELECT COUNT(*) FROM cat_class_feature WHERE cat_feature = feature_id)),
                                        '3',
                                        '0'),
                                    IF( (
                                            (SELECT COUNT(*) FROM cat_subfeatures WHERE feature=feature_id)
                                            =1
                                        ),
                                        '1',
                                        '2'
                                    )
                                )
                            ) as status
                    FROM category_feature_subfeature
                    UNION
                        SELECT DISTINCT
                            cfs.category as category
                            ,cfs.feature as feature
                            ,ccf.name as subfeature
                            ,(SELECT id FROM cat_features WHERE name=feature) as feature_id
                            ,IF( (
                                            (SELECT COUNT(*) FROM cat_subfeatures WHERE feature=feature_id)
                                            =1
                                        ),
                                        '1',
                                        '2'
                                    ) as status
                        FROM category_feature_subfeature cfs
                        JOIN cat_features cf
                        ON cf.name=cfs.feature
                        JOIN cat_class_feature ccf
                        ON ccf.cat_feature=cf.id
                        WHERE ccf.name='0'
                    ORDER BY category,feature,subfeature ASC";
        $categoriesArray = $this->dbController->secureGet($sqlData);
        $output = array();
        foreach($categoriesArray as $row){
            $output[$row['category']][$row['feature']][$row['subfeature']] = $row['status'];
        }
        return $output;
    }

    /**
     * @desc
     * Gets all the authentication methods  and it's descriptions from the database and returns them
     * as an array.
     * @return array - an array of authentication methods of features and the corresponding descriptions
     */
    public function getAuthenticationDescriptions()
    {
        $output = array();
        $query = "
            SELECT 
                a.name as name,
                i.description as description
            FROM
                auth_authentications a
            LEFT JOIN auth_info i
            ON i.id=a.id
                ";

        $result = $this->dbController->secureGet($query);

        foreach($result as $row){
            $output[$row['name']] = $row['description'];
        }

        return $output;
    }

    /**
     * @desc
     * Gets all the features and it's description from the database and returns it
     * as an array.
     * @return array - an array of names of features and the corresponding descriptions
     */
    public function getFeatureDescriptions()
    {
        $output = array();
        $query = "
            SELECT 
                f.name as name,
                i.description as description
            FROM
                cat_features f
            LEFT JOIN feature_info i
            ON i.id=f.id
                ";

        $result = $this->dbController->secureGet($query);

        foreach($result as $row){
            $output[$row['name']] = $row['description'];
        }

        return $output;
    }

    /**
     * @desc
     * Gets all the subfeatures and it's description from the database and returns it
     * as an array.
     * @return array - an array of names of subfeatures and the corresponding descriptions
     */
    public function getSubFeatureDescriptions()
    {
        $output = array();
        $query = "
            SELECT 
                f.name as name,
                i.description as description
            FROM
                cat_subfeatures f
            LEFT JOIN subfeature_info i
            ON i.id=f.id
                ";

        $result = $this->dbController->secureGet($query);

        foreach($result as $row){
            $output[$row['name']] = $row['description'];
        }

        return $output;
    }

    /**
     * @desc
     * Simply gets an array of categories from the database.
     * @return array - an array of all the categories of the platform
     */
    public function getCategories()
    {
        $sqlData = "SELECT id, name FROM cat_categories";

        return $this->dbController->secureGet($sqlData);
    }

    /**
     * @desc
     * Takes a category  and returns an array of features contained in this category.
     * @param $category - the category for which to get the containing features from the database
     * @return array - the resulting array of features for the category
     */
    public function getFeatures($category)
    {
        $sqlData = "SELECT id, category, name FROM cat_features WHERE category=" . $this->dbController->escapeStripString($category);

        return $this->dbController->secureGet($sqlData);
    }

    /**
     * @desc
     * Takes a feature and returns an array of subfeatures contained in this feature.
     * @param $feature - the feature for which to get the containing subfeatures from the database
     * @return array - the resulting array of subfeatures for the feature
     */
    public function getSubfeatures($feature)
    {
        $sqlData = "SELECT id, name, feature FROM cat_subfeatures WHERE feature=" . $this->dbController->escapeStripString($feature);

        return $this->dbController->secureGet($sqlData);
    }

    /**
     * @desc
     * Takes a selection of classes and gets an array of authentication methods containing it's performance values and
     * features according to the class selection. Only authentication methods within the classes are selected.
     *
     * @param $classSelection - is an array of features as key and class (of the feature) as value
     * @return null - is a multidimensional array with an authentication method as key and an array as value (with features
     *                  as key and a performance (of the feature) as value)
     */
    public function getAuthSystemPerformances($classSelection)
    {
        $result = array();
        $query = "SELECT
                    aa.name AS auth_name,
                    cf.name AS feat_name,
                    ccf.name AS class_name,
                    ap.value AS performance_val

                    FROM authentication_feature_class afc
                      JOIN auth_authentications aa
                        ON afc.auth_authentication = aa.id
                      JOIN cat_features cf
                        ON afc.cat_feature = cf.id
                      JOIN cat_class_feature ccf
                        ON afc.cat_class_feature = ccf.id
                      JOIN auth_performances ap
                        ON afc.auth_authentication = ap.auth_authentication AND afc.cat_feature = ap.cat_feature

                    ORDER BY auth_name, feat_name asc";

        //print_r($query);
        $queryResult = $this->dbController->secureGet($query);

        $tempOutput = array();

        foreach($queryResult as $row)
        {
            $tempOutput[$row['auth_name']][$row['feat_name']]['performance'] = $row['performance_val'];
            $tempClassNameArray = explode('+', $row['class_name']); // BOOOOOOOOOOOOOOM!
            sort($tempClassNameArray);
            $tempClassName = implode('+', $tempClassNameArray); // SORRY TOO LATE, WE'RE DUMB xD
            $tempOutput[$row['auth_name']][$row['feat_name']]['class_name'] = $tempClassName;

        }

        //print_r($tempOutput);

        foreach($tempOutput as $authName => $featureArray)
        {
            $tempBool = true;

            foreach ($classSelection as $inputFeatureName => $inputClassName) {
                if ($inputClassName != $featureArray[$inputFeatureName]['class_name'] && $inputClassName != '+') {
                    $tempBool = false;
                    break;
                }
            }
            if ($tempBool)
            {
                foreach ($featureArray as $featureName => $classArray)
                {
                    $result[$authName][$featureName] = $classArray['performance'];
                }
            }
        }



        //print_r($result);

        return $result;
    }

    /**
     * @desc
     * This function gets a configuration of categories, features and subfeatures and returns an
     * evaluation result, according to the AHP calculation method.
     *
     * @param $decisionConfiguration - the current configuration of the user for the decision making
     * @return array - array with the results of the evaluation of the decision making
     */
    public function getDecisionResult($decisionConfiguration)
    {
        //print_r($decisionConfiguration);
        // make fancy things here
        $tempResult = array();
        $result = array();

        foreach ($decisionConfiguration as $featureArray)
        {
            //$tempResult[] = $featureArray;
            foreach (array_keys($featureArray) as $keyname)
            {
                $tempFeatureSet = new FeatureSet();
                $tempFeatureSet->name = $keyname;
                if($featureArray[$keyname][0] == 1)
                {
                    $tempFeatureSet->checked = true;
                } else {
                    $tempFeatureSet->checked = false;
                }

                $tempResult[] = $tempFeatureSet;
            }

            $result = array_merge($result, $this->calculatePriorityValuesForCategory($tempResult));
            unset($tempResult);
            $tempResult = array();

        }

        //print_r($tempResult);
        //print_r($result);

        $inputData = array();

        foreach ($decisionConfiguration as $featureArray)
        {
            foreach (array_keys($featureArray) as $featureName)
            {
                $tempArray = $featureArray[$featureName][1];
                $tempString = "";
                $tempStringArray = array();
                foreach ($tempArray as $subFeatName => $value)
                {
                    if ($value == 1)
                    {
                        $tempStringArray[] = $subFeatName;
                    }
                }
                if (sizeof($tempStringArray) > 0)
                {
                    sort($tempStringArray);
                    foreach ($tempStringArray as $classNameSubstring)
                    {
                        $tempString .= $classNameSubstring.'+';
                    }
                    $tempString = substr($tempString, 0, -1);
                } else {
                    $tempString = '+';
                }

                $inputData[$featureName] = $tempString;
                unset($tempStringArray);

            }

        }

        $authSystemPerformances = $this->getAuthSystemPerformances($inputData);

        //print_r($authSystemPerformances);

        $output = array();

        foreach($authSystemPerformances as $authName => $featureArray)
        {
            $tempVal = 0;

            foreach($featureArray as $authFeatureName => $dbPerformance)
            {
                $tempVal += $dbPerformance * $result[$authFeatureName];
            }

            $output[$authName] = $tempVal;
        }

        //sort($output);

        array_reverse($output);

        arsort($output);

        //print_r($output);

        return $output;
    }

    /**
     * @desc
     * Algorithm for the calculation of the priority values of a category
     * 0. Declare and initiate a counter c with value 0 and a counter elem with value 0
     * 1. Determine the length l of the given array of features of our category
     * 2. The first element (either checked or not) gets the value l
     * 3. If the next feature f is not selected:
     *      If the feature f - 1 (i.e. the feature before) is unselected:
     *          If the feature f - 1 has a value:
     *              The current feature f at position p gets the value of the feature f - 1
     *          If the feature f - 1 has no value:
     *              Add 1 to elem
     *              Add l - p to c
     *
     *      If the feature f - 1 (i.e. the feature before) is selected:
     *          Add 1 to elem
     *          Add l - p to c
     *
     *    If the next feature f is selected:
     *      For every unchecked element before
     *          If it has no value:
     *              Set it's value to c / elem
     *          If it has a value:
     *              proceed
     *      The current feature f at list position p gets as value l - the current position p, where l is the lenght of the list
     *      The counter c will be set back to 0
     *      The counter elem will be set back to 0
     *
     * 4. Calculate the overall sums by normalizing the values (= value of position p divided by l)
     *
     * @param $featureArray - numeric array of type FeatureSet
     *
     * @return $result - associative array containing the feature name as key and it's priority value as value
     */
    public function calculatePriorityValuesForCategory($featureArray)
    {
        //print_r($featureArray);
        $c = 0;
        $elem = 0;
        $result = array();
        $uncheckedElements = array();
        $lastElement = null;

        $l = sizeof($featureArray);

        foreach($featureArray as $position => $featureSet)
        {
            if ($position == 0)
            {
                $result[$featureSet->name] = $l;
                $lastElement = $featureSet;
                continue;
            }

            if ($featureSet->checked)
            {
                $result[$featureSet->name] = $l - $position;
                if ($elem > 0)
                {
                    $valueForUncheckedElements = $c / sizeof($uncheckedElements);
                    foreach($uncheckedElements as $element)
                    {
                        $result[$element->name] = $valueForUncheckedElements;
                    }
                    unset($uncheckedElements);
                    $uncheckedElements = array();
                }
                $c = 0;
                $elem = 0;
                $lastElement = $featureSet;
            } else {
                if (!($lastElement->checked) && array_key_exists($lastElement->name, $result))
                {
                    $result[$featureSet->name] = $result[$lastElement->name];
                    $lastElement = $featureSet;
                } else {
                    $uncheckedElements[]=$featureSet;
                    $c += $l - $position;
                    $elem += 1;
                    $lastElement = $featureSet;
                }
            }

        }

        if ($elem > 0)
        {
            $valueForUncheckedElements = $c / sizeof($uncheckedElements);
            foreach($uncheckedElements as $element)
            {
                $result[$element->name] = $valueForUncheckedElements;
            }
        }

        $baseForNormalization = array_sum($result);
        foreach ($result as $position => $value)
        {
            $result[$position] = $value / $baseForNormalization;
        }
        return $result;
    }

    /**
     * logDecision($json)
     * @desc Logs the DecisionRequest JSON-String in the Database with a Hash to determine duplicate Entries
     * @param $json - The JSON-String of the Decision Request
     */
    public function logDecision($json)
    {
        $query = "INSERT INTO
                    decision_log
                    (jsondata, jsonhash, quantity)
                  VALUES
                    ('%s', '%s', 1)
                  ON DUPLICATE KEY UPDATE
                    quantity = quantity + 1
                 ";

        $jsonHash = md5($json);

        $query = sprintf(
            $query,
            $json,
            $jsonHash
        );

        $this->dbController->secureSet($query);
    }

}

?>














