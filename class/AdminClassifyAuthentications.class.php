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
 * Class AdminClassifyAuthentications
 * @desc This Class is used to create an Admin-Page that shows a Table used to classify Authentication Schemes in a
 *        Class of a Feature.
 * @var $isViewable - Determines if the Class has viewable Content
 * @var $classifyAuthenticationsController - The Controller the Class uses
 * @var $sessionController - The Session-Controller used by the Class
 */
class AdminClassifyAuthentications
{
    public static $isViewable = TRUE;
    private $classifyAuthenticationsController;
    private $sessionController;

    /**
     * AdminClassifyAuthentications constructor.
     */
    public function __construct()
    {
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->classifyAuthenticationsController = new ClassifyAuthenticationsController();
        $this->initTemplate();
    }

    /**
     * initTemplate()
     * @desc Will get the variable-content for the Template Pages and also loads the Template Pages.
     * @file content/header.php The Header pHTML File of the Template
     * @file content/navigation.php The Navigationbar of the Template
     * @file content/admin/adminclassifyauthentications.php The Maincontent Template File
     * @file content/footer.php The Footer pHTML File of the Template
     */
    private function initTemplate()
    {
        $parameters = $this->getParams();
        $data_className = $parameters[0];
        $data_featureName = $parameters[1];
        $data_pagetitle = "ACCESS BASIC - CLASSIFY AUTHENTICATIONS";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        $data_content = $this->classifyAuthenticationsController->getTableContent(
            $parameters[0],
            $parameters[1]
        );

        $data_tableHeader = $this->getTableHeader($data_content);

        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/admin/adminclassifyauthentications.php");
        include_once("content/footer.php");
    }

    /**
     * getParams()
     * @desc Checks for the GET-Variables 'class' and 'feature' which determine the selected feature and the selected
     *          class in which the Admin is going to classify the Authentication Schemes of the System.
     * @return array - An Array that has
     *                  [0] => Class-Name
     *                  [1] => Feature-Name
     *                 If both GET-Variables are set - else an empty Array will be returned.
     *
     */
    private function getParams()
    {
        if (isset($_GET['class']) && isset($_GET['feature'])) {
            return array(
                htmlspecialchars($_GET['class']),
                htmlspecialchars($_GET['feature'])
            );
        } else {
            return array();
        }
    }

    /**
     * getTableHeader($data)
     * @param $data - An Multidimensional Array that consists of all Authentication Scheme Names and their
     *                  Classification-Values.
     * @return array - An Array that has all Authentication Scheme names that occur in the input Array.
     */
    private function getTableHeader($data)
    {
        $output = array();
        $output[] = array_keys($data)[0];

        foreach ($data[array_keys($data)[0]] as $name => $val) {
            $output[] = $name;
        }

        return $output;
    }

}

?>