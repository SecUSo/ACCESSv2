<?
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
 * Class AdminEditCategories
 * @desc Class to edit the Categories, Features and SubFeatures
 * @var $isViewable : Static Boolean if the page is viewable
 * @var $categoriesController : Needed Controller for the class
 * @var $sessionController : The Session-Controller
 */
class AdminEditCategories
{
    public static $isViewable = TRUE;
    private $categoriesController;
    private $sessionController;

    /**
     * AdminEditCategories constructor.
     */
    public function __construct()
    {
        $this->categoriesController = new CategoriesController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
    }

    /**
     * getParams()
     * @desc Parses the POST-Data which should contain a JSON-String and lets the CategoriesController modify the data
     *          in the Database
     * @data The JSON-String in the POST-Variable json:
     * {
     *  "Category-Name":{
     *                  "Feature-Name":[
     *                                  "SubFeature-Name",
     *                                  "SubFeature-Name",
     *                                  ...
     *                                  ],
     *                  "Feature-Name":[..], ...
     *                  },
     *  "Category-Name":{..}, ...
     * }
     */
    private function getParams(){
        // Decode the incoming Data from the Front-End
        if($this->sessionController->getIsAdmin() != 0 && isset($_POST['json'])) {
            $json = json_decode($_POST['json'], true);
            $categoryNames = array();
            $featureNames = array();
            $subFeatureNames = array();
            $categoryFeatureNames = array();
            $featureSubFeatureNames = array();

            foreach($json as $categoryName => $arr) {
                array_push(
                    $categoryNames,
                    $categoryName
                );
                $categoryFeatureNames[$categoryName] = array();

                foreach($arr as $featureName => $fArr) {
                    array_push(
                        $featureNames,
                        $featureName
                    );
                    array_push(
                        $categoryFeatureNames[$categoryName],
                        $featureName
                    );
                    $featureSubFeatureNames[$featureName] = array();

                    foreach($fArr as $subFeatureName) {
                        array_push(
                            $subFeatureNames,
                            $subFeatureName
                        );
                        array_push(
                            $featureSubFeatureNames[$featureName],
                            $subFeatureName
                        );
                    }
                }
            }

            $this->categoriesController->deleteCategories($categoryNames);
            $this->categoriesController->deleteFeatures($featureNames);
            $this->categoriesController->deleteSubFeatures($subFeatureNames);
            $this->categoriesController->insertCategories($categoryNames);
            $this->categoriesController->insertFeatures($categoryFeatureNames);
            $this->categoriesController->insertSubFeatures($featureSubFeatureNames);
        }

    }

}
?>