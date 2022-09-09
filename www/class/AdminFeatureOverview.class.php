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
 * Class AdminFeatureOverview
 * @desc JSON API: (admin) output overview for features
 */
class AdminFeatureOverview
{
    public static $isViewable = TRUE;

    private $featureController;
    private $sessionController;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->featureController = new FeatureController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->initTemplate();
    }

    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - FEATURE OVERVIEW";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();

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
        include_once("content/admin/featureoverview.php");
        include_once("content/footer.php");
    }


}

?>
