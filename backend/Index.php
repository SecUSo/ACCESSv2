<?

function autoLoad($className)
{
  if (substr($className, 0, 8) == "Database")
    die("Index.php: bad input!");
  else if (file_exists('class/' . $className . '.class.php'))
    require_once ('class/' . $className . '.class.php');
  else
    return false;
}

spl_autoload_register('autoLoad');

$get_vars = array_keys($_GET);
$init_class = (!empty ($get_vars[0])) ? $get_vars[0] : "Index";

if (preg_match('/^[A-Za-z0-9_\-]+$/i', $init_class))
  new $init_class;
else
  echo "Index.php: bad input!";

?>
