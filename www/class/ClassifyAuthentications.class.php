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
 * Class ClassifyAuthentications
 * @desc output classification values for subclass
 */
class ClassifyAuthentications
{
    public static $isViewable = TRUE;

    private $classifyAuthenticationsController;

    public function __construct()
    {
        $this->classifyAuthenticationsController = new ClassifyAuthenticationsController();
        $this->initTemplate();
    }

    private function initTemplate()
    {
        $data_classes = $this->classifyAuthenticationsController->getTableContent($this->getParams()[0],$this->getParams()[1]);
        $data_classes_tableHeader = $this->getTableHeader($data_classes);

        include_once("content/classifyauthentications.php");
    }


    private function getTableHeader($data)
    {
        $output = array();
        $output[] = array_keys($data)[0];

        foreach ($data[array_keys($data)[0]] as $name => $val) {
            $output[] = $name;
        }

        return $output;
    }

    private function getParams()
    {
        if(isset($_GET['class']) && isset($_GET['feature'])){
            return array(
                htmlspecialchars($_GET['class']),
                htmlspecialchars($_GET['feature'])
            );
        }else{
            return array();
        }
    }
}

?>
