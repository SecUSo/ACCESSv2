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
 * Class AuthenticationsController
 * @desc The Controller for the "Add/Change Authentication Schemes" Admin Page
 * @var $dbController : The Database Controller
 */
class AuthenticationsController
{
    private $dbController;

    /**
     * AuthenticationsController constructor.
     */
    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    /**
     * getIndexContent()
     * @return array - The Output-Array containing Authentication Scheme Names and their Categories
     *  Array(
     *          Authentication-Name => Category
     *          Authentication-Name => Category
     *          ...
     *       )
     * @desc Returns Information for the "Add/Change Authentication Schemes" Admin Page
     */
    public function getIndexContent()
    {
        // Request all Authentications from the Database
        $requestQuery = "
          SELECT
            name AS name,
            category AS category
          FROM `auth_authentications`
          ";

        $authentications = $this->dbController->secureGet($requestQuery);

        // Array to hold the Output
        $output = array();

        // Fill out Array for the Output
        foreach($authentications as $value){
            $output[$value['name']] = $value['category'];
        }

        return $output;
    }

    /**
     * insertAuthentications($authArr)
     * @param $authArr - An Array that contains Authentication-Names and their Categories
     *  Array(
     *          Authentication-Name => Category
     *          Authentication-Name => Category
     *          ...
     *       )
     * @desc Inserts the given Authentication Scheme Names with their Categories into the Database.
     *          If the Authentication Scheme Name is already in the Database, the Category only will be updated.
     */
    public function insertAuthentications($authArr)
    {
        // Query to insert new Authentications
        // If the Authentication is already in the Database, the category gets updated
        $insertQuery = "
            INSERT INTO
              `auth_authentications`
              (name, category)
            VALUES %s
            ON DUPLICATE KEY
            UPDATE
              category=VALUES(category)
            ";

        // Build up the Values for the Query
        $insertValues = "";
        foreach($authArr as $authName => $authCat){
            $insertValues .= "('%s', '%s'),";
            $insertValues = sprintf($insertValues,
                $this->dbController->escapeStripString($authName),
                $this->dbController->escapeStripString($authCat)
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
     * deleteAuthentications($authArr)
     * @param $authArr - An Array that contains Authentication Scheme Names.
     *  Array(
     *          [0] => Authentication-Name
     *          [1] => Authentication-Name
     *          ...
     *       )
     * @desc Deletes the Authentication Schemes from the Database given in the Parameter.
     */
    public function deleteAuthentications($authArr)
    {
        $deleteQuery = "
          DELETE FROM
            `auth_authentications`
          WHERE
            name NOT IN(%s)
          ";

        // Build up the Information for the deleteQuery
        $deleteValues = "";

        foreach($authArr as $authName => $value){
            $deleteValues .= "'%s',";
            $deleteValues = sprintf(
                $deleteValues,
                $this->dbController->escapeStripString($authName)
            );
        }

        $deleteValues = substr($deleteValues, 0, -1);
        $deleteQuery = sprintf(
            $deleteQuery, 
            $deleteValues
        );

        $this->dbController->secureSet($deleteQuery);
    }

}
?>