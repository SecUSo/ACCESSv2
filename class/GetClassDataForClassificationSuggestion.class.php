<?
/**
 * #####################################################################################################################
 * Copyright (C) 2017   Thomas Weber
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
 * Class GetClassDataForClassificationSuggestion
 * @desc API to get data for a classification suggestion
 */
class GetClassDataForClassificationSuggestion
{
    private $sessionController;
    private $classifyAuthenticationsController;
    private $contentController;
    private $feature;
    private $class;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->classifyAuthenticationsController = new ClassifyAuthenticationsController();
        $this->contentController = new ContentController();

        /*if (!$this->sessionController->getIsAdmin())
            die("error: no access!");*/

        $this->getParams();
        $this->init();
    }

    private function getParams()
    {
        if (isset($_GET['feature'])) $this->feature = $_GET["feature"];
        if (isset($_GET['class'])) $this->class = $_GET["class"];

    }

    private function init()
    {
        $data = $this->classifyAuthenticationsController->getTableContent($this->class, $this->feature);
        $scheme_descriptions = $this->contentController->getAllDescriptionWithName();
        $final_data = array(
            "classdata" => $data,
            "scheme_descriptions" => $scheme_descriptions
        );

        echo json_encode($final_data);
    }

}

?>