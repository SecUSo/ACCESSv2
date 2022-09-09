<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 27.07.2018
 * Time: 12:09
 */

class PrivacyPolicy
{
    private $sessionController;

    public function __construct()
    {
        $this->sessionController = new SessionController();
        $this->initTemplate();
    }

    private function initTemplate()
    {
        $data_pagetitle = "ACCESS BASIC - PRIVACY POLICY";
        $data_validSession = $this->sessionController->isSessionValid();
        $data_name = $this->sessionController->getName();
        $data_isAdmin = $this->sessionController->getIsAdmin();
        include_once("content/header.php");
        include_once("content/navigation.php");
        include_once("content/privacypolicy.php");
        include_once("content/footer.php");

    }
}
?>