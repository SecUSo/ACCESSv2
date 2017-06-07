<?

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
 * DecisionController
 * @desc Providing Main Functionality for ACCESSv2 Decision Making.
 *
 */



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
 * Class DecisionController
 * @desc
 * This class is the Base Class for the Decision Making Process. It gets all Relevant Data from the Database and the
 * User Input from Ajax Requests and Calculates the respective Decision Results. For Detailed Information see
 * Function Documentation.
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
                i.id as idnr
            FROM
                auth_authentications a
            LEFT JOIN auth_info i
            ON i.id=a.id
                ";

        $result = $this->dbController->secureGet($query);

        foreach($result as $row){
            $output[$row['name']] = $row['idnr'];
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
     * @return array - an array of names of Subfeatures and the corresponding descriptions
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

        $queryResult = $this->dbController->secureGet($query);

        $tempOutput = array();

        foreach($queryResult as $row)
        {
            $tempOutput[$row['auth_name']][$row['feat_name']]['performance'] = $row['performance_val'];
            $tempClassNameArray = explode('+', $row['class_name']);
            sort($tempClassNameArray);
            $tempClassName = implode('+', $tempClassNameArray);
            $tempOutput[$row['auth_name']][$row['feat_name']]['class_name'] = $tempClassName;

        }

        foreach($tempOutput as $authName => $featureArray)
        {
            $tempBool = true;

            foreach ($classSelection as $inputFeatureName => $inputClassName) {
                if ($inputClassName != '+') {
                    $inputtemp = explode('+', $inputClassName);
                    $databasetemp = explode('+', $featureArray[$inputFeatureName]['class_name']);
                    foreach ($inputtemp as $key) {
                        if(!(in_array($key,$databasetemp))) {
                            $tempBool = false;
                        }
                    }
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

        return $result;
    }

    /**
     * @desc
     * This function gets a configuration of Features and Two Arrays of Subfeatures (And and Or) and returns the
     * Evaluation Result based on Calculation Method
     *
     * @return array - array with the results of the evaluation of the decision making
     */
    public function getDecisionResult($featureArray, $subFeatureArray, $subFeatureArrayOr)
    {

        $result = $this->calculatePriorityValuesForCategory($featureArray);


        $authSystemPerformances = $this->getAuthSystemPerformances($this->getSubFeatureConfiguration($subFeatureArray));

        if (!empty($subFeatureArrayOr)) {
            foreach ($subFeatureArrayOr as $subFeatName1 => $subFeatPairs) {

                $subFeatureArray2 = $subFeatureArray;
                foreach (array_keys($subFeatureArray) as $featureName) {
                    $tempArray = $subFeatureArray[$featureName][1];
                    foreach ($tempArray as $subFeatName => $value) {
                        if ($subFeatName1 == $subFeatName) {
                            $subFeatureArray2[$featureName][1][$subFeatName] = 1;
                        }
                    }
                }
                $authSystemPerformances1 = $this->getAuthSystemPerformances($this->getSubFeatureConfiguration($subFeatureArray2));

                foreach (array_keys($subFeatPairs[0]) as $subFeatName2) {
                    $subFeatureArray3 = $subFeatureArray;
                    foreach (array_keys($subFeatureArray) as $featureName) {
                        $tempArray = $subFeatureArray[$featureName][1];
                        foreach ($tempArray as $subFeatName => $value) {
                            if ($subFeatName2 == $subFeatName) {
                                $subFeatureArray3[$featureName][1][$subFeatName] = 1;
                            }
                        }
                    }
                    $authSystemPerformances2 = $this->getAuthSystemPerformances($this->getSubFeatureConfiguration($subFeatureArray3));

                    $authSystemPerformancesTEST = array_merge($authSystemPerformances1, $authSystemPerformances2);
                    $authSystemPerformances = array_intersect_key($authSystemPerformances, $authSystemPerformancesTEST);

                }
            }
        }


        $output = array();

        foreach($authSystemPerformances as $authName => $featureArray)
        {
            $tempVal = 0;

            foreach($featureArray as $authFeatureName => $dbPerformance)
            {
                if (isset($result[$authFeatureName])) {
                    $tempVal += $dbPerformance * $result[$authFeatureName];
                }
            }

            $output[$authName] = $tempVal;
        }

        array_reverse($output);

        arsort($output);


        return $output;
    }


    /**
     * @desc
     * This function gets a Set of selected and unselected SubFeatures and returns a combination of the selected
     * Subfeatures as Class Name for Extraction of Database Values
     *
     * @param $subFeatureArray - the current configuration of SubFeatures
     * @return outputData - array with combined, set Subfeatures
     */

    public function getSubFeatureConfiguration($subFeatureArray)
    {
        $outputData = array();
        foreach (array_keys($subFeatureArray) as $featureName)
        {
            $tempArray = $subFeatureArray[$featureName][1];
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

            $outputData[$featureName] = $tempString;
            unset($tempStringArray);

        }


        return $outputData;
    }

    /**
     * @desc
     * This function gets Set of Features and Returns the Perfomances for Given Features from Database, without checking
     * Subfeature Configuration, used mainly for comparison
     *
     * @param $featureArray - the current configuration of the user for the decision making
     * @return $authSystemPerformances - array with the rrespective Perfomances
     */
    public function getPerformances($featureArray)
    {

        $inputData = array();

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

        return $authSystemPerformances = $this->getAuthSystemPerformances($inputData);
    }



    /**
     * @desc
     * Algorithm for the calculation of the priority values of Feature Configuration
     * @param $featureArray - numeric array of type FeatureSet
     * @return output - associative array containing the feature name as key and it's priority value as value
     */
    public function calculatePriorityValuesForCategory($featureArray)
    {

        $input = array();
        foreach (array_keys($featureArray) as $keyname)
        {
            $input[$keyname] = $featureArray[$keyname][0];
        }


        $occurences = array_count_values($input);
        $l = sizeof($input);
        $iterator = 0;
        $output = array();

/*
       // SIMPLE METHOD (Count Bubbles and give all Features in Bubble Same Value starting with highest)
       $maxValue = end($input) + 1;

        foreach ($input as $key => $value) {


            $equals = $occurences[$value];
            for ($i = 0; $i < $equals; $i++) {
                $output[$key] = $maxValue;
            }
            $iterator++;
            if ($iterator == $occurences[$value]) {
                $maxValue = $maxValue - 1;
                $iterator = 0;
            }

        }
*/

        foreach ($input as $key => $value)  {

            $output[$key] = 0;

            $equals = $occurences[$value];
            for ($i = 0; $i < $equals; $i++) {
                $output[$key] =  $output[$key] + ($l - $i);
            }

            $iterator++;
            $output[$key] = $output[$key] / $occurences[$value];
            if ($iterator == $occurences[$value]) {
                $l = $l -  $occurences[$value];
                $iterator = 0;
            }
        }


        $baseForNormalization = array_sum($output);
        foreach ($output as $position => $value)
        {
            $output[$position] = $value / $baseForNormalization;
        }


        return $output;

    }



    /**
     * @desc
     * This function Gets a Array of Features and Subfeatures and Which Authentications fail on HardConstraint
     * for selected KNF-Configuration and returns with the respective HardConstraint
     *
     * @return $authSystemPerformances - array with the failing Authentications and respective HardConstraints
     */

    public function getFails($featureArray, $subFeatureArray, $subFeatureArrayOr)
    {

        $authSystemPerformances = $this->getFailureList($this->getSubFeatureConfiguration($subFeatureArray));


        if (!empty($subFeatureArrayOr)) {
            foreach ($subFeatureArrayOr as $subFeatName1 => $subFeatPairs) {

                $subFeatureArray2 = $subFeatureArray;
                foreach (array_keys($subFeatureArray) as $featureName) {
                    $tempArray = $subFeatureArray[$featureName][1];
                    foreach ($tempArray as $subFeatName => $value) {
                        if ($subFeatName1 == $subFeatName) {
                            $subFeatureArray2[$featureName][1][$subFeatName] = 1;
                        }
                    }
                }
                $authSystemPerformances1 = $this->getFailureList($this->getSubFeatureConfiguration($subFeatureArray2));

                foreach (array_keys($subFeatPairs[0]) as $subFeatName2) {
                    $subFeatureArray3 = $subFeatureArray;
                    foreach (array_keys($subFeatureArray) as $featureName) {
                        $tempArray = $subFeatureArray[$featureName][1];
                        foreach ($tempArray as $subFeatName => $value) {
                            if ($subFeatName2 == $subFeatName) {
                                $subFeatureArray3[$featureName][1][$subFeatName] = 1;
                            }
                        }
                    }
                    $authSystemPerformances2 = $this->getFailureList($this->getSubFeatureConfiguration($subFeatureArray3));

                    $authSystemPerformancesTEST = array_intersect_key($authSystemPerformances1, $authSystemPerformances2);
                    $authSystemPerformances = array_merge($authSystemPerformances, $authSystemPerformancesTEST);

                }
            }
        }


        return $authSystemPerformances;

    }




    /**
     * @desc
     * Helper Function for getFails() which extracts contnet from Database and fetches Fails for single configuration
     *
     * @return $result List of Failing Authentications and HardConstraints
     */

    public function getFailureList($classSelection)
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

        $queryResult = $this->dbController->secureGet($query);

        $tempOutput = array();

        foreach($queryResult as $row)
        {
            $tempOutput[$row['auth_name']][$row['feat_name']]['performance'] = $row['performance_val'];
            $tempClassNameArray = explode('+', $row['class_name']);
            sort($tempClassNameArray);
            $tempClassName = implode('+', $tempClassNameArray);
            $tempOutput[$row['auth_name']][$row['feat_name']]['class_name'] = $tempClassName;

        }

        foreach($tempOutput as $authName => $featureArray)
        {

            foreach ($classSelection as $inputFeatureName => $inputClassName) {
                if ($inputClassName != '+') {
                    $inputtemp = explode('+', $inputClassName);
                    $databasetemp = explode('+', $featureArray[$inputFeatureName]['class_name']);
                    foreach ($inputtemp as $key) {
                        if(!(in_array($key,$databasetemp))) {
                            if (array_key_exists ( $authName , $result )) {
                                $result[$authName] .= ', '.$key;
                            }
                            else {
                                $result[$authName] = $key;
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }



    /**
     * @desc
     * This function Gets a Array of Features and Subfeatures and Returns How Many Authentication Schemes
     * pass the Given Hard Constraints (Subfeatures)
     *
     * @param $decisionConfiguration - the current configuration of the user for the decision making
     * @return array - array with the results of the evaluation of the decision making
     */
    public function getResultCount($featureArray, $subFeatureArray, $subFeatureArrayOr)
    {

        $authSystemPerformances = $this->getAuthSystemPerformances($this->getSubFeatureConfiguration($subFeatureArray));

        if (!empty($subFeatureArrayOr)) {
            foreach ($subFeatureArrayOr as $subFeatName1 => $subFeatPairs) {

                $subFeatureArray2 = $subFeatureArray;
                foreach (array_keys($subFeatureArray) as $featureName) {
                    $tempArray = $subFeatureArray[$featureName][1];
                    foreach ($tempArray as $subFeatName => $value) {
                        if ($subFeatName1 == $subFeatName) {
                            $subFeatureArray2[$featureName][1][$subFeatName] = 1;
                        }
                    }
                }
                $authSystemPerformances1 = $this->getAuthSystemPerformances($this->getSubFeatureConfiguration($subFeatureArray2));

                foreach (array_keys($subFeatPairs[0]) as $subFeatName2) {
                    $subFeatureArray3 = $subFeatureArray;
                    foreach (array_keys($subFeatureArray) as $featureName) {
                        $tempArray = $subFeatureArray[$featureName][1];
                        foreach ($tempArray as $subFeatName => $value) {
                            if ($subFeatName2 == $subFeatName) {
                                $subFeatureArray3[$featureName][1][$subFeatName] = 1;
                            }
                        }
                    }
                    $authSystemPerformances2 = $this->getAuthSystemPerformances($this->getSubFeatureConfiguration($subFeatureArray3));

                    $authSystemPerformancesTEST = array_merge($authSystemPerformances1, $authSystemPerformances2);
                    $authSystemPerformances = array_intersect_key($authSystemPerformances, $authSystemPerformancesTEST);

                }
            }
        }

        $result = count($authSystemPerformances);

        return $result;
    }

    /**
     * logDecision($json)
     * @desc Logs the DecisionRequest JSON-String in the Database with a Hash to determine duplicate Entries
     * @param $json - The JSON-String of the Decision Request
     *
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
    */
}

?>