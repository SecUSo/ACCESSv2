<?
class Index
{
  private $dbController;
  private $testInstance;

  public function __construct()
  {
    $this->$dbController = new DatabaseController();
    $this->$testInstance = new TestInstance($this->$dbController);
    $this->initTemplate();
  }

  private function initTemplate()
  {
    include_once ("content/head.php");
    include_once ("content/navigation.php");
    include_once ("content/index.php");
    include_once ("content/footer.php");
  }
}
?>
