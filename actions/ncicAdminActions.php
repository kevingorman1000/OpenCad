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
include(__DIR__ . '/generalActions.php');
require_once(__DIR__ . "../../includes/autoloader.inc.php");

/* Handle POST requests */
/**
 * Patch notes:
 * Adding the `else` to make a `else if` prevents the execution
 * of multiple functions at the same time by the same client
 *
 * Running multiple functions at the same time doesnt seem to
 * be a needed feature.
 */
if (isset($_POST['delete_citation'])){
    delete_citation();
}else if (isset($_POST['delete_arrest'])){
    delete_arrest();
}else if (isset($_POST['delete_warning'])){
    delete_warning();
}else if (isset($_POST['delete_warrant'])){
    delete_warrant();
}else if (isset($_POST['delete_name'])){
    delete_name();
}else if (isset($_POST['delete_plate'])){
    delete_plate();
}else if (isset($_POST['delete_weapon'])){
    delete_weapon();
}else if (isset($_POST['create_name'])){
    create_name();
}else if (isset($_POST['create_plate'])){
    create_plate();
}else if (isset($_POST['create_warrant'])){
    create_warrant();
}else if (isset($_POST['create_citation'])){
    create_citation();
}else if (isset($_POST['edit_name'])){
    edit_name();
}else if (isset($_POST['edit_plate'])){
    edit_plate();
}else if (isset($_POST['editid'])){
    editnameid();
}else if (isset($_POST['edit_plateid'])){
    editplateid();
}

function ncicGetNames()
{
    $civ_data = new Civilian\CivilianManager();

    $result = $civ_data->ncicGetNamesAdmin();

    if(!$result)
    {
        echo "<div class=\"alert alert-info\"><span>There are currently no names in the NCIC Database</span></div>";
    }
    else
    {
        echo '
            <table id="ncic_names" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Name</th>
                <th>DOB</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Race</th>
                <th>Build</th>
                <th>Hair Color</th>
                <th>DL Status</th>
				<th>Weapon Status</th>
				<th>Deceased</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach($result as $row)
        {
            echo '
            <tr>
                <td>'.$row['name'].'</td>
                <td>'.$row['dob'].'</td>
                <td>'.$row['address'].'</td>
                <td>'.$row['gender'].'</td>
                <td>'.$row['race'].'</td>
                <td>'.$row['build'].'</td>
                <td>'.$row['hair_color'].'</td>
                <td>'.$row['dl_status'].'</td>
                <td>'.$row['weapon_permit'].'</td>
				<td>'.$row['deceased'].'</td>
                <td>
                    <button name="edit_name" data-toggle="modal" data-target="#IdentityEditModal" id="edit_nameBtn" data-id='.$row[0].' class="btn btn-xs btn-link">Edit</button>
                    <form action="".BASE_URL."/actions/ncicAdminActions.php" method="post">
                    <input name="delete_name" type="submit" class="btn btn-xs btn-link" style="color: red;" value="Delete"/>
                    <input name="uid" type="hidden" value='.$row[0].' />
                    </form>
                </td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function ncicGetPlates()
{
    $civ_data = new Civilian\CivilianManager();

    $result = $civ_data->ncicGetPlatesAdmin();

    if(!$result)
    {
        echo "<div class=\"alert alert-info\"><span>There are currently no vehicles in the NCIC Database</span></div>";
    }
    else
    {
        echo '
            <table id="ncic_plates" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Name</th>
                <th>Plate</th>
                <th>Reg. State</th>
                <th>Make</th>
                <th>Model</th>
                <th>Color</th>
                <th>Ins. Status</th>
                <th>Flags</th>
                <th>Notes</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach($result as $row)
        {
            echo '
            <tr>
                <td>'.$row['name'].'</td>
                <td>'.$row['veh_plate'].'</td>
                <td>'.$row['veh_reg_state'].'</td>
                <td>'.$row['veh_make'].'</td>
                <td>'.$row['veh_model'].'</td>
                <td>'.$row['veh_pcolor'].'/'.$row['veh_scolor'].'</td>
                <td>'.$row['veh_insurance'].' / '.$row['veh_insurance_type'].'</td>
                <td>'.$row['flags'].'</td>
                <td>'.$row['notes'].'</td>
                <td>
                    <form action="".BASE_URL."/actions/ncicAdminActions.php" method="post">
                    <button name="edit_plate" data-toggle="modal" data-target="#editPlateModal" id="edit_plateBtn" data-id='.$row[0].' class="btn btn-xs btn-link">Edit</button>
                    <input name="delete_plate" type="submit" class="btn btn-xs btn-link" style="color: red;" value="Delete" enabled/>
                    <input name="vehid" type="hidden" value='.$row[0].' />
                    </form>
                </td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function ncicGetWeapons()
{
    $civ_data = new Civilian\CivilianManager();

    $result = $civ_data->ncicGetWeaponsAdmin();

    if(!$result)
    {
        echo "<div class=\"alert alert-info\"><span>There are currently no weapons in the NCIC Database</span></div>";
    }
    else
    {
        echo '
            <table id="ncic_names" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Name</th>
                <th>Weapon Type</th>
                <th>Weapon Name</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach($result as $row)
        {
            echo '
            <tr>
                <td>'.$row['name'].'</td>
                <td>'.$row['weapon_type'].'</td>
                <td>'.$row['weapon_name'].'</td>
                <td>
                    <form action="".BASE_URL."/actions/ncicAdminActions.php" method="post">
                    <input name="delete_weapon" type="submit" class="btn btn-xs btn-link" style="color: red;" value="Delete"/>
                    <input name="weaid" type="hidden" value='.$row[0].' />
                    </form>
                </td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function delete_weapon()
{
    $weaid = htmlspecialchars($_POST['weaid']);

    $civ_data = new Civilian\CivilianManager();

    $civ_data->delete_weaponAdmin($weaid);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['weaponMessage'] = '<div class="alert alert-success"><span>Successfully removed civilian weapon</span></div>';
    header("Location: ".BASE_URL."/oc-admin/ncicAdmin.php");
}

function delete_citation()
{
    $cid = htmlspecialchars($_POST['cid']);

    $civ_data = new Civilian\CivilianManager();

    $civ_data->delete_citationAdmin($cid);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['citationMessage'] = '<div class="alert alert-success"><span>Successfully removed citation</span></div>';
    header("Location: ".BASE_URL."/oc-admin/ncicAdmin.php");
}

function delete_arrest()
{
    $aid = htmlspecialchars($_POST['aid']);

    $civ_data = new Civilian\CivilianManager();

    $civ_data->delete_arrestAdmin($aid);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['arrestMessage'] = '<div class="alert alert-success"><span>Successfully removed arrest</span></div>';
    header("Location: ".BASE_URL."/oc-admin/ncicAdmin.php");
}

function delete_warning()
{
    $wgid = htmlspecialchars($_POST['wgid']);

    $civ_data = new Civilian\CivilianManager();

    $civ_data->delete_warningAdmin($wgid);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['warningMessage'] = '<div class="alert alert-success"><span>Successfully removed warning</span></div>';
    header("Location: ".BASE_URL."/oc-admin/ncicAdmin.php");
}

function delete_warrant()
{
    $wid = htmlspecialchars($_POST['wid']);

    $civ_data = new Civilian\CivilianManager();

    $civ_data->delete_warrantAdmin($wid);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['warrantMessage'] = '<div class="alert alert-success"><span>Successfully removed warrant</span></div>';
    header("Location: ".BASE_URL."/oc-admin/ncicAdmin.php");
}

function ncic_arrests()
{
    $civ_data = new Civilian\CivilianManager();

    $result = $civ_data->ncic_arrestsAdmins();


    if(!$result)
    {
        echo "<div class=\"alert alert-info\"><span>There are currently no arrests in the NCIC Database</span></div>";
    }
    else
    {
        echo '
            <table id="ncic_arrests" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Name</th>
                <th>Arrest Reason</th>
                <th>Arrest Fine</th>
                <th>Issued On</th>
                <th>Issued By</th>
                <th>Issuing Agency</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach($result as $row)
        {
            echo '
            <tr>
                <td>'.$row['name'].'</td>
                <td>'.$row['arrest_reason'].'</td>
                <td>'.$row['arrest_fine'].'</td>
                <td>'.$row['issued_date'].'</td>
                <td>'.$row['issued_by'].'</td>
                <td>'.$row['issued_by_agency'].'</td>
                <td>
                    <form action="".BASE_URL."/actions/ncicAdminActions.php" method="post">
                    <input name="delete_arrest" type="submit" class="btn btn-xs btn-link" style="color: red;" value="Remove"/>
                    <input name="aid" type="hidden" value='.$row[0].' />
                    </form>
                </td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function ncic_warrants()
{
    $civ_data = new Civilian\CivilianManager();

    $result = $civ_data->ncic_warrantsAdmin();

    if(!$result)
    {
        echo "<div class=\"alert alert-info\"><span>There are currently no warrants in the NCIC Database</span></div>";
    }
    else
    {
        echo '
            <table id="ncic_warrants" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Status</th>
                <th>Name</th>
                <th>Warrant Name</th>
                <th>Issued On</th>
                <th>Expires On</th>
                <th>Issuing Agency</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach($result as $row)
        {
            echo '
            <tr>
                <td>'.$row['status'].'</td>
                <td>'.$row['name'].'</td>
                <td>'.$row['warrant_name'].'</td>
                <td>'.$row['issued_date'].'</td>
                <td>'.$row['expiration_date'].'</td>
                <td>'.$row['issuing_agency'].'</td>
                <td>
                    <form action="".BASE_URL."/actions/ncicAdminActions.php" method="post">
                    ';
                        if ($row[6] == "Active")
                        {
                            echo '<input name="serveWarrant" type="submit" class="btn btn-xs btn-link" value="Serve" disabled/>';
                        }
                        else
                        {
                            //Do Nothing
                        }
                    echo '
                    <input name="delete_warrant" type="submit" class="btn btn-xs btn-link" style="color: red;" value="Expunge"/>
                    <input name="wid" type="hidden" value='.$row[0].' />
                    </form>
                </td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function ncic_citations()
{
    $civ_data = new Civilian\CivilianManager();

    $result = $civ_data->ncic_citationsAdmin();
    
    if(!$result)
    {
        echo "<div class=\"alert alert-info\"><span>There are currently no citations in the NCIC Database</span></div>";
    }
    else
    {
        echo '
            <table id="ncic_citations" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Name</th>
                <th>Citation Name</th>
                <th>Citation Fine</th>
                <th>Issued On</th>
                <th>Issued By</th>
                <th>Issuing Agency</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach($result as $row)
        {
            echo '
            <tr>
                <td>'.$row['name'].'</td>
                <td>'.$row['citation_name'].'</td>
                <td>'.$row['citation_fine'].'</td>
                <td>'.$row['issued_date'].'</td>
                <td>'.$row['issued_by'].'</td>
                <td>'.$row['issued_by_agency'].'</td>
                <td>
                    <form action="".BASE_URL."/actions/ncicAdminActions.php" method="post">
                    <input name="delete_citation" type="submit" class="btn btn-xs btn-link" style="color: red;" value="Remove"/>
                    <input name="cid" type="hidden" value='.$row[0].' />
                    </form>
                </td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function ncic_warnings()
{
    $civ_data = new Civilian\CivilianManager();

    $result = $civ_data->ncic_warningsAdmin();
    
    if(!$result)
    {
        echo "<div class=\"alert alert-info\"><span>There are currently no warnings in the NCIC Database</span></div>";
    }
    else
    {
        echo '
            <table id="ncic_warnings" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Name</th>
                <th>Warning Name</th>
                <th>Issued On</th>
                <th>Issued By</th>
                <th>Issuing Agency</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach($result as $row)
        {
            echo '
            <tr>
                <td>'.$row['name'].'</td>
                <td>'.$row['warning_name'].'</td>
                <td>'.$row['issued_date'].'</td>
                <td>'.$row['issued_by'].'</td>
                <td>'.$row['issued_by_agency'].'</td>
                <td>
                    <form action="".BASE_URL."/actions/ncicAdminActions.php" method="post">
                    <input name="delete_warning" type="submit" class="btn btn-xs btn-link" style="color: red;" value="Remove"/>
                    <input name="wgid" type="hidden" value='.$row[0].' />
                    </form>
                </td>
            </tr>
            ';
        }

        echo '
            </tbody>
            </table>
        ';
    }
}

function getUserList()
{

    $civ_data = new Civilian\CivilianManager();

    $result = $civ_data->getUserList();

	foreach($result as $row)
	{
		echo "<option value=\"$row[0]\">$row[1] $row[2]</option>";
	}
}

function delete_name()
{
    $uid = htmlspecialchars($_POST['uid']);
    
    $civ_data = new Civilian\CivilianManager();

    $civ_data->delete_name($uid);
    
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['nameMessage'] = '<div class="alert alert-success"><span>Successfully removed civilian name</span></div>';
    header("Location: ".BASE_URL."/oc-admin/ncicAdmin.php#name_panel");
}

function delete_plate()
{
    $vehid = htmlspecialchars($_POST['vehid']);

    $civ_data = new Civilian\CivilianManager();

    $civ_data->delete_name($vehid);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['plateMessage'] = '<div class="alert alert-success"><span>Successfully removed civilian plate</span></div>';
    header("Location: ".BASE_URL."/oc-admin/ncicAdmin.php#plate_panel");
}

function edit_name()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;

    $fullName = htmlspecialchars($_POST['civNameReq']);
    $firstName = explode(" ", $fullName) [0];
    $lastName = explode(" ", $fullName) [1];
    
    $civ_data = new Civilian\CivilianManager();

    //Set first name to all lowercase
    $firstName = strtolower($firstName);
    //Remove all special characters
    $firstName = preg_replace('/[^A-Za-z0-9\-]/', '', $firstName);
    //Set first letter to uppercase
    $firstName = ucfirst($firstName);

    //Set last name to all lowercase
    $lastName = strtolower($lastName);
    //Remove all special characters
    $lastName = preg_replace('/[^A-Za-z0-9\-]/', '', $lastName);
    //Set first letter to uppercase
    $lastName = ucfirst($lastName);
	
	$name = $firstName . ' ' . $lastName;

    $result = $civ_data->edit_name($name);
    
    if (!$result)
    {
        $_SESSION['identityMessage'] = '<div class="alert alert-danger"><span>Name already exists</span></div>';

        sleep(1);
        header("Location:".BASE_URL."/oc-admin/ncicAdmin.php");
    }

    // If name doesn't exist, add it to ncic_requests table
    //Who submitted it
    $submittedByName = $_SESSION['name'];
    $submitttedById = $_SESSION['id'];
    //Submission Data
    $name;
    $dob = htmlspecialchars($_POST['civDobReq']);
    $address = htmlspecialchars($_POST['civAddressReq']);
    $sex = htmlspecialchars($_POST['civSexReq']);
    $race = htmlspecialchars($_POST['civRaceReq']);
    $dlstatus = htmlspecialchars($_POST['civDL']);
    $hair = htmlspecialchars($_POST['civHairReq']);
    $build = htmlspecialchars($_POST['civBuildReq']);
	$weapon = htmlspecialchars($_POST['civWepStat']);
	$deceased = htmlspecialchars($_POST['civDec']);
    $editid = htmlspecialchars($_POST['Edit_id']);

    $result = $civ_data->edit_name($name, $dob, $address, $sex, $race, $dlstatus, $hair, $build, $weapon, $deceased, $editid);

    $_SESSION['identityMessage'] = '<div class="alert alert-success"><span>Successfully Update an identity</span></div>';
    
    header("Location:".BASE_URL."/oc-admin/ncicAdmin.php#name_panel");
}

function edit_plate()
{
    $civ_data = new Civilian\CivilianManager();

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    
    $plate = htmlspecialchars($_POST['veh_plate']);
    
    //Remove all spaces from plate
    $plate = str_replace(' ', '', $plate);
    //Set plate to all uppercase
    $plate = strtoupper($plate);
    //Remove all hyphens
    $plate = str_replace('-', '', $plate);
    //Remove all special characters
    $plate = preg_replace('/[^A-Za-z0-9\-]/', '', $plate);
    
    $vehicle = htmlspecialchars($_POST['veh_make_model']);
    $veh_make = explode(" ", $vehicle) [0];
    $veh_model = explode(" ", $vehicle) [1];
    
    $uid = $_SESSION['id'];

    $submittedById = $_SESSION['id'];
    $userId = htmlspecialchars($_POST['civilian_names']);
    $veh_plate = $plate;
    $veh_pcolor = htmlspecialchars($_POST['veh_pcolor']);
    $veh_scolor = htmlspecialchars($_POST['veh_scolor']);
    $veh_insurance = htmlspecialchars($_POST['veh_insurance']);
    $flags = htmlspecialchars($_POST['flags']);
    $veh_reg_state = htmlspecialchars($_POST['veh_reg_state']);
    $notes = htmlspecialchars($_POST['notes']);
    $plate_id = htmlspecialchars($_POST['Edit_plateId']);

    $civ_data->edit_name($userId, $veh_plate, $veh_make, $veh_model, $veh_pcolor, $veh_scolor, $veh_insurance, $flags, $veh_reg_state, $notes, $plate_id);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['plateMessage'] = '<div class="alert alert-success"><span>Successfully Updated plate to the database</span></div>';

    header("Location:".BASE_URL."/oc-admin/ncicAdmin.php#plate_panel");
}

function editnameid()
{
    $uid = htmlspecialchars($_POST['editid']);
    $civ_data = new Civilian\CivilianManager();
    $result = $civ_data->editNameId($uid);

    echo json_encode($result);
}

function editplateid()
{
    
    $uid = htmlspecialchars($_POST['edit_plateid']);
    $civ_data = new Civilian\CivilianManager();
    $result = $civ_data->editplateid($uid);

    echo json_encode($result);
}
?>