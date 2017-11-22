<?php
/**
 * #####################################################################################################################
 * Copyright (C) 2016   Thomas Weber
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
 * Class AuthenticationFeatureController
 * @desc Controller for the Classes of the "Change SubFeatures of Authentication Schemes"-Page
 * @var $dbController : The Database-Controller to send SQL-Queries
 */
class SuggestionController
{
    private $dbController;

    /**
     * AuthenticationFeatureController constructor.
     */
    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    /**
     * getIndexContent()
     * @return array - An Array with all Authentication Schemes of the System
     */
    public function getIndexContent()
    {
        // Request all Authentications from the Database
        $requestQuery = "
          SELECT
            name AS name
          FROM `auth_authentications`";

        $authentications = $this->dbController->secureGet($requestQuery);

        // Array to hold the Output
        $output = array();

        // Fill out Array for the Output
        foreach ($authentications as $value) {
            array_push(
                $output,
                $value['name']
            );
        }
        return $output;
    }

    /**
     * getAuthID($authName)
     * @param $authName - The Name of an Authentication Scheme
     * @return int - The ID of the Authentication Scheme
     */
    public function getAuthID($authName)
    {
        // Query to get the ID of the Authentication
        $authenticationIdQuery = "SELECT DISTINCT id FROM `auth_authentications` WHERE name='%s' LIMIT 1";
        $authenticationIdQuery = sprintf(
            $authenticationIdQuery,
            $this->dbController->escapeStripString($authName)
        );

        $authenticationIdResult = $this->dbController->secureGet($authenticationIdQuery);
        // Save the Authentication-ID in this Variable
        return $authenticationIdResult[0]['id'];
    }

    /**
     * getSubFeatureIDs()
     * @return array - An Array with all SubFeature-Names and their corresponding IDs
     *      Array(
     *              SubFeature-Name => SubFeature-ID,
     *              SubFeature-Name => SubFeature-ID,
     *              ...
     *          )
     */
    public function getSubFeatureIDs()
    {
        // Query to get all IDs and Names of the SubFeatures stored in the Database
        $subFeatureIdsQuery = "SELECT id, name FROM `cat_subfeatures`";
        $subFeatureIdsResult = $this->dbController->secureGet($subFeatureIdsQuery);
        // Variable to Convert SubFeature-Names to their IDs
        $subFeatureToId = array();
        foreach ($subFeatureIdsResult as $row) {
            $subFeatureToId[$row['name']] = $row['id'];
        }
        return $subFeatureToId;
    }

    /**
     * deleteAuthSubFeatures($authName, $subFeatureNames)
     * @param $authName - The Name of an Authentication Scheme
     * @param $subFeatureNames - An Array with SubFeature-Names
     * @desc Deletes all Relations Authentication-Scheme-ID -> SubFeature-ID in the Database for the given Params
     */
    public function deleteAuthSubFeatures($authName, $subFeatureNames)
    {
        // Query to delete all SubFeatures the Authentication should no longer have
        $deleteQuery = "
            DELETE IGNORE
              FROM `auth_subfeatures`
                WHERE auth_authentication=%d
                AND cat_subfeature IN(%s)
            ";
        $authID = $this->getAuthID($authName);
        $subFeatureNameToID = $this->getSubFeatureIDs();
        $deleteValues = "";

        foreach ($subFeatureNames as $sfName) {
            $deleteValues .= "'%d',";
            $deleteValues = sprintf(
                $deleteValues,
                $subFeatureNameToID[$this->dbController->escapeStripString($sfName)]
            );
        }

        $deleteValues = substr($deleteValues, 0, -1);
        $deleteQuery = sprintf(
            $deleteQuery,
            $authID,
            $deleteValues
        );
        $this->dbController->secureSet($deleteQuery);
    }

    /**
     * insertAuthSubFeatures($authName, $subFeatureNames)
     * @param $authName - The Name of an Authentication-Scheme
     * @param $subFeatureNames - Array containing SubFeature-Names
     * @desc Inserts Relations Authentication-Scheme-ID -> SubFeatureID into the Database for the given Params
     */
    public function insertAuthSubFeatures($id, $subFeatureNames)
    {
        // Query to insert all SubFeatures the Authentication now should have
        $insertQuery = "
            INSERT IGNORE INTO
                `auth_suggestions_subfeatures`
                  (id, cat_subfeature)
            VALUES
              %s
            ";
        $subFeatureNameToID = $this->getSubFeatureIDs();
        $insertValues = "";

        foreach ($subFeatureNames as $subFeatureName) {
            $insertValues .= "(%d,%d),";
            $insertValues = sprintf(
                $insertValues,
                $id,
                $subFeatureNameToID[$this->dbController->escapeStripString($subFeatureName)]
            );
        }


        $insertValues = substr($insertValues, 0, -1);
        $insertQuery = sprintf(
            $insertQuery,
            $insertValues
        );

        $this->dbController->secureSet($insertQuery);
    }

    /**
     * getAuthContent($authName)
     * @param $authName - The Name of an Authentication Scheme
     * @return array - An Array containing Categories, Features, SubFeatures and a Boolean
     *                  The Boolean is True, if the Authentication Scheme has the SubFeature, else it's False
     *  Array(
     *          Category-Name => Array(
     *                                  Feature-Name => Array(
     *                                                          SubFeature-Name => Boolean
     *                                                          SubFeature-Name => Boolean
     *                                                          ...
     *                                                       )
     *                                  Feature-Name => Array(...)
     *                                  ...
     *                                )
     *          Category-Name => Array(...)
     *          ...
     *       )
     */
    public function getAuthContent($authName)
    {
        $authName = $this->dbController->escapeStripString($authName);

        $requestAllQuery = "
            SELECT
              c.name as category,
              f.name as feature,
              s.id as subfeature_id,
              s.name as subfeature
            FROM
              `cat_subfeatures` s
            JOIN (cat_features f
              JOIN cat_categories c
              ON f.category=c.id)
            ON s.feature=f.id
            ";

        // Query to get the SubFeature-IDs from the Database the requested Authentication has
        $requestSelectedQuery = "
            SELECT
              s.cat_subfeature as subfeature_id
            FROM
              `auth_subfeatures` s
            JOIN auth_authentications a
            ON s.auth_authentication=a.id
            WHERE a.name='%s'";

        $requestSelectedQuery = sprintf(
            $requestSelectedQuery,
            $this->dbController->escapeStripString($authName)
        );

        // Get the Results from the Database
        $allResult = $this->dbController->secureGet($requestAllQuery);
        $selectedResult = $this->dbController->secureGet($requestSelectedQuery);

        // Array to hold the Output
        $resultArray = array();

        // Build the Output
        foreach ($allResult as $row) {
            $resultArray[$row['category']][$row['feature']][$row['subfeature']] = false;

            foreach ($selectedResult as $srow) {
                if ($srow['subfeature_id'] == $row['subfeature_id']) {
                    $resultArray[$row['category']][$row['feature']][$row['subfeature']] = true;
                    break;
                }
            }
        }

        return $resultArray;
    }

    /**
     * getCleanAuthContent()
     * @return 3d array of category, feature, subfeature
     * @desc Get clean data-obj with cat, feature, subfeature for auth. suggestions
     */
    public function getCleanAuthContent()
    {
        $requestAllQuery = "
            SELECT
              c.name as category,
              f.name as feature,
              s.id as subfeature_id,
              s.name as subfeature
            FROM
              `cat_subfeatures` s
            JOIN (cat_features f
              JOIN cat_categories c
              ON f.category=c.id)
            ON s.feature=f.id
            ";

        // Get the Results from the Database
        $allResult = $this->dbController->secureGet($requestAllQuery);

        $resultArray = array();

        // Build the Output
        foreach ($allResult as $row) {
            $resultArray[$row['category']][$row['feature']][$row['subfeature']] = false;
        }

        return $resultArray;
    }

    /**
     * insertSingleAuthenticationSuggestion($name, $category, $description, $suggestion_userid)
     * @param $name - scheme name
     * @param $category - scheme category
     * @param $description - scheme description
     * @param $suggestion_userid - autor userid
     * @return id of inserted suggestion
     * @desc Insert scheme suggestion into database and return insertion id
     */
    public function insertSingleAuthenticationSuggestion($name, $category, $description, $suggestion_userid)
    {
        $insertQuery = "
            INSERT INTO
            `auth_suggestions`
            (name, description, category, suggestion_userid, suggestion_date)
            VALUES ('%s','%s','%s','%d',NOW())
            ";

        $insertQuery = sprintf(
            $insertQuery,
            $this->dbController->escapeStripString($name),
            $this->dbController->escapeStripString($category),
            $this->dbController->escapeStripString($description),
            $this->dbController->escapeStripString($suggestion_userid)
        );

        $this->dbController->secureSet($insertQuery);

        return $this->dbController->getLatestInsertionId();
    }

    /**
     * insertSingleSubfeatureSuggestion($authId, $subfeatureId, $value, $discussion_id, $suggestion_userid)
     * @param $authId - id of authentication scheme
     * @param $subfeatureId - id of subfeature
     * @param $value - value describing if subfeature is getting removed or added
     * @param $discussion_id - id of discussion thread
     * @param $suggestion_userid - autor userid
     * @return id of inserted suggestion
     * @desc Insert subfeature suggestion into database and return insertion id
     */
    public function insertSingleSubfeatureSuggestion($authId, $subfeatureId, $value, $discussion_id, $suggestion_userid)
    {
        $insertQuery = "
            INSERT INTO `subfeature_suggestion`
            (auth_authentication, cat_subfeature, value, discussion_id, suggestion_userid, suggestion_date) 
            VALUES ('%d','%d','%d','%d','%d',NOW())
            ";

        $insertQuery = sprintf(
            $insertQuery,
            $this->dbController->escapeStripString($authId),
            $this->dbController->escapeStripString($subfeatureId),
            $this->dbController->escapeStripString($value),
            $this->dbController->escapeStripString($discussion_id),
            $this->dbController->escapeStripString($suggestion_userid)
        );

        $this->dbController->secureSet($insertQuery);

        return $this->dbController->getLatestInsertionId();
    }

    /**
     * insertSingleClassificationSuggestion($authId, $featureId, $classId, $discussion_id, $suggestion_userid)
     * @param $authId - id of authentication scheme
     * @param $featureId - id of feature
     * @param $classId - id of subfeature-class
     * @param $discussion_id - id of discussion thread
     * @param $suggestion_userid - autor userid
     * @return id of inserted suggestion
     * @desc Insert classification suggestion into database and return insertion id
     */
    public function insertSingleClassificationSuggestion($authId, $featureId, $classId, $discussion_id, $suggestion_userid)
    {
        $insertQuery = "
            INSERT INTO `classification_suggestion`
            (auth_id, feature_id, class_id, discussion_id, suggestion_userid, suggestion_date) 
            VALUES ('%d','%d','%d','%d','%d',NOW())
            ";

        $insertQuery = sprintf(
            $insertQuery,
            $this->dbController->escapeStripString($authId),
            $this->dbController->escapeStripString($featureId),
            $this->dbController->escapeStripString($classId),
            $this->dbController->escapeStripString($discussion_id),
            $this->dbController->escapeStripString($suggestion_userid)
        );

        $this->dbController->secureSet($insertQuery);

        return $this->dbController->getLatestInsertionId();
    }

    /**
     * insertSingleClassificationSuggestionValue($id, $featureId, $classId, $auth_1, $auth_2, $value)
     * @param $id - id for corresponding classification suggestion
     * @param $featureId - id of feature
     * @param $auth_1 - id of first authentication scheme
     * @param $auth_2 - id of second authentication scheme
     * @param $value - rating value
     * @return id of inserted suggestion
     * @desc Insert value-pair for classification suggestion into database and return insertion id
     */
    public function insertSingleClassificationSuggestionValue($id, $featureId, $classId, $auth_1, $auth_2, $value)
    {
        $insertQuery = "
            INSERT INTO `classification_suggestion_values`
            (id, cat_feature, cat_class_feature, auth_authentication_1, auth_authentication_2, value) 
            VALUES ('%d','%d','%d','%d','%d','%f')
            ";

        $insertQuery = sprintf(
            $insertQuery,
            $this->dbController->escapeStripString($id),
            $this->dbController->escapeStripString($featureId),
            $this->dbController->escapeStripString($classId),
            $this->dbController->escapeStripString($auth_1),
            $this->dbController->escapeStripString($auth_2),
            $this->dbController->escapeStripString($value)
        );
        $this->dbController->secureSet($insertQuery);

        return $this->dbController->getLatestInsertionId();
    }

    /**
     * getDataForSubfeatureSuggestionThread($suggestionId)
     * @param $suggestionId - id of subfeature suggestion
     * @return discussion id of first entry
     * @desc Get discussion id for given subfeature suggestion
     */
    public function getDataForSubfeatureSuggestionThread($suggestionId)
    {
        $query = "SELECT discussion_id FROM subfeature_suggestion WHERE id='%d' LIMIT 1";

        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($suggestionId)
        );

        $result = $this->dbController->secureGet($query);

        return $result[0]['discussion_id'];
    }

    /**
     * getDataForClassificationSuggestionThread($suggestionId)
     * @param $suggestionId - id of suggestion
     * @return discussion id of first entry
     * @desc Get discussion id for given classification suggestion
     */
    public function getDataForClassificationSuggestionThread($suggestionId)
    {
        $query = "SELECT discussion_id FROM classification_suggestion WHERE id='%d' LIMIT 1";

        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($suggestionId)
        );

        $result = $this->dbController->secureGet($query);

        return $result[0]['discussion_id'];
    }

    /**
     * getFeatureID($featureName)
     * @param $featureName - name of feature
     * @return id of given feature name
     * @desc Get feature id by name
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
     * getSubFeatureID($subfeatureName)
     * @param $subfeatureName - name of subfeature
     * @return id of given subfeature name
     * @desc Get subfeature id by name
     */
    public function getSubFeatureID($subfeatureName)
    {
        $query = "SELECT id FROM cat_subfeatures WHERE name='%s' LIMIT 1";

        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($subfeatureName)
        );

        $result = $this->dbController->secureGet($query);

        return $result[0]['id'];
    }

    /**
     * getClassID($className, $featureID)
     * @param $className - plaintext classname
     * @param $featureID - id of feature
     * @return id of resulting class
     * @desc Get classId by classname and featureId
     */
    public function getClassID($className, $featureID)
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

    /**
     * getAuthNameIDs($authNames)
     * @param $authNames - array of authentication scheme names
     * @return array with name as index and id as value
     * @desc Get id and name for given authentication scheme names
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
     * insertNewClass($id, $featureId, $className)
     * @param $id - id of new class
     * @param $featureId - feature id of new class
     * @param $className - name of new class
     * @desc Insert new subfeature class into database
     */
    public function insertNewClass($id, $featureId, $className)
    {
        $query = "INSERT INTO auth_suggestions_new_classes
                    (id,name, cat_feature)
                  VALUES ('%d','%s','%d');";

        $query = sprintf($query,
            $this->dbController->escapeStripString($id),
            $this->dbController->escapeStripString($className),
            $this->dbController->escapeStripString($featureId));

        $this->dbController->secureSet($query);

    }
    
    /**
     * setClassificationValuesSuggestion($id, $class, $feature, $scaleValues)
     * @param $id - id of suggestion
     * @param $class - classname
     * @param $feature - plaintext feature name
     * @param $scaleValues - array of scalevalues
     * @desc Insert classvalues into knowledge database, sort by auth name and inverse value if auth2 comes first. If they already exist update value
     */
    public function setClassificationValuesSuggestion($id, $class, $feature, $scaleValues)
    {
        $featureName = $feature;
        $featureID = $this->getFeatureID($featureName);
        $className = $class;
        $classID = $this->getClassID($className, $featureID);
        $authNames = array();


        foreach ($scaleValues[array_keys($scaleValues)[0]] as $authName => $val) {
            $authNames[] = $authName;
        }

        $authNameIDs = $this->getAuthNameIDs($authNames);

        $query = "INSERT INTO auth_suggestions_classification
                    (id,cat_feature, cat_class_feature, auth_authentication_1, auth_authentication_2, value)
                  VALUES %s
                 ";

        $values = "";

        $newAuthKey = key($scaleValues);
        $authNameIDs[$newAuthKey] = -1;

        foreach ($scaleValues[$newAuthKey] as $index => $value) {


            if (strcmp($newAuthKey, $index) < 0)// auth1 = newauth, auth2 = other
            {
                //regular value
                $val = 1;
                if ($value == "3/2")
                    $val = 1.5;
                else if ($value == "2/3")
                    $val = 0.666667;

                $values .= "(%d,%d,%d,%d,%d,%f),";
                $values = sprintf(
                    $values,
                    $id,
                    $featureID,
                    $classID,
                    $authNameIDs[$this->dbController->escapeStripString($newAuthKey)],
                    $authNameIDs[$this->dbController->escapeStripString($index)],
                    $this->dbController->escapeStripString($val)
                );
            } else// auth1 = other, auth2 = newauth
            {
                //invert values, cause its other auth -> new auth
                $val = 1;
                if ($value == "3/2")
                    $val = 0.666667;
                else if ($value == "2/3")
                    $val = 1.5;

                $values .= "(%d,%d,%d,%d,%d,%f),";
                $values = sprintf(
                    $values,
                    $id,
                    $featureID,
                    $classID,
                    $authNameIDs[$this->dbController->escapeStripString($index)],
                    $authNameIDs[$this->dbController->escapeStripString($newAuthKey)],
                    $this->dbController->escapeStripString($val)
                );
            }
        }

        $values = substr($values, 0, -1);
        $query = sprintf($query, $values);

        $this->dbController->secureSet($query);
    }

    /**
     * getKnownScaleValues($classID, $featureID)
     * @param $classID - id of class
     * @param $featureID - id of feature
     * @return 2D Array with auth_1 and auth_2 as index and scalevalue as value
     * @desc Get all scalevalues for a subfeature class within a feature
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
     * getAuthIDs($classID)
     * @param $classID - id of class
     * @return Array with all authentication scheme ids
     * @desc Get all authentication scheme ids by given subfeature classid
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
     * setAllClassvalues()
     * @desc Reset classvalues for classes
     */
    public function setAllClassvalues()
    {
        $classifyAuthenticationsController = new ClassifyAuthenticationsController();

        $classes_and_features = $this->getAllClassesWithFeatures();
        foreach ($classes_and_features as $entry) {
            $data_content = $classifyAuthenticationsController->getTableContent(
                $entry["class_name"],
                $entry["feature_name"]
            );
            $classifyAuthenticationsController->setClassificationValues($entry["class_name"], $entry["feature_name"], $data_content);
        }
    }

    /**
     * fixInconsistencyDeleteList($deleteList)
     * @param $deleteList - array of deleteList entrys (featureId, classId, auth_type, auth_id)
     * @desc Deletes value_pairs by given deleteList entry
     */
    private function fixInconsistencyDeleteList($deleteList)
    {
        foreach ($deleteList as $entry) {
            $query = "DELETE FROM cat_class_authentications_value_pair WHERE cat_feature='%d' AND cat_class_feature='%d' AND auth_authentication_%s='%d';";

            $query = sprintf($query,
                $entry["feature_id"],
                $entry["class_id"],
                $entry["auth_type"],
                $entry["auth_id"]
            );

            // echo $query;
            $this->dbController->secureSet($query);

        }
    }

    /**
     * fixInconsistency()
     * @desc Delete all scalevalue-pairs which are invalid cause one of the two auth-schemes isn't in the corresponding subfeature class anymore
     */
    public function fixInconsistency()
    {
        $classes = $this->getClasses();
        $deleteList = array();

        foreach ($classes as $class) {
            $knownScaleValues = $this->getKnownScaleValues($class["id"], $class["cat_feature"]);
            $authIDs = $this->getAuthIDs($class["id"]);

            foreach ($knownScaleValues as $auth1 => $arr) {
                $found1 = false;
                foreach ($authIDs as $auth) {
                    if ($auth == $auth1) {
                        $found1 = true;
                        break;
                    }
                }
                if (!$found1) {
                    $deleteList[] = array(
                        'class_id' => $class["id"],
                        'feature_id' => $class["cat_feature"],
                        'auth_type' => '1',
                        'auth_id' => $auth1
                    );
                }
                foreach ($arr as $auth2 => $val) {
                    $found2 = false;
                    foreach ($authIDs as $auth) {
                        if ($auth == $auth2) {
                            $found2 = true;
                            break;
                        }
                    }
                    if (!$found2)
                        $deleteList[] = array(
                            'class_id' => $class["id"],
                            'feature_id' => $class["cat_feature"],
                            'auth_type' => '2',
                            'auth_id' => $auth2
                        );
                }
            }
        }
        $this->fixInconsistencyDeleteList($deleteList);
    }

    /**
     * getClasses()
     * @return Array containing all cat_class_features from the database
     * @desc Get all cat_class_features entries
     */
    public function getClasses()
    {
        $query = "SELECT * FROM cat_class_feature;";

        return $this->dbController->secureGet($query);
    }

    /**
     * getSchemeSuggestionOverview()
     * @return overview data for authentication scheme suggestion
     * @desc Get list of active authentication scheme suggestions for overview
     */
    public function getSchemeSuggestionOverview()
    {
        $query = "SELECT auth_suggestions.id,name,description,category,FirstName,LastName,suggestion_date FROM auth_suggestions INNER JOIN users ON auth_suggestions.suggestion_userid=users.Id;";

        return $this->dbController->secureGet($query);
    }

    /**
     * getSubfeatureSuggestionOverview()
     * @return overview data for subfeature suggestion
     * @desc Get list of active subfeature suggestions for overview
     */
    public function getSubfeatureSuggestionOverview()
    {
        $query = "SELECT b.id, b.subfeature, b.scheme, b.auth_id, b.value, b.discussion_id, users.FirstName, users.LastName, b.suggestion_date FROM (SELECT a.id, a.discussion_id, a.auth_authentication AS auth_id, a.value, cat_subfeatures.name AS subfeature, a.scheme, a.suggestion_userid,a.suggestion_date FROM (SELECT subfeature_suggestion.id, subfeature_suggestion.auth_authentication,subfeature_suggestion.discussion_id, auth_authentications.name AS scheme, subfeature_suggestion.value, subfeature_suggestion.cat_subfeature,subfeature_suggestion.suggestion_userid,subfeature_suggestion.suggestion_date FROM `subfeature_suggestion` LEFT JOIN auth_authentications ON subfeature_suggestion.auth_authentication=auth_authentications.id) AS a LEFT JOIN cat_subfeatures ON a.cat_subfeature=cat_subfeatures.id) AS b LEFT JOIN users ON b.suggestion_userid=users.Id;";

        return $this->dbController->secureGet($query);
    }

    /**
     * getClassificationSuggestionOverview()
     * @return overview data for classification suggestion
     * @desc Get list of active classification suggestions for overview
     */
    public function getClassificationSuggestionOverview()
    {
        $query = "SELECT classification_suggestion.id, classification_suggestion.auth_id, classification_suggestion.discussion_id, classification_suggestion.suggestion_date, auth_authentications.name AS scheme, cat_features.name AS feature, cat_class_feature.name AS class, users.LastName, users.FirstName FROM classification_suggestion JOIN users on classification_suggestion.suggestion_userid=users.id JOIN auth_authentications ON classification_suggestion.auth_id=auth_authentications.id JOIN cat_features ON classification_suggestion.feature_id=cat_features.id JOIN cat_class_feature ON classification_suggestion.class_id=cat_class_feature.id;";
        return $this->dbController->secureGet($query);
    }

    /**
     * deleteSubfeatureSuggestion($id)
     * @param $id - if of suggestion
     * @desc Delete subfeature suggestion by id
     */
    public function deleteSubfeatureSuggestion($id)
    {
        $query = "DELETE FROM subfeature_suggestion WHERE id=%d;";
        $query = sprintf($query, $this->dbController->escapeStripString($id));
        $this->dbController->secureSet($query);
    }

    /**
     * deleteClassificationSuggestion($id)
     * @param $id - if of suggestion
     * @desc Delete classification suggestion by id
     */
    public function deleteClassificationSuggestion($id)
    {
        $query = "DELETE FROM classification_suggestion WHERE id=%d;";
        $query = sprintf($query, $this->dbController->escapeStripString($id));
        $this->dbController->secureSet($query);

        $query = "DELETE FROM classification_suggestion_values WHERE id=%d;";
        $query = sprintf($query, $this->dbController->escapeStripString($id));
        $this->dbController->secureSet($query);
    }

    /**
     * deleteSchemeSuggestion($id)
     * @param $id - if of suggestion
     * @desc Delete authentication scheme suggestion by id
     */
    public function deleteSchemeSuggestion($id)
    {
        $query = "DELETE FROM auth_suggestions WHERE id=%d;";
        $query = sprintf($query, $this->dbController->escapeStripString($id));
        $this->dbController->secureSet($query);

        $query = "DELETE FROM auth_suggestions_classification WHERE id=%d;";
        $query = sprintf($query, $this->dbController->escapeStripString($id));
        $this->dbController->secureSet($query);

        $query = "DELETE FROM auth_suggestions_subfeatures WHERE id=%d;";
        $query = sprintf($query, $this->dbController->escapeStripString($id));
        $this->dbController->secureSet($query);

        $query = "DELETE FROM auth_suggestions_new_classes WHERE id=%d;";
        $query = sprintf($query, $this->dbController->escapeStripString($id));
        $this->dbController->secureSet($query);
    }

    /**
     * acceptClassificationSuggestion($id)
     * @param $id - if of suggestion
     * @desc Accept classification suggestion
     */
    public function acceptClassificationSuggestion($id)
    {
        $query = "UPDATE cat_class_authentications_value_pair AS a, (SELECT cat_feature, cat_class_feature, auth_authentication_1, auth_authentication_2, value FROM classification_suggestion_values WHERE id='%d') AS b SET a.value=b.value WHERE a.cat_feature=b.cat_feature AND a.cat_class_feature=b.cat_class_feature AND a.auth_authentication_1=b.auth_authentication_1 AND a.auth_authentication_2=b.auth_authentication_2;";

        $query = sprintf($query,
            $this->dbController->escapeStripString($id));

        $this->dbController->secureSet($query);

        $this->deleteClassificationSuggestion($id);
    }

    /**
     * deleteAllSubfeaturesForAuth($auth_id, $feature_id)
     * @param $auth_id - authentication scheme id
     * @param $feature_id - feature id
     * @desc Delete all subfeatures of an authentication scheme within a feature
     */
    private function deleteAllSubfeaturesForAuth($auth_id, $feature_id)
    {
        $query = "SELECT auth_subfeatures.auth_authentication, auth_subfeatures.cat_subfeature FROM  `auth_subfeatures` JOIN cat_subfeatures ON auth_subfeatures.cat_subfeature=cat_subfeatures.id WHERE feature='%d' AND auth_authentication='%d';";

        $query = sprintf($query,
            $this->dbController->escapeStripString($feature_id),
            $this->dbController->escapeStripString($auth_id)
        );

        $result = $this->dbController->secureGet($query);

        foreach ($result as $row) {
            $query = "DELETE FROM auth_subfeatures WHERE auth_authentication='%d' AND cat_subfeature='%d';";

            $query = sprintf($query,
                $row["auth_authentication"],
                $row["cat_subfeature"]
            );

            $this->dbController->secureSet($query);

        }
    }

    /**
     * acceptSubfeatureSuggestion($id)
     * @param $id - if of suggestion
     * @desc Accept subfeature suggestion
     */
    public function acceptSubfeatureSuggestion($id)
    {
        $query = "SELECT * FROM subfeature_suggestion WHERE id='%d';";

        $query = sprintf($query,
            $this->dbController->escapeStripString($id));

        $result = $this->dbController->secureGet($query);

        $query = "SELECT * FROM cat_subfeatures WHERE id='%d';";

        $query = sprintf($query,
            $this->dbController->escapeStripString($result[0]["cat_subfeature"]));

        $cat_subfeature = $this->dbController->secureGet($query);

        $feature_id = $cat_subfeature[0]["feature"];

        $query = "SELECT * FROM cat_features WHERE id='%d';";

        $query = sprintf($query,
            $this->dbController->escapeStripString($feature_id));

        $cat_features = $this->dbController->secureGet($query);

        $feature_name = $cat_features[0]["name"];

        $selectiveFeatures = array("Memorywise-Effortless", "Nothing-to-Carry", "Physically-Effortless");

        $selectiveFeaturesWithNullclass = array("Scalable-for-Users",
            "Browser-Compatible",
            "Easy-to-Learn",
            "Easy-Recovery-from-Loss",
            "Negligible-Cost-per-User",
            "Server-Compatible",
            "Browser-Compatible",
            "Non-Proprietary",
            "Resilient-to-Targeted-Impersonation",
            "Resilient-to-Throttled-Guessing",
            "Resilient-to-Unthrottled-Guessing",
            "Resilient-to-Leaks-form-Other-Verifiers", //needs to be fixed *from
            "Resilient-to-Phishing",
            "Resilient-to-Theft",
            "Resilient-to-Third-Party",
            "Requiring-Explicit-Consent",
            "Unlinkable");

        $value = $result[0]["value"];

        //special treatment for selective features
        if (in_array($feature_name, $selectiveFeatures)) {

            $this->deleteAllSubfeaturesForAuth($result[0]["auth_authentication"], $feature_id);

            $query = "INSERT IGNORE INTO auth_subfeatures SET auth_authentication='%d', cat_subfeature='%d'";

            $query = sprintf($query,
                $this->dbController->escapeStripString($result[0]["auth_authentication"]),
                $this->dbController->escapeStripString($result[0]["cat_subfeature"])
            );

            $this->dbController->secureSet($query);

            $this->deleteSubfeatureSuggestion($id);

            return;
        } else if (in_array($feature_name, $selectiveFeaturesWithNullclass)) {

            $this->deleteAllSubfeaturesForAuth($result[0]["auth_authentication"], $feature_id);

            if ($value == "1") {

                $query = "INSERT IGNORE INTO auth_subfeatures SET auth_authentication='%d', cat_subfeature='%d'";

                $query = sprintf($query,
                    $this->dbController->escapeStripString($result[0]["auth_authentication"]),
                    $this->dbController->escapeStripString($result[0]["cat_subfeature"])
                );

                $this->dbController->secureSet($query);
            }

            $this->deleteSubfeatureSuggestion($id);

            return;
        }

        if ($value == "0") {
            $query = "DELETE FROM auth_subfeatures WHERE auth_authentication='%d' AND cat_subfeature='%d'";

            $query = sprintf($query,
                $this->dbController->escapeStripString($result[0]["auth_authentication"]),
                $this->dbController->escapeStripString($result[0]["cat_subfeature"])
            );

            $this->dbController->secureSet($query);

            $this->deleteSubfeatureSuggestion($id);

            return;
        } else if ($value == "1") {
            $query = "INSERT IGNORE INTO auth_subfeatures SET auth_authentication='%d', cat_subfeature='%d'";

            $query = sprintf($query,
                $this->dbController->escapeStripString($result[0]["auth_authentication"]),
                $this->dbController->escapeStripString($result[0]["cat_subfeature"])
            );

            $this->dbController->secureSet($query);

            $this->deleteSubfeatureSuggestion($id);

            return;
        }
    }

    /**
     * acceptSchemeSuggestion($id)
     * @param $id - if of suggestion
     * @desc Accept authentication scheme suggestion
     */
    public function acceptSchemeSuggestion($id)
    {
        $query = "INSERT INTO auth_authentications (name,category) SELECT name, category FROM auth_suggestions WHERE id=%d;";

        $query = sprintf($query, $this->dbController->escapeStripString($id));

        $this->dbController->secureSet($query);

        $latestInsertionId = $this->dbController->getLatestInsertionId();

        $query = "SELECT * FROM auth_suggestions_new_classes WHERE id='%d'";

        $query = sprintf($query, $this->dbController->escapeStripString($id));

        $new_classes = $this->dbController->secureGet($query);

        if (count($new_classes) > 0) {
            $query = "INSERT INTO cat_class_feature (name,cat_feature) VALUES ('%s','%d');";

            $query = sprintf($query, $new_classes[0]["name"], $new_classes[0]["cat_feature"]);

            $this->dbController->secureSet($query);
        }

        $query = "SELECT * FROM auth_suggestions WHERE id=%d;";

        $query = sprintf($query, $this->dbController->escapeStripString($id));

        $suggestion = $this->dbController->secureGet($query);


        $query = "INSERT INTO auth_info (id,description) VALUES (%d,'%s');";

        $query = sprintf($query, $latestInsertionId, $suggestion[0]["description"]);

        $this->dbController->secureSet($query);


        $query = "SELECT * FROM auth_suggestions_subfeatures WHERE id=%d;";

        $query = sprintf($query, $this->dbController->escapeStripString($id));

        $subfeatures = $this->dbController->secureGet($query);

        if (count($subfeatures) > 0) {
            $query = "INSERT INTO auth_subfeatures (auth_authentication,cat_subfeature) VALUES %s;";

            $values = "";

            foreach ($subfeatures as $subfeature) {
                $values .= "(%d,%d),";
                $values = sprintf(
                    $values,
                    $latestInsertionId,
                    $subfeature["cat_subfeature"]
                );
            }

            $values = substr($values, 0, -1);
            $query = sprintf($query, $values);

            $this->dbController->secureSet($query);
        }

        $query = "SELECT * FROM auth_suggestions_classification WHERE id=%d;";

        $query = sprintf($query, $this->dbController->escapeStripString($id));

        $classvalues = $this->dbController->secureGet($query);

        if (count($classvalues) > 0) {
            $query = "INSERT INTO cat_class_authentications_value_pair (cat_feature,cat_class_feature,auth_authentication_1,auth_authentication_2,value) VALUES %s;";

            $values = "";

            foreach ($classvalues as $classvalue) {
                if ($classvalue["auth_authentication_1"] == -1)
                    $classvalue["auth_authentication_1"] = $latestInsertionId;
                else if ($classvalue["auth_authentication_2"] == -1)
                    $classvalue["auth_authentication_2"] = $latestInsertionId;

                $values .= "(%d,%d,%d,%d,%f),";
                $values = sprintf(
                    $values,
                    $classvalue["cat_feature"],
                    $classvalue["cat_class_feature"],
                    $classvalue["auth_authentication_1"],
                    $classvalue["auth_authentication_2"],
                    $classvalue["value"]

                );

            }

            $values = substr($values, 0, -1);
            $query = sprintf($query, $values);

            $this->dbController->secureSet($query);
        }

        $this->deleteSchemeSuggestion($id);

    }

    /**
     * getSchemesForEditing($id)
     * @param $id - if of scheme suggestion
     * @return meta data for scheme suggestion
     * @desc Get meta data for scheme suggestion by id
     */
    public function getSchemesForEditing($id)
    {
        $query = "SELECT auth_suggestions.id,name,description,category,FirstName,LastName,suggestion_date FROM auth_suggestions INNER JOIN users ON auth_suggestions.suggestion_userid=users.Id WHERE auth_suggestions.id=%d;";

        $query = sprintf($query, $this->dbController->escapeStripString($id));

        return $this->dbController->secureGet($query)[0];
    }

    /**
     * getSubfeaturesForEditing($id)
     * @param $id - if of scheme suggestion
     * @return array of selected subfeatures (name and id)
     * @desc Get selected subfeatures of scheme suggestion by id
     */
    public function getSubfeaturesForEditing($id)
    {
        $query = "SELECT cat_subfeatures.id, cat_subfeatures.name FROM auth_suggestions_subfeatures RIGHT JOIN cat_subfeatures ON auth_suggestions_subfeatures.cat_subfeature = cat_subfeatures.id WHERE auth_suggestions_subfeatures.id=%d;";

        $query = sprintf($query, $this->dbController->escapeStripString($id));

        return $this->dbController->secureGet($query);
    }

    /**
     * getClassvaluesForEditing($id)
     * @param $id - if of scheme suggestion
     * @return array of scalevalue-pairs
     * @desc Get scalevalue-pairs of scheme suggestion by id
     */
    public function getClassvaluesForEditing($id)
    {
        $query = "SELECT a.id,a.name as class,a.cat_feature,a.cat_class_feature,a.auth_authentication_1,a.auth_authentication_2,a.value,cat_features.name as feature FROM (SELECT auth_suggestions_classification.id,auth_suggestions_classification.cat_feature,auth_suggestions_classification.cat_class_feature,auth_suggestions_classification.auth_authentication_1,auth_suggestions_classification.auth_authentication_2,auth_suggestions_classification.value,name FROM `auth_suggestions_classification` LEFT JOIN cat_class_feature ON auth_suggestions_classification.cat_class_feature = cat_class_feature.id WHERE auth_suggestions_classification.id=%d) AS a INNER JOIN `cat_features` ON a.cat_feature=cat_features.id;";

        $query = sprintf($query, $this->dbController->escapeStripString($id));

        return $this->dbController->secureGet($query);
    }

    /**
     * getNewClassesForEditing($id)
     * @param $id - if of scheme suggestion
     * @return array of all new classes
     * @desc Get all newclasses of scheme suggestion by id
     */
    public function getNewClassesForEditing($id)
    {
        $query = "SELECT auth_suggestions_new_classes.name AS new_class_name, cat_features.name AS feature_name FROM auth_suggestions_new_classes  LEFT JOIN cat_features ON auth_suggestions_new_classes.cat_feature=cat_features.id WHERE auth_suggestions_new_classes.id='%d';";

        $query = sprintf($query, $this->dbController->escapeStripString($id));

        return $this->dbController->secureGet($query);
    }

    /**
     * getAllAuthIDs()
     * @return array of all authentication schemes - index: auth_id value: auth_name
     * @desc Get all newclasses of scheme suggestion by id
     */
    public function getAllAuthIDs()
    {
        $query = "SELECT * FROM auth_authentications;";

        $data = $this->dbController->secureGet($query);

        $new_arr = array();

        foreach ($data as $subdata) {
            $new_arr[$subdata["id"]] = $subdata["name"];
        }

        return $new_arr;
    }

    /**
     * EditMetaForSchemeSuggestion($id, $name, $description, $category)
     * @param $id - scheme id
     * @param $description - scheme description
     * @param $category - scheme category
     * @desc Update metadata for authentication scheme suggestion
     */
    public function EditMetaForSchemeSuggestion($id, $name, $description, $category)
    {
        $query = "UPDATE auth_suggestions SET name = '%s', description = '%s', category = '%s' WHERE id=%d;";

        $query = sprintf($query,
            $this->dbController->escapeStripString($name),
            $this->dbController->escapeStripString($description),
            $this->dbController->escapeStripString($category),
            $this->dbController->escapeStripString($id));

        $this->dbController->secureSet($query);

    }

    /**
     * EditClassvaluesForSchemeSuggestion($id, $classValues)
     * @param $id - scheme id
     * @param $classValues - array of classvalues
     * @desc Update classvalues of authentication scheme suggestion
     */
    public function EditClassvaluesForSchemeSuggestion($id, $classValues)
    {

        $query = "INSERT INTO auth_suggestions_classification
                    (id, cat_feature, cat_class_feature, auth_authentication_1, auth_authentication_2, value)
                  VALUES %s
                  ON DUPLICATE KEY UPDATE value=VALUES(value)
                 ";

        $values = "";

        foreach ($classValues as $classvalue) {
            $values .= "(%d,%d,%d,%d,%d,%f),";
            $values = sprintf(
                $values,
                $this->dbController->escapeStripString($id),
                $this->dbController->escapeStripString($classvalue["cat_feature"]),
                $this->dbController->escapeStripString($classvalue["cat_class_feature"]),
                $this->dbController->escapeStripString($classvalue["auth_authentication_1"]),
                $this->dbController->escapeStripString($classvalue["auth_authentication_2"]),
                $this->dbController->escapeStripString($classvalue["value"])
            );
        }

        $values = substr($values, 0, -1);
        $query = sprintf($query, $values);

        $this->dbController->secureSet($query);

    }

    /**
     * getClassesForAuth($id)
     * @param $id - auth id
     * @return array of all classes for given authid
     * @desc Get all classes of an auth by authid
     */
    public function getClassesForAuth($id)
    {
        $query = "SELECT cat_features.name AS feature, cat_class_feature.name AS class FROM authentication_feature_class LEFT JOIN cat_class_feature ON authentication_feature_class.cat_class_feature=cat_class_feature.id LEFT JOIN cat_features ON authentication_feature_class.cat_feature=cat_features.id WHERE auth_authentication='%d';";

        $query = sprintf($query, $this->dbController->escapeStripString($id));

        return $this->dbController->secureGet($query);
    }

    /**
     * getAuthNameById($id)
     * @param $id - auth id
     * @return name of auth
     * @desc Get auth name by auth_id
     */
    public function getAuthNameById($id)
    {
        $query = "SELECT DISTINCT name FROM `auth_authentications` WHERE id='%d' LIMIT 1";
        $query = sprintf(
            $query,
            $this->dbController->escapeStripString($id)
        );

        $result = $this->dbController->secureGet($query);
        return $result[0]["name"];
    }

    /**
     * getSuggestionChangelogByAuthId($auth_id)
     * @param $id - auth id
     * @return array of all timeline entries
     * @desc Get all timeline entries for given auth
     */
    public function getSuggestionChangelogByAuthId($auth_id)
    {
        $query = "SELECT * FROM suggestion_changelog WHERE auth_authentication='%d';";

        $query = sprintf($query, $this->dbController->escapeStripString($auth_id));

        return $this->dbController->secureGet($query);
    }

    /**
     * insertChangelogEntry($auth_id, $log_title, $log_text)
     * @param $auth_id - auth id
     * @param $log_title - title of timeline entry
     * @param $log_text - text of timeline entry
     * @desc Insert timeline entry for authentication scheme
     */
    private function insertChangelogEntry($auth_id, $log_title, $log_text)
    {
        $query = "INSERT INTO suggestion_changelog (auth_authentication,log_title,log_text) VALUES (%d,'%s','%s');";

        $query = sprintf($query,
            $this->dbController->escapeStripString($auth_id),
            $this->dbController->escapeStripString($log_title),
            $log_text); //html-content dont strip

        return $this->dbController->secureSet($query);
    }

    /**
     * deleteChangelogEntryById($entry_id)
     * @param $entry_id - timeline entry id
     * @desc Delete timeline entry by id
     */
    public function deleteChangelogEntryById($entry_id)
    {
        $query = "DELETE FROM suggestion_changelog WHERE id='%d'";

        $query = sprintf($query,
            $this->dbController->escapeStripString($entry_id));

        return $this->dbController->secureSet($query);
    }

    /**
     * insertSubfeatureSuggestionChangelog($suggestion_id)
     * @param $suggestion_id - suggestion id
     * @desc Insert timeline entry for subfeature suggestion
     */
    public function insertSubfeatureSuggestionChangelog($suggestion_id)
    {
        $query = "SELECT subfeature_suggestion.id, subfeature_suggestion.auth_authentication, subfeature_suggestion.value, cat_subfeatures.name FROM subfeature_suggestion JOIN cat_subfeatures ON cat_subfeatures.id = subfeature_suggestion.cat_subfeature WHERE subfeature_suggestion.id='%s';";

        $query = sprintf($query,
            $this->dbController->escapeStripString($suggestion_id));

        $result = $this->dbController->secureGet($query);

        if (count($result) <= 0)
            return;

        $auth_authentication = $result[0]["auth_authentication"];
        $subfeature_name = $result[0]["name"];
        $value = $result[0]["value"];

        if ($value == "0")
            $this->insertChangelogEntry($auth_authentication, "Subfeature Suggestion", $subfeature_name . " Removed (-)");
        else
            $this->insertChangelogEntry($auth_authentication, "Subfeature Suggestion", $subfeature_name . " Added (+)");


        return;
    }

    /**
     * insertClassificationSuggestionChangelog($suggestion_id)
     * @param $suggestion_id - suggestion id
     * @desc Insert timeline entry for classification suggestion
     */
    public function insertClassificationSuggestionChangelog($suggestion_id)
    {
        $query = "SELECT classification_suggestion.auth_id, cat_features.name AS feature_name FROM `classification_suggestion` JOIN cat_features ON classification_suggestion.feature_id=cat_features.id WHERE classification_suggestion.id='%s';";

        $query = sprintf($query,
            $this->dbController->escapeStripString($suggestion_id));

        $result = $this->dbController->secureGet($query);

        if (count($result) <= 0)
            return;

        $auth_authentication = $result[0]["auth_id"];
        $feature_name = $result[0]["feature_name"];
        $auths = $this->getChangedClassvaluesFromClassifcationSuggestion($suggestion_id);

        $output = "Feature: " . $feature_name . "<hr>";

        foreach ($auths as $auth) {
            if ($auth["value"] == "1.5") {
                $output .= "<b>" . $this->getAuthNameById($auth["auth_authentication_1"]) . "</b> is better than <b>" . $this->getAuthNameById($auth["auth_authentication_2"]) . "</b></br>";
                $temp = "Feature: " . $feature_name . "<hr><b>" . $this->getAuthNameById($auth["auth_authentication_1"]) . "</b> is better than <b>" . $this->getAuthNameById($auth["auth_authentication_2"]) . "</b></br>";

                if ($auth["auth_authentication_1"] == $auth_authentication) {
                    $this->insertChangelogEntry($auth["auth_authentication_2"], "Classification Suggestion", $temp);
                } else {
                    $this->insertChangelogEntry($auth["auth_authentication_1"], "Classification Suggestion", $temp);
                }

            } else if ($auth["value"] == "1") {
                $output .= "<b>" . $this->getAuthNameById($auth["auth_authentication_1"]) . "</b> is equal to <b>" . $this->getAuthNameById($auth["auth_authentication_2"]) . "</b></br>";
                $temp = "Feature: " . $feature_name . "<hr><b>" . $this->getAuthNameById($auth["auth_authentication_1"]) . "</b> is equal to <b>" . $this->getAuthNameById($auth["auth_authentication_2"]) . "</b></br>";

                if ($auth["auth_authentication_1"] == $auth_authentication) {
                    $this->insertChangelogEntry($auth["auth_authentication_2"], "Classification Suggestion", $temp);
                } else {
                    $this->insertChangelogEntry($auth["auth_authentication_1"], "Classification Suggestion", $temp);
                }
            } else {
                $output .= "<b>" . $this->getAuthNameById($auth["auth_authentication_1"]) . "</b> is worse than <b>" . $this->getAuthNameById($auth["auth_authentication_2"]) . "</b></br>";
                $temp = "Feature: " . $feature_name . "<hr><b>" . $this->getAuthNameById($auth["auth_authentication_1"]) . "</b> is worse than <b>" . $this->getAuthNameById($auth["auth_authentication_2"]) . "</b></br>";

                if ($auth["auth_authentication_1"] == $auth_authentication) {
                    $this->insertChangelogEntry($auth["auth_authentication_2"], "Classification Suggestion", $temp);
                } else {
                    $this->insertChangelogEntry($auth["auth_authentication_1"], "Classification Suggestion", $temp);
                }
            }

        }

        $this->insertChangelogEntry($auth_authentication, "Classification Suggestion", $output);
        return;
    }

    /**
     * getChangedClassvaluesFromClassifcationSuggestion($suggestion_id)
     * @param $suggestion_id - suggestion id
     * @return array of classvalues
     * @desc Get list of all classvalues who really changed when accepting an classification suggestion
     */
    public function getChangedClassvaluesFromClassifcationSuggestion($suggestion_id)
    {
        $query = "SELECT * FROM classification_suggestion_values WHERE id='%d'";

        $query = sprintf($query,
            $this->dbController->escapeStripString($suggestion_id));

        $result = $this->dbController->secureGet($query);

        $query = "SELECT * FROM cat_class_authentications_value_pair WHERE ";

        foreach ($result as $entry) {
            $query .= "(cat_feature='%d' AND cat_class_feature='%d' AND auth_authentication_1='%d' AND auth_authentication_2='%d' AND NOT value='%f') OR ";
            $query = sprintf($query, $entry["cat_feature"],
                $entry["cat_class_feature"],
                $entry["auth_authentication_1"],
                $entry["auth_authentication_2"],
                $entry["value"]);
        }

        $query = substr($query, 0, -3);

        $classvalues = $this->dbController->secureGet($query);

        for ($i = 0; $i < count($classvalues); $i++) {
            foreach ($result as $single_result) {
                if ($classvalues[$i]["cat_feature"] == $single_result["cat_feature"] &&
                    $classvalues[$i]["cat_class_feature"] == $single_result["cat_class_feature"] &&
                    $classvalues[$i]["auth_authentication_1"] == $single_result["auth_authentication_1"] &&
                    $classvalues[$i]["auth_authentication_2"] == $single_result["auth_authentication_2"]
                ) {
                    $classvalues[$i]["value"] = $single_result["value"];
                }
            }
        }

        return $classvalues;
    }

    /**
     * filterSuggestionClassvalues($feature_id, $class_id, $cv)
     * @param $feature_id - feature id
     * @param $class_id - subfeature class id
     * @param $cv - array of classvalue pairs
     * @return array of classvalues who really changed
     * @desc Get a list of all classvalues who really changed when inserting a classification suggestion
     */
    public function filterSuggestionClassvalues($feature_id, $class_id, $cv)
    {

        $new_cv = array();
        for ($i = 0; $i < count($cv); $i++) {

            $query = "SELECT * FROM cat_class_authentications_value_pair WHERE ";
            $query .= "cat_feature='%d' AND cat_class_feature='%d' AND auth_authentication_1='%s' AND auth_authentication_2='%d' AND value='%f'";
            $query = sprintf($query, $feature_id,
                $class_id,
                $this->getAuthID($cv[$i]["auth_1"]),
                $this->getAuthID($cv[$i]["auth_2"]),
                $cv[$i]["value"]);

            $res = $this->dbController->secureGet($query);

            if (count($res) <= 0) {
                $new_cv[] = $cv[$i];
            }
        }
        return $new_cv;
    }

    /**
     * getAllClassesWithFeatures()
     * @return Array of all subfeature classes together with corresponding feature names
     * @desc Get subfeature classes together with corresponding feature names
     */
    public function getAllClassesWithFeatures()
    {
        $query = "SELECT DISTINCT cat_class_feature.name AS class_name, cat_features.name AS feature_name FROM cat_class_feature JOIN cat_features ON cat_features.id=cat_class_feature.cat_feature";

        return $this->dbController->secureGet($query);
    }
}

?>