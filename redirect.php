<?php
include_once ('UrlShortner.class.php');
include_once ('config.php');

try
{
    $db = new UrlShortner ($database);
    header ("Location: " . $db->get ($_GET['url']));
}
catch (Exception $e)
{
    die ($e->getMessage ());
}
?>
