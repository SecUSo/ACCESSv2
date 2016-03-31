<?

/**
 * Class AdminRejectSuggestionThread
 * @desc JSON API: (admin) reject suggestion
 */
class AdminRejectSuggestionThread
{
    private $discussionController;
    private $sId;
    private $sContentType;

    public function __construct()
    {
        $this->discussionController = new DiscussionController();
        $this->sessionController = new SessionController();
        if (!$this->sessionController->getIsAdmin())
            die("error: no access!");
        $this->getParams();
        $this->processRejectSuggestion();
    }

    private function getParams()
    {
        if (isset($_POST['json_data'])) {
            $jsonArr = json_decode($_POST['json_data'], true);
            if (isset($jsonArr["id"])) $this->sId = $jsonArr["id"];
            if (isset($jsonArr["contentType"])) $this->sContentType = $jsonArr["contentType"];
        }
    }

    private function checkParams()
    {
        if ($this->sId == '' ||
            $this->sContentType == ''
        ) {
            return FALSE;
        }
        return TRUE;
    }

    /*
 * return code
 * 0 - success
 * 1 - wrong input
 * format: { "status": code }
 */
    private function processRejectSuggestion()
    {
        if (!$this->checkParams())
            $this->returnStatus(1);

        if ($this->sContentType == 'auth')
            $this->discussionController->rejecttAuthSuggestion($this->sId);
        else if ($this->sContentType == 'feature')
            $this->discussionController->rejectFeatureSuggestion($this->sId);
        else if ($this->sContentType == 'subfeature')
            $this->discussionController->rejectSubfeatureSuggestion($this->sId);

        $this->returnStatus(0);
    }

    private function returnStatus($success)
    {
        die('{"status": "' . $success . '"}');
    }
}

?>
