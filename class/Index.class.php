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
 * Class Index
 * @desc output frontpage: auths, features, subfeatures
 */
class Index
{
    private $sessionController;
    private $contentController;
    private $featureController;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->contentController = new ContentController();
        $this->featureController = new FeatureController();
        $this->initTemplate();
    }

    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - LOGIN";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_content = $this->contentController->getIndexContent();

        $data_featureOverview = array();
        $categories = $this->featureController->getCategories();

        for ($iCat = 0; $iCat < count($categories); $iCat++) {
            $category = $categories[$iCat];
            $features = $this->featureController->getFeatures($category['id']);
            $featureArr = array();
            for ($iFeature = 0; $iFeature < count($features); $iFeature++) {
                $feature = $features[$iFeature];
                $subfeatures = $this->featureController->getSubfeatures($feature['id']);
                $featureArr[] = array("id" => $feature['id'], "name" => $feature['name'], "subfeatures" => $subfeatures);
            }
            $data_featureOverview[] = array("id" => $category['id'], "name" => $category['name'], "features" => $featureArr);
        }

        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/index.php");
        include_once("content/footer.php");
    }
}

?>
