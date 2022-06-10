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
/*
    This file handles all actions for admin.php script
*/

require_once(__DIR__ . "/../oc-config.php");
require_once(__DIR__ . "/../oc-functions.php");
include_once(__DIR__ . "/../plugins/api_auth.php");
require_once(__DIR__ . "/../includes/autoloader.inc.php");

/**
 * Patch notes:
 * Adding the `else` to make a `else if` prevents the execution
 * of multiple functions at the same time by the same client
 *
 * Running multiple functions at the same time doesnt seem to
 * be a needed feature.
 */
if (isset($_GET['getCalls'])) {
    getActiveCalls();
} else if (isset($_GET['getMyCall'])) {
    getMyCall();
} else if (isset($_GET['getCallDetails'])) {
    getCallDetails();
} else if (isset($_GET['getAvailableUnits'])) {
    getAvailableUnits();
} else if (isset($_GET['getUnAvailableUnits'])) {
    getUnAvailableUnits();
} else if (isset($_POST['changeStatus'])) {
    changeStatus();
} else if (isset($_GET['getActiveUnits'])) {
    getActiveUnits();
} else if (isset($_GET['getActiveUnitsModal'])) {
    getActiveUnitsModal();
} else if (isset($_POST['logoutUser'])) {
    logoutUser();
} else if (isset($_POST['setTone'])) {
    setTone();
} else if (isset($_GET['checkTones'])) {
    checkTones();
} else if (isset($_GET['getDispatchers'])) {
    getDispatchers();
} else if (isset($_GET['getDispatchersMDT'])) {
    getDispatchersMDT();
} else if (isset($_POST['quickStatus'])) {
    quickStatus();
} else if (isset($_GET['getAOP'])) {
    getAOP();
} else if (isset($_GET['newApiKey'])) {
    $myRank = $_SESSION['admin_privilege'];

    if ($myRank == 2) {
        getApiKey(true);
        if (!isset($_SESSION)) {
            session_start();
        }
        session_unset();
        session_destroy();
        if (ENABLE_API_SECURITY === true)
            setcookie('aljksdz7', null, -1, "/");

        header("Location: " . BASE_URL . "/index.php?loggedOut=true");
        exit();
    } else {
        header("Location: " . BASE_URL . "/oc-admin/about.php");
        die();
    }
}

function quickStatus()
{
    $event = htmlspecialchars($_POST['event']);
    $callId = htmlspecialchars($_POST['callId']);
    if (!isset($_SESSION)) {
        session_start();
    }
    $callsign = $_SESSION['callsign'];

    switch ($event) {
        case "enroute":
            $narrativeAdd = date("Y-m-d H:i:s") . ': ' . $callsign . ': En-Route<br/>';

            $cad_data = new \CAD\CadManager();
            $cad_data->quickStatus($narrativeAdd, $callId);
            break;

        case "onscene":

            break;
    }
}

function getMyCall()
{
    if (!isset($_SESSION)) {
        session_start();
    }
    //First, check to see if they're on a call
    $uid = $_SESSION['id'];

    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getMyCall($uid);

    if (!$result) {
        echo '<div class="alert alert-info"><span>Not currently on a call</span></div>';
    } else {
        $result = '';

        //Figure out what call the user is on
        $result = $cad_data->getUsersCalls($uid);

        foreach ($result as $row) {
            $call_id = $row[0];
        }

        $result = $cad_data->getUserCallDetails($call_id);

        if (!$result) {
            echo '<div class="alert alert-info"><span>Not currently on a call</span></div>';
        } else {
            echo '<table id="activeCalls" class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th>Type</th>
                    <th>Call Type</th>
                    <th>Units</th>
                    <th>Location</th>
                    <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
            ';


            $counter = 0;
            foreach ($result as $row) {
                echo '
                <tr id="' . $counter . '">
                    <td>' . $row["call_type"] . '</td>';

                //Issue #28. Check if $row[1] == bolo. If so, change text color to orange
                if ($row[1] == "BOLO") {
                    echo '<td style="color:orange;">' . $row[1] . '</td>';
                    echo '<td><!--Leave blank--></td>';
                } else {
                    echo '<td>' . $row[1] . '</td>';
                    echo '
                            <td>';
                    getUnitsOnCall($row[0]);
                    echo '</td>';
                }


                echo '<td>' . $row[3] . '/' . $row[4] . '/' . $row[5] . '</td>';

                if (isset($_GET['type']) && $_GET['type'] == "responder") {
                    echo '
                        <td>
                            <button id="' . $row[0] . '" class="btn-link" name="call_details_btn" data-toggle="modal" data-target="#callDetails">Details</button>
                        </td>';
                } else {
                    echo '
                    <td>
                        <button id="' . $row[0] . '" class="btn-link" style="color: red;" value="' . $row[0] . '" onclick="clearCall(' . $row[0] . ')">Clear</button>
                        <button id="' . $row[0] . '" class="btn-link" name="call_details_btn" data-toggle="modal" data-target="#callDetails">Details</button>
                        <input name="uid" name="uid" type="hidden" value="' . $row[0] . '"/>
                    </td>';
                }

                echo '
                </tr>
                ';
                $counter++;
            }

            echo '
                </tbody>
                </table>
            ';
        }
    }
    $pdo = null;
}

//Checks to see if there are any active tones. Certain tones will add a session variable
function checkTones()
{

    $cad_data = new \CAD\CadManager();
    $result = $cad_data->checkTone();

    $encode = array();
    foreach ($result as $row) {
        // If the tone is set to active
        if ($row[2] == "1") {
            $encode[$row[1]] = "ACTIVE";
        } else if ($row[2] == "0") {
            $encode[$row[1]] = "INACTIVE";
        }
    }
    echo json_encode($encode);
}

function setTone()
{
    $tone = htmlspecialchars($_POST['tone']);
    $action = htmlspecialchars($_POST['action']);

    $status = null;
    switch ($action) {
        case "start":
            $status = '1';
            break;
        case "stop":
            $status = '0';
            break;
    }

    $cad_data = new \CAD\CadManager();
    $cad_data->setTone($status, $tone);

    if ($action == "start") {
        echo "SUCCESS START";
    } else {
        echo "SUCCESS STOP";
    }
}

function logoutUser()
{
    $identifier = htmlspecialchars($_POST['unit']);

    $users_data = new Users\UserService();

    $users_data->LogOutUser($identifier);
}

function changeStatus()
{
    $unit = htmlspecialchars($_POST['unit']);
    $status = htmlspecialchars($_POST['status']);
    $statusId = null;
    $statusDet = null;
    $onCall = false;

    switch ($status) {
        case "statusMeal":
            $statusId = '0';
            $statusDet = '4';
            break;
        case "statusOther":
            $statusId = '0';
            $statusDet = '2';
            break;
        case "statusAvailBusy":
            $statusId = '1';
            $statusDet = '1';
            $onCall = true;
            break;
        case "statusUnavailBusy":
            $statusId = '6';
            $statusDet = '6';
            $onCall = true;
            break;
        case "statusSig11":
            $statusId = '1';
            $statusDet = '5';
            break;
        case "statusArrivedOC":
            $statusId = '7';
            $statusDet = '7';
            $onCall = true;
            break;
        case "statusTransporting":
            $statusId = '8';
            $statusDet = '8';
            $onCall = true;
            break;

        case "10-52":
            $statusId = '8';
            $statusDet = '8';
            $onCall = true;
            break;
        case "10-23":
            $statusId = '7';
            $statusDet = '7';
            $onCall = true;
            break;
        case "10-65":
            $statusId = '8';
            $statusDet = '8';
            break;
        case "10-8":
            $statusId = '1';
            $statusDet = '1';
            $onCall = true;
            break;
        case "10-7":
            $statusId = '6';
            $statusDet = '6';
            $onCall = false;
            break;
        case "10-6":
            $statusId = '0';
            $statusDet = '2';
            break;
        case "10-5":
            $statusId = '0';
            $statusDet = '4';
            break;
        case "sig11":
            $statusId = '1';
            $statusDet = '5';
            break;
    }

    $cad_data = new CAD\CadManager();
    $result = $cad_data->changeStatus($statusId, $statusDet, $unit);

    if ($onCall) {
        $result = $cad_data->selectActiveCallUsers($unit);

        $callId = "";
        foreach ($result as $row) {
            $callId = $row[0];
        }
        $result = $cad_data->selectActiveUsers($unit);
        foreach ($result as $row) {
            $callsign = $row[0];
        }

        //Update the call_narrative to say they were cleared
        $narrativeAdd = date("Y-m-d H:i:s") . ': Unit Cleared: ' . $callsign . '<br/>';

        $cad_data->updateCallNarrative($narrativeAdd, $callId);

        $cad_data->deleteActiveCall($unit);
    }
}

function deleteDispatcher()
{
    $cad_data = new \CAD\CadManager();
    $cad_data->deleteDispatcher($_SESSION['identifier']);
}

function setDispatcher($dep)
{
    $status = "0";
    switch ($dep) {
        case "1":
            $status = "0";
            break;
        case "2":
            $status = "1";
            break;
    }

    deleteDispatcher();

    $cad_data = new \CAD\CadManager();
    $cad_data->setDispatcher($_SESSION['identifier'], $_SESSION['identifier'], $status);
}

function getAOP()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getAOP();

    if (!$result) {
        echo "NO AOP SET";
    } else {
        foreach ($result as $row) {
            echo 'AOP: ' . $row[0] . ' ';
        }
    }
}

function getDispatchers()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getDispatchers();

    if (!$result) {
        echo "<div class=\"alert alert-danger\"><span>No available units</span></div>";
    } else {

        echo '
            <table id="dispatchersTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Identifier</th>
                </tr>
            </thead>
            <tbody>
        ';
        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row['identifier'] . '</td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function getDispatchersMDT()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getDispatchers();

    if (!$result) {
        $dispatcher = "false";
    } else {
        $dispatcher = "true";
    }
}

function setUnitActive($dep)
{
    $identifier = $_SESSION['identifier'];
    $uid = $_SESSION['id'];
    $status = "";
    switch ($dep) {
        case "1":
            $status = "1";
            break;
        case "2":
            $status = "2";
            break;
    }

    $cad_data = new \CAD\CadManager();
    $cad_data->setUnitActive($identifier, $identifier, $status, $uid);
}

function getAvailableUnits()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getAvailableUnits();



    if (!$result) {
        echo "<div class=\"alert alert-danger\"><span>No available units</span></div>";
    } else {

        echo '
            <table id="activeUsers" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Identifier</th>
                <th>Callsign</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
        ';


        $counter = 0;
        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[0] . '</td>
                <td>' . $row[1] . '</td>
                <td>
                <div class="dropdown"><button class="btn btn-link dropdown-toggle nopadding" type="button" data-toggle="dropdown">Status <span class="caret"></span></button><ul class="dropdown-menu">
                    <li><a id="statusMeal' . $counter . '" class="statusMeal ' . $row[0] . '" onclick="testFunction(this);">10-5/Meal Break</a></li>
                    <li><a id="statusOther' . $counter . '" class="statusOther ' . $row[0] . '" onclick="testFunction(this);">10-6/Other</a></li>
                    <li><a id="statusSig11' . $counter . '" class="statusSig11 ' . $row[0] . '" onclick="testFunction(this);">Signal 11</a></li>
                </ul></div>

                </td>
                <input name="uid" type="hidden" value=' . $row[0] . ' />
            </tr>
            ';
            $counter++;
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function getUnAvailableUnits()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getUnAvailableUnits();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>Units are all avaliable</span></div>";
    } else {
        echo '
                <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th>Identifier</th>
                    <th>Callsign</th>
                    <th>Status</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
            ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[0] . '</td>
                <td>' . $row[1] . '</td>
                <td>';

            getIndividualStatus($row[1]);

            echo '</td>

                <td>
                <a id="logoutUser" class="nopadding logoutUser ' . $row[0] . '" onclick="logoutUser(this);" style="color:red; cursor:pointer;">Logout</a>&nbsp;&nbsp;&nbsp;
                <div class="dropdown"><button class="btn btn-link dropdown-toggle nopadding" style="display: inline-block; vertical-align:top;" type="button" data-toggle="dropdown">Status <span class="caret"></span></button><ul class="dropdown-menu">
                    <li><a id="statusAvail" class="statusAvailBusy ' . $row[0] . '" onclick="testFunction(this);">10-8/Available</a></li>
                </ul></div>
                </td>
                <input name="uid" type="hidden" value=' . $row[0] . ' />
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function getIndividualStatus($callsign)
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getIndividualStatus($callsign);

    $statusDetail = "";
    foreach ($result as $row) {
        $statusDetail = $row[0];
    }

    $result = $cad_data->getIndividualStatusText($statusDetail);

    $statusText = "";
    foreach ($result as $row) {
        $statusText = $row[0];
    }
    echo $statusText;
}

function getIncidentType()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getIncidentType();

    foreach ($result as $row) {
        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
    }
}


function getStreet()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getStreet();


    foreach ($result as $row) {
        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
    }
}

function getActiveUnits()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getActiveUnits();

    $encode = array();
    foreach ($result as $row) {
        $encode[$row[0]] = $row[0];
    }

    echo json_encode($encode);
}

function getActiveUnitsModal()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getActiveUnits();

    $encode = array();
    foreach ($result as $row) {
        $encode[$row[1]] = $row[0];
    }

    echo json_encode($encode);
}

function getActiveCalls()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getActiveCalls();

    if (!$result) {
        echo '<div class="alert alert-info"><span>No active calls</span></div>';
    } else {
        echo '<table id="activeCalls" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Call ID</th>
                <th>Call Type</th>
                <th>Units</th>
                <th>Location</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';


        $counter = 0;
        foreach ($result as $row) {
            echo '
            <tr id="' . $counter . '">
                <td>' . $row[0] . '</td>';

            //Issue #28. Check if $row[1] == bolo. If so, change text color to orange
            if ($row[1] == "BOLO") {
                echo '<td style="color:orange;">' . $row[1] . '</td>';
                echo '<td><!--Leave blank--></td>';
            } else {
                echo '<td>' . $row[1] . '</td>';
                echo '
                        <td>';
                getUnitsOnCall($row[0]);
                echo '</td>';
            }


            echo '<td>' . $row[3] . '/' . $row[4] . '/' . $row[5] . '</td>';

            if (isset($_GET['type']) && $_GET['type'] == "responder") {
                echo '
                    <td>
                        <button id="' . $row[0] . '" class="btn-link" name="call_details_btn" data-toggle="modal" data-target="#callDetails">Details</button>
                    </td>';
            } else {
                echo '
                <td>
                    <button id="' . $row[0] . '" class="btn-link" style="color: red;" value="' . $row[0] . '" onclick="clearCall(' . $row[0] . ')">Clear</button>
                    <button id="' . $row[0] . '" class="btn-link" name="call_details_btn" data-toggle="modal" data-target="#callDetails">Details</button>
                    <input id="' . $row[0] . '" type="submit" name="assign_unit" data-toggle="modal" data-target="#assign" class="btn-link ' . $row[0] . '" value="Assign"/>
                    <input name="uid" name="uid" type="hidden" value="' . $row[0] . '"/>
                </td>';
            }

            echo '
            </tr>
            ';
            $counter++;
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function getActivePersonBOLO()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getActivePersonBOLO();

    if (!$result) {
        echo '<div class="alert alert-info"><span>No active calls</span></div>';
    } else {
        echo '<table id="activeCalls" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Type</th>
                <th>Call Type</th>
                <th>Units</th>
                <th>Location</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';


        $counter = 0;
        foreach ($result as $row) {
            echo '
            <tr id="' . $counter . '">
                <td>' . $row[0] . '</td>';

            //Issue #28. Check if $row[1] == bolo. If so, change text color to orange
            if ($row[1] == "BOLO") {
                echo '<td style="color:orange;">' . $row[1] . '</td>';
                echo '<td><!--Leave blank--></td>';
            } else {
                echo '<td>' . $row[1] . '</td>';
                echo '
                        <td>';
                getUnitsOnCall($row[0]);
                echo '</td>';
            }


            echo '<td>' . $row[2] . '/' . $row[3] . '/' . $row[4] . '</td>';

            if (isset($_GET['type']) && $_GET['type'] == "responder") {
                echo '
                    <td>
                        <button id="' . $row[0] . '" class="btn-link" name="call_details_btn" data-toggle="modal" data-target="#callDetails">Details</button>
                    </td>';
            } else {
                echo '
                <td>
                    <button id="' . $row[0] . '" class="btn-link" style="color: red;" value="' . $row[0] . '" onclick="clearCall(' . $row[0] . ')">Clear</button>
                    <button id="' . $row[0] . '" class="btn-link" name="call_details_btn" data-toggle="modal" data-target="#callDetails">Details</button>
                    <input id="' . $row[0] . '" type="submit" name="assign_unit" data-toggle="modal" data-target="#assign" class="btn-link ' . $row[0] . '" value="Assign"/>
                    <input name="uid" name="uid" type="hidden" value="' . $row[0] . '"/>
                </td>';
            }

            echo '
            </tr>
            ';
            $counter++;
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function getUnitsOnCall($callId)
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getUnitsOnCall($callId);

    $units = "";
    if (!$result) {
        $units = '<span style="color: red;">No Assigned Units!</span>';
    } else {
        foreach ($result as $row) {
            $units = $units . '' . $row[2] . ', ';
        }
    }

    echo $units;
}

function getCallDetails()
{
    $callId = htmlspecialchars($_GET['callId']);

    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getCallDetails($callId);

    $encode = array();
    foreach ($result as $row) {
        $encode["call_id"] = $row[0];
        $encode["call_type"] = $row[1];
        $encode["call_street1"] = $row[3];
        $encode["call_street2"] = $row[4];
        $encode["call_street3"] = $row[5];
        $encode["narrative"] = $row[6];
    }

    echo json_encode($encode);
}

function getCivilianNamesOption()
{
    $cad_data = new \CAD\CadManager();
    $result = $cad_data->getCivilianNamesOption();

    foreach ($result as $row) {
        echo "<option value=" . $row[0] . ">" . $row[1] . "</option>";
    }
}

function getCitations()
{

    $cit_data = new \Citations\CitationManager();
    $result = $cit_data->getCitations();

    foreach ($result as $row) {
        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
    }
}

/**#@+
 * function getVehicleMakes()
 *
 * Querys database to retrieve all vehicle makes.
 *
 * @since 1.0a RC2
 */
function getVehicleMakes()
{

    $veh_data = new \Vehicles\vehicleManager();
    $result = $veh_data->getVehicleMakes();

    foreach ($result as $row) {
        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
    }
}

/**#@+
 * function getVehicleModels()
 *
 * Querys database to retrieve all vehicle models.
 *
 * @since 1.0a RC2
 */
function getVehicleModels()
{
    $veh_data = new \Vehicles\vehicleManager();
    $result = $veh_data->getVehicleModels();

    foreach ($result as $row) {
        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
    }
}

/**#@+
 * function getVehicle()
 *
 * Querys database to retrieve all vehicle models.
 *
 * @since 1.0a RC2
 */
function getVehicle()
{
    $veh_data = new \Vehicles\vehicleManager();
    $result = $veh_data->getVehicles();

    foreach ($result as $row) {
        echo '<option value="' . $row[1] . ' ' . $row[2] . '">' . $row[1] . '-' . $row[2] . '</option>';
    }
}

/**#@+
 * function getGenders()
 *
 * Querys database to retrieve genders.
 *
 * @since 1.0a RC2
 *
function getGenders()
{
    try{
        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    } catch(PDOException $ex)
    {
        $_SESSION['error'] = "Could not connect -> ".$ex->getMessage();
        $_SESSION['error_blob'] = $ex;
        header('Location: '.BASE_URL.'/plugins/error/index.php');
        die();
    }

    $result = $pdo->query("SELECT DISTINCT ".DB_PREFIX."genders.genders FROM ".DB_PREFIX."genders");

    if (!$result)
    {
        $_SESSION['error'] = $pdo->errorInfo();
        header('Location: '.BASE_URL.'/plugins/error/index.php');
        die();
    }
    $pdo = null;

    $num_rows = $result->rowCount();

    foreach($result as $row)
    {
        echo '<option value="'.$row[0].'">'.$row[0].'</option>';
    }
}

/**#@+
 * function getColors()
 *
 * Querys database to retrieve genders.
 *
 * @since 1.0a RC2
 */
function getColors()
{
    $veh_data = new \Vehicles\vehicleManager();
    $result = $veh_data->getVehicleColors();

    foreach ($result as $row) {
        echo '<option value="' . $row[0] . '-' . $row[1] . '">' . $row[0] . '-' . $row[1] . '</option>';
    }
}

function getCivilianNames()
{
    $civ_data = new \Civilian\CivilianManager();
    $result = $civ_data->getCivilianNames();

    foreach ($result as $row) {
        echo "<option value=\"$row[0]\">$row[1]</option>";
    }
}

function callCheck()
{
    $uid = $_SESSION['id'];
    $identifier = $_SESSION['identifier'];

    $cad_data = new \CAD\CadManager();
    $civ_data = new \Civilian\CivilianManager();
    $result = $cad_data->callCheck($uid);

    if (!$result) {
        $civ_data->replaceActiveUsers($identifier, $uid, "6");
    } else {
        $civ_data->replaceActiveUsers($identifier, $uid, "3");
    }
}

function getWeapons()
{
    $weapon_data = new \Weapons\WeaponManager();
    $result = $weapon_data->getWeapons();

    foreach ($result as $row) {
        echo '<option value="' . $row[1] . ' ' . $row[2] . '">' . $row[1] . '&#8212;' . $row[2] . '</option>';
    }
}

function rms_warnings()
{
    $warning_data = new \Warnings\WarningManager();
    $result = $warning_data->rms_warnings();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are currently no warnings in the NCIC Database</span></div>";
    } else {
        echo '
            <table id="rms_warnings" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Name</th>
                <th>Warning Name</th>
                <th>Issued On</th>
                <th>Issued By</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[0] . '</td>
                <td>' . $row[2] . '</td>
                <td>' . $row[3] . '</td>
                <td>' . $row[4] . '</td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function rms_citations()
{
    $citation_data = new \Citations\CitationManager();
    $result = $citation_data->rms_citations();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are currently no citations in the NCIC Database</span></div>";
    } else {
        echo '
            <table id="rms_citations" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Name</th>
                <th>Citation Name</th>
				<th>Citation Amount</th>
                <th>Issued On</th>
                <th>Issued By</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[0] . '</td>
                <td>' . $row[2] . '</td>
                <td>' . $row[3] . '</td>
                <td>' . $row[4] . '</td>
                <td>' . $row[5] . '</td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function rms_arrests()
{
    $civ_data = new \Civilian\CivilianManager();
    $result = $civ_data->rms_arrests();
    
    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are currently no arrests in the NCIC Database</span></div>";
    } else {
        echo '
            <table id="rms_arrests" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Name</th>
                <th>Arrest Reason</th>
				<th>Arrest Amount</th>
                <th>Issued On</th>
                <th>Issued By</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[0] . '</td>
                <td>' . $row[2] . '</td>
                <td>' . $row[3] . '</td>
                <td>' . $row[4] . '</td>
                <td>' . $row[5] . '</td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function rms_warrants()
{
    $warrant_data = new \Warrants\WarrantManager();
    $result = $warrant_data->rms_warrants();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are currently no warrants in the NCIC Database</span></div>";
    } else {
        echo '
            <table id="rms_warrants" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Status</th>
                <th>Name</th>
                <th>Warrant Name</th>
                <th>Issued On</th>
                <th>Expires On</th>
                <th>Issuing Agency</th>

                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[6] . '</td>
                <td>' . $row[7] . '</td>
                <td>' . $row[2] . '</td>
                <td>' . $row[5] . '</td>
                <td>' . $row[1] . '</td>
                <td>' . $row[3] . '</td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}
