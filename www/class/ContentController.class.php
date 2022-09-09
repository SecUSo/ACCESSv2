<?
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
 * Class ContentController
 * @desc controller for sql input/output of content data
 */
class ContentController
{
    private $dbController;

    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    public function addContent($id, $description)
    {
        $sqlData = "INSERT INTO auth_info " .
            "(id, description) " .
            "VALUES(" .
            $this->dbController->escapeStripString($id) . ", " .
            "'" . $this->dbController->escapeStripString($description) . "'" .
            ");";

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function updateContent($id, $description)
    {
        $sqlData = "UPDATE auth_info SET " .
            "description='" . $this->dbController->escapeStripString($description) . "' " .
            "WHERE id=" . $this->dbController->escapeStripString($id) . ";";

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function deleteContent($pk_id)
    {
        $sqlData = "DELETE FROM auth_info WHERE id=" . $this->dbController->escapeStripString($pk_id);

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function getContent($pk_id)
    {
        $sqlData = "SELECT auth_authentications.id, description, name, category FROM auth_authentications LEFT JOIN auth_info " .
            "ON auth_authentications.id = auth_info.id WHERE auth_authentications.id=" . $this->dbController->escapeStripString($pk_id);
        return $this->dbController->secureGet($sqlData);
    }

    public function getAllDescriptionWithName()
    {
        $sqlData = "SELECT description, name  FROM auth_authentications LEFT JOIN auth_info " .
            "ON auth_authentications.id = auth_info.id;";
        $result = $this->dbController->secureGet($sqlData);

        $new_res = array();
        foreach ($result as $entry) {
            $new_res[$entry["name"]] = $entry["description"];
        }
        return $new_res;
    }

    public function getAllFeatureDescriptionsWithName()
    {
        $sqlData = "SELECT description, name FROM cat_features LEFT JOIN feature_info ON cat_features.id = feature_info.id;";
        $result = $this->dbController->secureGet($sqlData);

        $new_res = array();
        foreach ($result as $entry) {
            $new_res[$entry["name"]] = $entry["description"];
        }
        return $new_res;
    }

    public function isInInfoTable($pk_id)
    {
        $sqlData = "SELECT id FROM auth_info WHERE id=" . $this->dbController->escapeStripString($pk_id);

        $tempData = $this->dbController->secureGet($sqlData);

        if (count($tempData) == 0)
            return false;
        else
            return true;
    }

    public function getIndexContent()
    {
        $sqlData = "SELECT id, name, category " .
            "FROM auth_authentications ";

        return $this->dbController->secureGet($sqlData);
    }

    public function getSubFeaturesForAuthIndex($index)
    {
        $sqlData = "SELECT cat_subfeatures.name, cat_subfeatures.id FROM auth_subfeatures INNER JOIN cat_subfeatures ON auth_subfeatures.cat_subfeature=cat_subfeatures.id WHERE auth_subfeatures.auth_authentication=" . $this->dbController->escapeStripString($index);

        return $this->dbController->secureGet($sqlData);
    }


}

?>
