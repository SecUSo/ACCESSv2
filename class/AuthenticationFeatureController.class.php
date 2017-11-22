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
 * Class AuthenticationFeatureController
 * @desc Controller for the Classes of the "Change SubFeatures of Authentication Schemes"-Page
 * @var $dbController : The Database-Controller to send SQL-Queries
 */
class AuthenticationFeatureController
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
    public function insertAuthSubFeatures($authName, $subFeatureNames)
    {
        // Query to insert all SubFeatures the Authentication now should have
        $insertQuery = "
            INSERT IGNORE INTO
                `auth_subfeatures`
                  (auth_authentication, cat_subfeature)
            VALUES
              %s
            ";
        $authID = $this->getAuthID($authName);
        $subFeatureNameToID = $this->getSubFeatureIDs();
        $insertValues = "";

        foreach ($subFeatureNames as $subFeatureName) {
            $insertValues .= "(%d,%d),";
            $insertValues = sprintf(
                $insertValues,
                $authID,
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

    //Get clean data-obj with cat, feature, subfeature for auth. suggestions
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

        $preSelectSelectiveSubfeatures = array("No-Secret-to-Remember", "No-Object-to-Carry", "No-Physical-Effort");

        // Build the Output
        foreach ($allResult as $row) {
            if (in_array($row['subfeature'], $preSelectSelectiveSubfeatures))
                $resultArray[$row['category']][$row['feature']][$row['subfeature']] = true;
            else
                $resultArray[$row['category']][$row['feature']][$row['subfeature']] = false;

        }

        return $resultArray;
    }

}

?>