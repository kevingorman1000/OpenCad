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
require(__DIR__."/../includes/autoloader.inc.php");

$users_data = new \Users\UserService();

if(!empty($_POST))
{
    session_start();
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $result = $users_data->LoginUser($email);

    $login_ok = false;

    if (password_verify($password, $result['password']))
    {
        $login_ok = true;
    }
    else
    {
        session_start();
        $_SESSION['loginMessageDanger'] = 'Invalid credentials';
        header("Location:".BASE_URL."/index.php");
        exit();
    }

    /* Check to see if they're approved to use the system
        0 = Pending Approval
        1 = Approved
        2 = Suspended
    */
    if ($result['approved'] == "0")
    {
        session_start();
        $_SESSION['loginMessageDanger'] = 'Your account hasn\'t been approved yet. Please wait for an administrator to approve your access request.';
        header("Location:".BASE_URL."/index.php");
        exit();
    }
    else if ($result['approved'] == "2")
    {
        /* TODO: Show reason why user is suspended */
        session_start();
        $_SESSION['loginMessageDanger'] = "Your account has been suspended by an administrator for: $suspended_reason";
        header("Location:".BASE_URL."/index.php");
        exit();
    }

    /* TODO: Handle password resets */
    $_SESSION['logged_in'] = 'YES';
    $_SESSION['id'] = $result['id'];
    $_SESSION['name'] = $result['name'];
    $_SESSION['email'] = $result['email'];
    $_SESSION['identifier'] = $result['identifier'];
    $_SESSION['callsign'] = $result['identifier']; //Set callsign to default to identifier until the unit changes it
    $_SESSION['admin_privilege'] = $result['admin_privilege']; //Set callsign to default to identifier until the unit changes it
    if(ENABLE_API_SECURITY === true)
        setcookie("aljksdz7", hash('md5', session_id().getApiKey()), time() + (86400 * 7), "/");
    header("Location:".BASE_URL."/dashboard.php");
}

?>