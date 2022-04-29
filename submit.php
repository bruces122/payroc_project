<?php

require_once("functions/Shortener.php");

$urlShortener = new Shortener();

if (isset($_GET["url"]))
{
    return $urlShortener->validateReturnCode($_GET["url"]);
}

?>