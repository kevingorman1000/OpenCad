<?php
/**
Open source CAD system for RolePlaying Communities.
Copyright (C) 2017 Shane Gill

This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

This program comes with ABSOLUTELY NO WARRANTY; Use at your own risk.
**/

require_once(__DIR__ . "/../oc-config.php");
require_once(__DIR__ . "/../includes/autoloader.inc.php");

if (isset($_GET['responder']))
{
    $identifier = htmlspecialchars($_GET['responder']);

    $users_data = new Users\UserService();

    $users_data->LogOutUser($identifier);
}


if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
session_unset();
session_destroy();
if(ENABLE_API_SECURITY === true)
    setcookie('aljksdz7', null, -1, "/");

header("Location: ".BASE_URL."/index.php?loggedOut=true");
exit();
?>