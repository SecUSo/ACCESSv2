<h2>References</h2>

<?php

$_GET['library']=1;
define('BIBTEXBROWSER_BIBTEX_LINKS',false); // no [bibtex] link by default
require_once('bibtexbrowser.local.php');
global $db;
$db = new BibDataBase();
$db->load('citations.bib');


// can also be $query = array('year'=>'.*');
$query = array('title'=>'.*');
$entries=$db->multisearch($query);
uasort($entries, 'compare_bib_entries');

foreach ($entries as $bibentry) {
    echo $bibentry->toHTML()."<br/>";
}

?>

</br>
</br>

