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
?>

<?

/**
 * Class FeatureController
 * @desc controller for sql input/output of feature data
 */
class FeatureController
{
    private $dbController;

    public function __construct()
    {
        $this->dbController = new DatabaseController();
    }

    public function addFeature($id, $description)
    {
        $sqlData = "INSERT INTO feature_info " .
            "(id, description) " .
            "VALUES(" .
            $this->dbController->escapeStripString($id) . ", " .
            "'" . $this->dbController->escapeStripString($description) . "'" .
            ");";

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function addSubFeature($id, $description)
    {
        $sqlData = "INSERT INTO subfeature_info " .
            "(id, description) " .
            "VALUES(" .
            $this->dbController->escapeStripString($id) . ", " .
            "'" . $this->dbController->escapeStripString($description) . "'" .
            ");";

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function updateFeature($id, $description)
    {
        $sqlData = "UPDATE feature_info SET " .
            "description='" . $this->dbController->escapeStripString($description) . "' " .
            "WHERE id=" . $this->dbController->escapeStripString($id) . ";";

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function updateSubFeature($id, $description)
    {
        $sqlData = "UPDATE subfeature_info SET " .
            "description='" . $this->dbController->escapeStripString($description) . "' " .
            "WHERE id=" . $this->dbController->escapeStripString($id) . ";";

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function deleteFeature($pk_id)
    {
        $sqlData = "DELETE FROM feature_info WHERE id=" . $this->dbController->escapeStripString($pk_id);

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function deleteSubFeature($pk_id)
    {
        $sqlData = "DELETE FROM subfeature_info WHERE id=" . $this->dbController->escapeStripString($pk_id);

        $this->dbController->secureSet($sqlData);

        return true;
    }

    public function getCategories()
    {
        $sqlData = "SELECT id, name FROM cat_categories";

        return $this->dbController->secureGet($sqlData);
    }

    public function getFeatures($category)
    {
        $sqlData = "SELECT id, category, name FROM cat_features WHERE category=" . $this->dbController->escapeStripString($category);

        return $this->dbController->secureGet($sqlData);
    }

    public function getSubfeatures($feature)
    {
        $sqlData = "SELECT id, name, feature FROM cat_subfeatures WHERE feature=" . $this->dbController->escapeStripString($feature);

        return $this->dbController->secureGet($sqlData);
    }

    public function isInFeatureTable($pk_id)
    {
        $sqlData = "SELECT id FROM feature_info WHERE id = " . $this->dbController->escapeStripString($pk_id);

        $tempData = $this->dbController->secureGet($sqlData);

        if (count($tempData) == 0)
            return false;
        else
            return true;
    }

    public function isInSubFeatureTable($pk_id)
    {
        $sqlData = "SELECT id FROM subfeature_info WHERE id = " . $this->dbController->escapeStripString($pk_id);

        $tempData = $this->dbController->secureGet($sqlData);

        if (count($tempData) == 0)
            return false;
        else
            return true;
    }

    public function getFeatureInfo($index)
    {
        $sqlData = "SELECT cat_features.id, name, description FROM cat_features LEFT JOIN feature_info " .
            "ON cat_features.id = feature_info.id WHERE cat_features.id=" . $this->dbController->escapeStripString($index);

        return $this->dbController->secureGet($sqlData);
    }

    public function getSubfeatureInfo($index)
    {
        $sqlData = "SELECT cat_subfeatures.id, name, description FROM cat_subfeatures LEFT JOIN subfeature_info " .
            "ON cat_subfeatures.id = subfeature_info.id WHERE cat_subfeatures.id=" . $this->dbController->escapeStripString($index);

        return $this->dbController->secureGet($sqlData);
    }

    public function getAllSubfeatureDescriptions()
    {
        $sqlData = "SELECT name, description FROM cat_subfeatures LEFT JOIN subfeature_info " .
            "ON cat_subfeatures.id = subfeature_info.id;";

        $result = $this->dbController->secureGet($sqlData);

        $new_res = array();
        foreach($result as $entry)
        {
            $new_res[$entry["name"]] = $entry["description"];
        }
        return $new_res;
    }

    public function getAHPMatrixByFeatureId($id)
    {
        $sqlData = "SELECT feature_authentications_value.value FROM feature_authentications_value " .
            "INNER JOIN auth_authentications ON feature_authentications_value.auth_authentication_2 = auth_authentications.id WHERE cat_feature=" . $this->dbController->escapeStripString($id);

        return $this->dbController->secureGet($sqlData);

    }

    public function getAHPMatrixCaptions()
    {
        $sqlData = "SELECT name FROM auth_authentications ORDER BY id ASC";

        return $this->dbController->secureGet($sqlData);

    }


}

?>
