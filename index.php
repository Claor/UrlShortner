<?php
error_reporting (0);
include_once ('UrlShortner.class.php');
include_once ('config.php');

if (array_key_exists ("redirect", $_GET))
{
    try
    {
        $db = new UrlShortner ($database);
        header ("Location: " . $db->get ($_GET['redirect']));
    }
    catch (Exception $e)
    {
        die ($e->getMessage ());
    }
}
else if (array_key_exists ("preview", $_GET))
{
    try
    {
        $db = new UrlShortner ($database);
        echo $db->get ($_GET['preview']);
    }
    catch (Exception $e)
    {
        die ($e->getMessage ());
    }
}
?>
