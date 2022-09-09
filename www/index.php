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


//error_reporting(E_ALL);
//ini_set("display_errors", 1);

spl_autoload_register(function ($class) {
    include 'class/' . $class . '.class.php';
});

$error_page = "ErrorPage";
$get_vars = array_keys($_GET);
$init_class = (!empty ($get_vars[0])) ? $get_vars[0] : "Index";

if (preg_match('/^[A-Za-z0-9_\-]+$/i', $init_class)) {
    if (class_exists($init_class))
        new $init_class;
    else
        new $error_page;
} else
    new $error_page;

?>
