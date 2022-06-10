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
include_once(__DIR__ . "/../plugins/api_auth.php");
require(__DIR__ . "../../includes/autoloader.inc.php");

/* This file handles all actions for admin.php script */

/* Handle resetData POST request */

//** Handle POST requests for Citation Type Manager **/
if (isset($_POST['getCitationTypes'])) {
    getCitationTypes();
} else if (isset($_POST['getCitationTypeDetails'])) {
    getCitationTypeDetails();
} else if (isset($_POST['editCitationType'])) {
    editCitationType();
} else if (isset($_POST['deleteCitationType'])) {
    deleteCitationType();
}

//** Handle POST requests for Departments Manager **/
else if (isset($_POST['getDepartments'])) {
    getDepartments();
} else if (isset($_POST['getDepartmentDetails'])) {
    getDepartmentDetails();
} else if (isset($_POST['editDepartment'])) {
    editDepartment();
} else if (isset($_POST['deleteDepartment'])) {
    deleteDepartment();
}

//** Handle POST requests for Incident Types **/
else if (isset($_POST['getIncidentTypes'])) {
    getIncidentTypes();
} else if (isset($_POST['getIncidentTypeDetails'])) {
    getIncidentTypeDetails();
} else if (isset($_POST['editIncidentType'])) {
    editIncidentType();
} else if (isset($_POST['deleteIncidentType'])) {
    deleteIncidentType();
}

//** Handle POST requests for Radio Code Manager **/
else if (isset($_POST['getRadioCodes'])) {
    getRadioCodes();
} else if (isset($_POST['getRadioCodeDetails'])) {
    getRadioCodeDetails();
} else if (isset($_POST['editRadioCode'])) {
    editRadioCode();
} else if (isset($_POST['deleteRadioCode'])) {
    deleteRadioCode();
}

// Handle POST requests for Streets **//
else if (isset($_POST['getStreets'])) {
    getStreets();
} else if (isset($_POST['getStreetDetails'])) {
    getStreetDetails();
} else if (isset($_POST['editStreet'])) {
    editStreet();
} else if (isset($_POST['deleteStreet'])) {
    deleteStreet();
}

//** Handle POST requests for Vehicles **/
else if (isset($_POST['getVehicles'])) {
    getVehicles();
} else if (isset($_POST['getVehicleDetails'])) {
    getVehicleDetails();
} else if (isset($_POST['editVehicle'])) {
    editVehicle();
} else if (isset($_POST['deleteVehicle'])) {
    deleteVehicle();
}

//** Handle POST requests for Warning Types Manager **/
else if (isset($_POST['getWarningTypes'])) {
    getWarningTypes();
} else if (isset($_POST['getWarningTypeDetails'])) {
    getWarningTypeDetails();
} else if (isset($_POST['editWarningType'])) {
    editWarningType();
} else if (isset($_POST['deleteWarningType'])) {
    deleteWarningType();
}

//** Handle POST requests for Warrant Types Manager **/
else if (isset($_POST['getWarrantTypes'])) {
    getWarrantTypes();
} else if (isset($_POST['getWarrantTypeDetails'])) {
    getWarrantTypeDetails();
} else if (isset($_POST['editWarrantType'])) {
    editWarrantType();
} else if (isset($_POST['deleteWarrantType'])) {
    deleteWarrantType();
}

//** Handle POST requests for Weapons **/
else if (isset($_POST['getWeapons'])) {
    getWeapons();
} else if (isset($_POST['getWeaponDetails'])) {
    getWeaponDetails();
} else if (isset($_POST['editWeapon'])) {
    editWeapon();
} else if (isset($_POST['deleteWeapon'])) {
    deleteWeapon();
}

/* Handle POST requests for Import/Export/Reset */ else if (isset($_POST['resetData'])) {
    resetData();
}

//** BEGIN Citation Types Manager FUNCTIONS **/

/**#@+
 * function getCitationTypes()
 * Fetches all Warrant Types from the warrant_types table with their resepective IDs and
 * types. It then builds the table and includes functions such as Edit and Delete
 * These functions are handled by editCitationTypes(); and deleteCitationTypes(); 
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getCitationTypes()
{

    $citation_data = new Citations\CitationManager();

    $result = $citation_data->getCitationTypes();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are no citation types in the database.</span></div>";
    } else {
        echo '
            <table id="allCitationTypes" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Citation Description</th>
                <th>Citation Fine (Recommended)</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[1] . '</td>
                <td>' . $row[2] . '</td>
                <td>';
            if (DEMO_MODE == false) {
                echo '<form action="' . BASE_URL . '/actions/dataActions.php" method="post">';
                if ((MODERATOR_EDIT_WARNINGTYPE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<button name="editCitationType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editCitationTypeModal" class="btn btn-xs btn-link" >Edit</button>';
                } else {
                    echo '<button name="editCitationType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editCitationTypeModal" class="btn btn-xs btn-link" disabled >Edit</button>';
                }

                if ((MODERATOR_DELETE_WARNINGTYPE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<input name="deleteCitationType" type="submit" class="btn btn-xs btn-link" onclick="deleteCitationType(' . $row[0] . ')" value="Delete" />';
                } else {
                    echo '<input name="deleteCitationType" type="submit" class="btn btn-xs btn-link" onclick="deleteCitationType(' . $row[0] . ')" value="Delete" disabled />';
                }
            } else {
                echo ' </td>
                <td>
                <form action="' . BASE_URL . '/actions/dataActions.php" method="post">
                <button name="editCitationType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editCitationTypeModal" class="btn btn-xs btn-link" disabled >Edit</button>
                <input name="deleteCitationType" type="submit" class="btn btn-xs btn-link" onclick="deleteCitationTypeCode(' . $row[0] . ')" value="Delete" disabled />
                ';
            }

            echo '<input name="citationTypeID" type="hidden" value=' . $row[0] . ' />
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

/**#@+
 * function getCitationTypeDetails();
 * Fetches details for a given edit modal in Warrant Types Manager.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getCitationTypeDetails()
{
    $id = htmlspecialchars($_POST['id']);

    $citation_data = new Citations\CitationManager();

    $result = $citation_data->getCitationTypeDetails($id);

    $encode = array();
    foreach ($result as $row) {
        $encode["citation_id"] = $row[0];
        $encode["citation_description"] = $row[1];
        $encode["citation_fine"] = $row[2];
    }

    echo json_encode($encode);
}

function editCitationType()
{
    $id                              = !empty($_POST['citation_id']) ? htmlspecialchars($_POST['citation_id']) : '';
    $citation_description           = !empty($_POST['citation_description']) ? htmlspecialchars($_POST['citation_description']) : '';
    $citation_fine                  = !empty($_POST['citation_fine']) ? htmlspecialchars($_POST['citation_fine']) : '';

    $citation_data = new Citations\CitationManager();

    $result = $citation_data->editCitationType($citation_description, $citation_fine, $id);

    if (!$result) {
        echo "Error updating record";
    }

    //Let the user know their information was updated
    $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Citation ' . $citation_description . ' with a recommended fine of ' . $citation_fine . '  edited successfully.</span></div>';
    header("Location: " . BASE_URL . "/oc-admin/dataManagement/citationTypeManager.php");
}

/**#@+
 * function deleteCitationType()
 * Delete a given Warrant Type from the database.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function deleteCitationType()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $id = htmlspecialchars($_POST['citationTypeID']);

    $citation_data = new Citations\CitationManager();

    $citation_data->deleteCitationType($id);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Successfully removed incident type from database</span></div>';
    header("Location: " . BASE_URL . "/oc-admin/dataManagement/citationTypeManager.php");
}

//** END Citation Types Manager FUNCTIONS **//

//** BEGIN Departments Manager FUNCTIONS **/

/**#@+
 * function getDepartments()
 * Fetches all Warrant s from the warrant_s table with their resepective IDs and
 * s. It then builds the table and includes functions such as Edit and Delete
 * These functions are handled by editDepartments(); and deleteDepartments(); 
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getDepartments()
{
    $department_data = new Departments\DepartmentManager();

    $result = $department_data->getDepartments();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are no Departments in the database.</span></div>";
    } else {
        echo '
            <table id="allDepartments" class="table table-striped table-bordered">
            <thead>
                <tr>                
                    <th>Department</th>
                    <th>Department Short Name</th>
                    <th>Department Long Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {

            if ($row[4] = 1) {
                $deptStatus = "<span style=\"color:red;\">Disabled (1)</span>";
            } else {
                $deptStatus = "<span style=\"color:green; font-weight:bold;\">Enabled (2)</span>";
            }

            echo '
            <tr>
                <td>' . $row[1] . '</td>
                <td>' . $row[2] . '</td>
                <td>' . $row[3] . '</td>

                <td>';
            if (DEMO_MODE == false) {
                echo '<form action="' . BASE_URL . '/actions/dataActions.php" method="post">';
                if ((MODERATOR_DATAMAN_DEPARTMENTS == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<button name="editDepartment" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editDepartmentModal" class="btn btn-xs btn-link">Edit</button>';
                    echo '<input name="deleteDepartment" type="submit" class="btn btn-xs btn-link" onclick="deleteDepartment(' . $row[0] . ')" value="Delete" />';
                } else {
                    echo '<button name="editDepartment" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editDepartmentModal" class="btn btn-xs btn-link" disabled >Edit</button>';
                    echo '<input name="deleteDepartment" type"submit" class="btn btn-xs btn-link" onclick="deleteDepartment(' . $row[0] . ')" value="Delete" />';
                }
            } else {
                echo ' </td>
                <td>
                <form action="' . BASE_URL . '/actions/dataActions.php" method="post">
                <button name="editDepartment" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editDepartmentModal" class="btn btn-xs btn-link" disabled >Edit</button>
                <input name="deleteDepartment" type="submit" class="btn btn-xs btn-link" onclick="deleteDepartment(' . $row[0] . ')" value="Delete" disabled />
                ';
            }

            echo '<input name="departmentID" type="hidden" value=' . $row[0] . ' aria-hidden="true" />
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

/**#@+
 * function getDepartmentDetails();
 * Fetches details for a given edit modal in Warrant s Manager.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getDepartmentDetails()
{
    $departmentID = htmlspecialchars($_POST['departmentID']);

    $department_data = new Departments\DepartmentManager();

    $result = $department_data->getDepartmentDetails($departmentID);

    $encode = array();
    foreach ($result as $row) {
        $encode["departmentID"] = $row[0];
        $encode["department_name"] = $row[1];
        $encode["department_short_name"] = $row[2];
        $encode["department_long_name"] = $row[3];
        $encode["allow_department"] = $row[4];
    }

    echo json_encode($encode);
}

function editDepartment()
{
    $departmentID                    = !empty($_POST['departmentID']) ? htmlspecialchars($_POST['departmentID']) : '';
    $department_name                = !empty($_POST['department_name']) ? htmlspecialchars($_POST['department_name']) : '';
    $department_short_name            = !empty($_POST['department_short_name']) ? htmlspecialchars($_POST['department_short_name']) : '';
    $department_long_name            = !empty($_POST['department_long_name']) ? htmlspecialchars($_POST['department_long_name']) : '';
    $allow_department                = !empty($_POST['allow_department']) ? htmlspecialchars($_POST['allow_department']) : '';


    $departmentID = htmlspecialchars($_POST['departmentID']);

    $department_data = new Departments\DepartmentManager();

    $result = $department_data->editDepartment($department_name, $department_short_name, $department_long_name, $departmentID);

    if (!$result) {
        //Let the user know their information was updated
        $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Department ' . $department_long_name . ' (' . $department_short_name . ')  was  edited successfully.</span></div>';
        header("Location: " . BASE_URL . "/oc-admin/dataManagement/departmentsManager.php");
    } else {
        echo "Error updating record";
    }
}

/**#@+
 * function deleteDepartment()
 * Delete a given Warrant  from the database.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function deleteDepartment()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $departmentID = htmlspecialchars($_POST['departmentID']);

    $department_data = new Departments\DepartmentManager();

    $department_data->deleteDepartment($departmentID);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Successfully removed incident  from database</span></div>';
    header("Location: " . BASE_URL . "/oc-admin/dataManagement/departmentsManager.php");
}

//** END Departments Manager FUNCTIONS **//

//** BEGIN Incident Type Manager FUNCTIONS **/

/**#@+
 * function getIncidentTypes()
 * Fetches all Incident Types from the incident_types table with their resepective IDs and
 * types. It then builds the table and includes functions such as Edit and Delete
 * These functions are handled by editIncidentType(); and deleteIncidnetTypes(); 
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getIncidentTypes()
{

    $incident_data = new Incidents\IncidentManager();

    $result = $incident_data->getIncidentTypes();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are no incident types in the database.</span></div>";
    } else {
        echo '
            <table id="allIncidentTypes" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Incident ID</th>
                <th>Incident Name</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[1] . '</td>
                <td>' . $row[2] . '</td>
                <td>';
            if (DEMO_MODE == false) {
                echo '<form action="' . BASE_URL . '/actions/dataActions.php" method="post">';
                if ((MODERATOR_EDIT_INCIDENTTYPE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<button name="editIncidentType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editIncidentTypeModal" class="btn btn-xs btn-link" >Edit</button>';
                } else {
                    echo '<button name="editIncidentType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editIncidentTypeModal" class="btn btn-xs btn-link" disabled >Edit</button>';
                }

                if ((MODERATOR_DELETE_INCIDENTTPYE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<input name="deleteIncidentType" type="submit" class="btn btn-xs btn-link" onclick="deleteIncidentType(' . $row[0] . ')" value="Delete" />';
                } else {
                    echo '<input name="deleteIncidentType" type="submit" class="btn btn-xs btn-link" onclick="deleteIncidentType(' . $row[0] . ')" value="Delete" disabled />';
                }
            } else {
                echo ' </td>
                <td>
                <form action="' . BASE_URL . '/actions/dataActions.php" method="post">
                <button name="editIncidentType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editIncidentTypeModal" class="btn btn-xs btn-link" disabled >Edit</button>
                <input name="deleteIncidentType" type="submit" class="btn btn-xs btn-link" onclick="deleteIncidentType(' . $row[0] . ')" value="Delete" disabled />
                ';
            }

            echo '<input name="incidentTypeID" type="hidden" value=' . $row[0] . ' />
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

/**#@+
 * function getIncidentTypeDetails();
 * Fetches details for a given edit modal in Weapon Manager.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getIncidentTypeDetails()
{
    $incidentTypeID = htmlspecialchars($_POST['incidentTypeID']);

    $incident_data = new Incidents\IncidentManager();

    $result = $incident_data->getIncidentTypeDetails($incidentTypeID);

    $encode = array();
    foreach ($result as $row) {
        $encode["incidentTypeID"] = $row[0];
        $encode["incident_code"] = $row[1];
        $encode["incident_name"] = $row[2];
    }

    echo json_encode($encode);
}

function editIncidentType()
{
    $id                    = !empty($_POST['incidentTypeID']) ? htmlspecialchars($_POST['incidentTypeID']) : '';
    $incident_code        = !empty($_POST['incident_code']) ? htmlspecialchars($_POST['incident_code']) : '';
    $incident_name        = !empty($_POST['incident_name']) ? htmlspecialchars($_POST['incident_name']) : '';

    $incident_data = new Incidents\IncidentManager();

    $result = $incident_data->editIncidentType($incident_code, $incident_name, $id);

    if (!$result) {
        //Let the user know their information was updated
        $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Incident ' . $incident_code . ' – ' . $incident_name . ' edited successfully.</span></div>';
        header("Location: " . BASE_URL . "/oc-admin/dataManagement/incidentTypeManager.php");
    } else {
        echo "Error updating record";
    }
}

/**#@+
 * function deleteIncidentType()
 * Delete a given Weapon from the database.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function deleteIncidentType()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $id = !empty($_POST['incidentTypeID']) ? htmlspecialchars($_POST['incidentTypeID']) : '';

    $incident_data = new Incidents\IncidentManager();

    $incident_data->deleteIncidentType($id);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Successfully removed incident type from database</span></div>';
    header("Location: " . BASE_URL . "/oc-admin/dataManagement/incidentTypeManager.php");
}

//** END Incident Types Manager FUNCTIONS **//

//** BEGIN Radio Codes Manager FUNCTIONS **/

/**#@+
 * function getRadioCodes()
 * Fetches all Warrant Types from the warrant_types table with their resepective IDs and
 * types. It then builds the table and includes functions such as Edit and Delete
 * These functions are handled by editRadioCode(); and deleteRadioCode(); 
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getRadioCodes()
{
    $radio_data = new \Radio\radioCodesManager();

    $result = $radio_data->getRadioCodes();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are no radio codes in the database.</span></div>";
    } else {
        echo '
            <table id="allRadioCodes" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Code</th>
                <th>Code Description</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[1] . '</td>
                <td>' . $row[2] . '</td>
                <td>';
            if (DEMO_MODE == false) {
                echo '<form action="' . BASE_URL . '/actions/dataActions.php" method="post">';
                if ((MODERATOR_EDIT_WARNINGTYPE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<button name="editRadioCode" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editRadioCodeModal" class="btn btn-xs btn-link" >Edit</button>';
                } else {
                    echo '<button name="editRadioCode" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editRadioCodeModal" class="btn btn-xs btn-link" disabled >Edit</button>';
                }

                if ((MODERATOR_DELETE_WARNINGTYPE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<input name="deleteRadioCode" type="submit" class="btn btn-xs btn-link" onclick="deleteRadioCode(' . $row[0] . ')" value="Delete" />';
                } else {
                    echo '<input name="deleteRadioCode" type="submit" class="btn btn-xs btn-link" onclick="deleteRadioCode(' . $row[0] . ')" value="Delete" disabled />';
                }
            } else {
                echo ' </td>
                <td>
                <form action="' . BASE_URL . '/actions/dataActions.php" method="post">
                <button name="editRadioCode" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editRadioCodeModal" class="btn btn-xs btn-link" disabled >Edit</button>
                <input name="deleteRadioCode" type="submit" class="btn btn-xs btn-link" onclick="deleteRadioCode(' . $row[0] . ')" value="Delete" disabled />
                ';
            }

            echo '<input name="deleteRadioCodeId" type="hidden" value=' . $row[0] . ' />
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

/**#@+
 * function getRadioCodeDetails();
 * Fetches details for a given edit modal in Warrant Types Manager.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getRadioCodeDetails()
{
    $id = htmlspecialchars($_POST['id']);

    $radio_data = new \Radio\radioCodesManager();

    $result = $radio_data->getRadioCodeDetails($id);

    $encode = array();
    foreach ($result as $row) {
        $encode["id"] = $row[0];
        $encode["code"] = $row[1];
        $encode["code_description"] = $row[2];
    }

    echo json_encode($encode);
}

function editRadioCode()
{
    $id                        = !empty($_POST['id']) ? htmlspecialchars($_POST['id']) : '';
    $code                    = !empty($_POST['code']) ? htmlspecialchars($_POST['code']) : '';
    $code_description        = !empty($_POST['code_description']) ? htmlspecialchars($_POST['code_description']) : '';
    $OnCall                    = !empty($_POST['OnCall']) ? htmlspecialchars($_POST['OnCall']) : '';

    $radio_data = new \Radio\radioCodesManager();

    $result = $radio_data->editRadioCode($code_description, $code, $id);

    if (!$result) {
        //Let the user know their information was updated
        $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Code ' . $code . ' – ' . $code_description . '  edited successfully.</span></div>';
        header("Location: " . BASE_URL . "/oc-admin/dataManagement/radioCodesManager.php");
    } else {
        echo "Error updating record";
    }
}

/**#@+
 * function deleteRadioCode()
 * Delete a given Warrant Type from the database.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function deleteRadioCode()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $id = !empty($_POST['deleteRadioCodeId']) ? htmlspecialchars($_POST['deleteRadioCodeId']) : '';

    $radio_data = new \Radio\radioCodesManager();

    $radio_data->deleteRadioCode($id);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Successfully removed incident type from database</span></div>';
    header("Location: " . BASE_URL . "/oc-admin/dataManagement/radioCodesManager.php");
}

//** END Radio Codes Manager FUNCTIONS **//

//** BEGIN Streets Manager FUNCTIONS **//
/**#@+
 * function getStreets()
 * Fetches all streets from the streets table with their resepective IDs and
 * counties. It then build the table and includes functions such as Edit and Delete
 * These functions are handled by editStreet(); and deleteStreet(); 
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getStreets()
{

    $street_data = new \Street\StreetManager();

    $result = $street_data->getStreets();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are no streets in the database.</span></div>";
    } else {
        echo '
            <table id="allStreets" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Street</th>
                <th>County</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[1] . '</td>
                <td>' . $row[2] . '</td>
                <td>';
            if (DEMO_MODE == false) {
                echo '<form action="' . BASE_URL . '/actions/dataActions.php" method="post">';
                if ((MODERATOR_EDIT_STREET == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<button name="editStreet" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editStreetModal" class="btn btn-xs btn-link" >Edit</button>';
                } else {
                    echo '<button name="editStreet" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editStreetModal" class="btn btn-xs btn-link" disabled >Edit</button>';
                }

                if ((MODERATOR_DELETE_STREET == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<input name="deleteStreet" type="submit" class="btn btn-xs btn-link" onclick="deleteStreet(' . $row[0] . ')" value="Delete" />';
                } else {
                    echo '<input name="deleteStreet" type="submit" class="btn btn-xs btn-link" onclick="deleteStreet(' . $row[0] . ')" value="Delete" disabled />';
                }
            } else {
                echo ' </td>
                <td>
                <form action="' . BASE_URL . '/actions/dataActions.php" method="post">
                <button name="editStreet" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editStreetModal" class="btn btn-xs btn-link" disabled >Edit</button>
                <input name="deleteStreet" type="submit" class="btn btn-xs btn-link" onclick="deleteStreet(' . $row[0] . ')" value="Delete" disabled />
                ';
            }

            echo '<input name="streetID" type="hidden" value=' . $row[0] . ' />
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

/**#@+
 * function getStreetDetails();
 * Fetches details for a given edit modal in Street Manager.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getStreetDetails()
{
    $streetID = htmlspecialchars($_POST['streetID']);

    $street_data = new \Street\StreetManager();

    $result = $street_data->getStreetDetails($streetID);

    $encode = array();
    foreach ($result as $row) {
        $encode["streetID"] = $row[0];
        $encode["name"] = $row[1];
        $encode["county"] = $row[2];
    }

    echo json_encode($encode);
}

function editStreet()
{
    $id            = !empty($_POST['streetID']) ? htmlspecialchars($_POST['streetID']) : '';
    $name         = !empty($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $county     = !empty($_POST['county']) ? htmlspecialchars($_POST['county']) : '';

    $street_data = new \Street\StreetManager();

    $result = $street_data->editStreet($name, $county, $id);

    if (!$result) {

        //Let the user know their information was updated
        $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Street ' . $name . ' in ' . $county . ' edited successfully.</span></div>';
        header("Location: " . BASE_URL . "/oc-admin/dataManagement/streetManager.php");
    } else {
        echo "Error updating record";
    }
}

/**#@+
 * function deleteStreet()
 * Delete a given street from the database.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function deleteStreet()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $id = htmlspecialchars($_POST['streetID']);

    $street_data = new \Street\StreetManager();

    $street_data->deleteStreet($id);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Successfully removed street from database</span></div>';
    header("Location: " . BASE_URL . "/oc-admin/dataManagement/streetManager.php");
}

//** END Streets Manager FUNCTIONS **//

//** BEGIN Vehicle Manager FUNCTIONS **/
/**#@+
 * function getVehicles()
 * Fetches all vehicles from the streets table with their resepective IDs and
 * counties. It then build the table and includes functions such as Edit and Delete
 * These functions are handled by editVehcile(); and deleteVehicle(); 
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getVehicles()
{
    $veh_data = new Vehicles\vehicleManager();

    $vehicles = $veh_data->getVehicles();

    if (!$vehicles) {
        echo "<div class=\"alert alert-info\"><span>There are no vehicles in the database.</span></div>";
    } else {
        echo '
                <table id="allVehicles" class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
            ';

        foreach ($vehicles as $row) {
            echo '
                <tr>
                    <td>' . $row[1] . '</td>
                    <td>' . $row[2] . '</td>
                    <td>';
            if (DEMO_MODE == false) {
                echo '<form action="' . BASE_URL . '/actions/dataActions.php" method="post">';
                if ((MODERATOR_EDIT_VEHICLE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<button name="editVehicle" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editVehicleModal" class="btn btn-xs btn-link" >Edit</button>';
                } else {
                    echo '<button name="editVehicle" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editVehicleModal" class="btn btn-xs btn-link" disabled >Edit</button>';
                }

                if ((MODERATOR_DELETE_VEHICLE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<input name="deleteVehicle" type="submit" class="btn btn-xs btn-link" onclick="deleteVehicle(' . $row[0] . ')" value="Delete" />';
                } else {
                    echo '<input name="deleteVehicle" type="submit" class="btn btn-xs btn-link" onclick="deleteVehicle(' . $row[0] . ')" value="Delete" disabled />';
                }
            } else {
                echo ' </td>
                    <td>
                    <form action="' . BASE_URL . '/actions/dataActions.php" method="post">
                    <button name="editVehicle" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editVehicle" class="btn btn-xs btn-link" disabled >Edit</button>
                    <input name="deleteVehcile" type="submit" class="btn btn-xs btn-link" onclick="deleteVehicle(' . $row[0] . ')" value="Delete" disabled />
                    ';
            }

            echo '<input name="vehicleID" type="hidden" value=' . $row[0] . ' />
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

/**#@+
 * function getVehicleDetails();
 * Fetches details for a given edit modal in Vehicle Manager.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getVehicleDetails()
{
    $vehicleID = htmlspecialchars($_POST['vehicleID']);

    $veh_data = new Vehicles\vehicleManager();

    $result = $veh_data->getVehicleDetails($vehicleID);

    $encode = array();
    foreach ($result as $row) {
        $encode["vehicleID"] = $row[0];
        $encode["make"] = $row[1];
        $encode["model"] = $row[2];
    }

    echo json_encode($encode);
}

/**#@+
 * function editVehicle()
 * Updates the corresponding record for the given vehicle in the database.
 *
 * @since 0.2.6
 *
 **/
function editVehicle()
{
    $id            = !empty($_POST['vehicleID']) ? htmlspecialchars($_POST['vehicleID']) : '';
    $make         = !empty($_POST['make']) ? htmlspecialchars($_POST['make']) : '';
    $model       = !empty($_POST['model']) ? htmlspecialchars($_POST['model']) : '';

    $veh_data = new Vehicles\vehicleManager();

    $result = $veh_data->editVehicle($make, $model, $id);

    if (!$result) {
        /** Indicate that the vehicle record was updated successfully **/
        $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Vehicle ' . $make . ' ' . $model . ' edited successfully.</span></div>';
        header("Location: " . BASE_URL . "/oc-admin/dataManagement/vehicleManager.php");
    } else {
        echo "Error updating record";
    }
}

/**#@+
 * function deleteVehicle()
 * Delete a given vehicle from the database.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function deleteVehicle()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $id         = !empty($_POST['vehicleID']) ? htmlspecialchars($_POST['vehicleID']) : '';

    $veh_data = new Vehicles\vehicleManager();

    $veh_data->deleteVehicle($id);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['successMessage'] = "<div class=\"alert alert-success\"><span>Vehicle " . $_POST['make'] . " " . $_POST['model'] . " removed successfully.</div>";
    header("Location: " . BASE_URL . "/oc-admin/dataManagement/vehicleManager.php");
}

//** END Vehicle Manager FUNCTIONS **//

//** BEGIN Warning Type Manager FUNCTIONS **/

/**#@+
 * function getWarningTypes()
 * Fetches all Warning Types from the warning_types table with their resepective IDs and
 * types. It then builds the table and includes functions such as Edit and Delete
 * These functions are handled by editWarningType(); and deleteWarningTypes(); 
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getWarningTypes()
{
    $warning_data = new Warnings\WarningManager();

    $result = $warning_data->getWarningTypes();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are no warning types in the database.</span></div>";
    } else {
        echo '
            <table id="allWarningTypes" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Warning Description</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[1] . '</td>
                <td>';
            if (DEMO_MODE == false) {
                echo '<form action="' . BASE_URL . '/actions/dataActions.php" method="post">';
                if ((MODERATOR_EDIT_WARNINGTYPE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<button name="editWarningType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editWarningTypeModal" class="btn btn-xs btn-link" >Edit</button>';
                } else {
                    echo '<button name="editWarningType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editWarningypeModal" class="btn btn-xs btn-link" disabled >Edit</button>';
                }

                if ((MODERATOR_DELETE_WARNINGTYPE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<input name="deleteWarningType" type="submit" class="btn btn-xs btn-link" onclick="deleteWarningType(' . $row[0] . ')" value="Delete" />';
                } else {
                    echo '<input name="deleteWarningType" type="submit" class="btn btn-xs btn-link" onclick="deleteWarningType(' . $row[0] . ')" value="Delete" disabled />';
                }
            } else {
                echo ' </td>
                <td>
                <form action="' . BASE_URL . '/actions/dataActions.php" method="post">
                <button name="editWarningType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editWarningTypeModal" class="btn btn-xs btn-link" disabled >Edit</button>
                <input name="deleteWarningType" type="submit" class="btn btn-xs btn-link" onclick="deleteWarningType(' . $row[0] . ')" value="Delete" disabled />
                ';
            }

            echo '<input name="warningTypeID" type="hidden" value=' . $row[0] . ' />
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

/**#@+
 * function getWarningDetails();
 * Fetches details for a given edit modal in Warning Types Manager.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getWarningTypeDetails()
{
    $warningTypeID = htmlspecialchars($_POST['warningTypeID']);

    $warning_data = new Warnings\WarningManager();

    $result = $warning_data->getWarningTypeDetails($warningTypeID);

    $encode = array();
    foreach ($result as $row) {
        $encode["warningTypeID"] = $row[0];
        $encode["warning_description"] = $row[1];
    }

    echo json_encode($encode);
}

function editWarningType()
{
    $id                         = !empty($_POST['warningTypeID']) ? htmlspecialchars($_POST['warningTypeID']) : '';
    $warning_description        = !empty($_POST['warning_description']) ? htmlspecialchars($_POST['warning_description']) : '';

    $warning_data = new Warnings\WarningManager();

    $result = $warning_data->editWarningType($warning_description, $id);

    if (!$result) {
        //Let the user know their information was updated
        $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Incident edited successfully.</span></div>';
        header("Location: " . BASE_URL . "/oc-admin/dataManagement/warningTypeManager.php");
    } else {
        echo "Error updating record";
    }
}

/**#@+
 * function deleteWarningType()
 * Delete a given Warning Type from the database.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function deleteWarningType()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $id = htmlspecialchars($_POST['warningTypeID']);

    $warning_data = new Warnings\WarningManager();

    $warning_data->deleteWarningType($id);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Successfully removed incident type from database</span></div>';
    header("Location: " . BASE_URL . "/oc-admin/dataManagement/warningTypeManager.php");
}

//** END Warning Types Manager FUNCTIONS **//

//** BEGIN Warrant Type Manager FUNCTIONS **/

/**#@+
 * function getWarrantTypes()
 * Fetches all Warrant Types from the warrant_types table with their resepective IDs and
 * types. It then builds the table and includes functions such as Edit and Delete
 * These functions are handled by editWarrantType(); and deleteWarrantType(); 
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getWarrantTypes()
{
    $warrant_data = new Warrants\WarrantManager();

    $result = $warrant_data->getWarrantTypes();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are no warrant types in the database.</span></div>";
    } else {
        echo '
            <table id="allWarrantTypes" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Warrant violent</th>
                <th>Warrant Description</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[1] . '</td>
                <td>' . $row[2] . '</td>
                <td>';
            if (DEMO_MODE == false) {
                echo '<form action="' . BASE_URL . '/actions/dataActions.php" method="post">';
                if ((MODERATOR_EDIT_WARRANTTYPE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<button name="editWarrantType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editWarrantTypeModal" class="btn btn-xs btn-link" >Edit</button>';
                } else {
                    echo '<button name="editWarrantType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editWarrantypeModal" class="btn btn-xs btn-link" disabled >Edit</button>';
                }

                if ((MODERATOR_DELETE_WARRANTTYPE == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<input name="deleteWarrantType" type="submit" class="btn btn-xs btn-link" onclick="deleteWarrantType(' . $row[0] . ')" value="Delete" />';
                } else {
                    echo '<input name="deleteWarrantType" type="submit" class="btn btn-xs btn-link" onclick="deleteWarrantType(' . $row[0] . ')" value="Delete" disabled />';
                }
            } else {
                echo ' </td>
                <td>
                <form action="' . BASE_URL . '/actions/dataActions.php" method="post">
                <button name="editWarrantType" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editWarrantTypeModal" class="btn btn-xs btn-link" disabled >Edit</button>
                <input name="deleteWarrantType" type="submit" class="btn btn-xs btn-link" onclick="deleteWarrantType(' . $row[0] . ')" value="Delete" disabled />
                ';
            }

            echo '<input name="warrantTypeID" type="hidden" value=' . $row[0] . ' />
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

/**#@+
 * function getWarrantTypeDetails();
 * Fetches details for a given edit modal in Warrant Types Manager.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getWarrantTypeDetails()
{
    $warrantTypeID = htmlspecialchars($_POST['warrantTypeID']);

    $warrant_data = new Warrants\WarrantManager();

    $result = $warrant_data->getWarrantTypeDetails($warrantTypeID);

    $encode = array();
    foreach ($result as $row) {
        $encode["warrantTypeID"] = $row[0];
        $encode["warrant_violent"] = $row[1];
        $encode["warrant_description"] = $row[2];
    }

    echo json_encode($encode);
}

function editWarrantType()
{
    $id                         = !empty($_POST['warrantTypeID']) ? htmlspecialchars($_POST['warrantTypeID']) : '';
    $warrant_violent            = !empty($_POST['warrant_violent']) ? htmlspecialchars($_POST['warrant_violent']) : '';
    $warrant_description        = !empty($_POST['warrant_description']) ? htmlspecialchars($_POST['warrant_description']) : '';

    $warrant_data = new Warrants\WarrantManager();

    $result = $warrant_data->editWarrantType($warrant_violent, $warrant_description, $id);

    if (!$result) {
        //Let the user know their information was updated
        $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Warrant type "' . $warrant_description . '" edited successfully.</span></div>';
        header("Location: " . BASE_URL . "/oc-admin/dataManagement/warrantTypeManager.php");
    } else {
        echo "Error updating record";
    }
}

/**#@+
 * function deleteWarrantType()
 * Delete a given Warrant Type from the database.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function deleteWarrantType()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $id = htmlspecialchars($_POST['warrantTypeID']);

    $warrant_data = new Warrants\WarrantManager();

    $warrant_data->deleteWarrantType($id);
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Successfully removed warrant type from database</span></div>';
    header("Location: " . BASE_URL . "/oc-admin/dataManagement/warrantTypeManager.php");
}

//** END Warrant Types Manager FUNCTIONS **//

//** BEGIN Weapon Manager FUNCTIONS **/

/**#@+
 * function getWeapons()
 * Fetches all Weapons from the weapons table with their resepective IDs and
 * types. It then builds the table and includes functions such as Edit and Delete
 * These functions are handled by editWeapon(); and deleteWeapon(); 
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getWeapons()
{
    $weapon_data = new Weapons\WeaponManager();

    $result = $weapon_data->getWeapons();

    if (!$result) {
        echo "<div class=\"alert alert-info\"><span>There are no weapons in the database.</span></div>";
    } else {
        echo '
            <table id="allWeapons" class="table table-striped table-bordered">
            <thead>
                <tr>
                <th>Weapon Type</th>
                <th>Weapon Name</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($result as $row) {
            echo '
            <tr>
                <td>' . $row[1] . '</td>
                <td>' . $row[2] . '</td>
                <td>';
            if (DEMO_MODE == false) {
                echo '<form action="' . BASE_URL . '/actions/dataActions.php" method="post">';
                if ((MODERATOR_EDIT_WEAPON == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<button name="editWeapon" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editWeaponModal" class="btn btn-xs btn-link" >Edit</button>';
                } else {
                    echo '<button name="editWeapon" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editWeaponModal" class="btn btn-xs btn-link" disabled >Edit</button>';
                }

                if ((MODERATOR_DELETE_WEAPON == true && $_SESSION['admin_privilege'] == 2) || ($_SESSION['admin_privilege'] == 3)) {
                    echo '<input name="deleteWeapon" type="submit" class="btn btn-xs btn-link" onclick="deleteWeapon(' . $row[0] . ')" value="Delete" />';
                } else {
                    echo '<input name="deleteWeapon" type="submit" class="btn btn-xs btn-link" onclick="deleteWeapon(' . $row[0] . ')" value="Delete" disabled />';
                }
            } else {
                echo ' </td>
                <td>
                <form action="' . BASE_URL . '/actions/dataActions.php" method="post">
                <button name="editWeapon" type="button" data-toggle="modal" id="' . $row[0] . '" data-target="#editWeaponModal" class="btn btn-xs btn-link" disabled >Edit</button>
                <input name="deleteWeapon" type="submit" class="btn btn-xs btn-link" onclick="deleteWeapon(' . $row[0] . ')" value="Delete" disabled />
                ';
            }

            echo '<input name="WeaponID" type="hidden" value=' . $row[0] . ' />
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

/**#@+
 * function getWeaponDetails();
 * Fetches details for a given edit modal in Weapon Manager.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function getWeaponDetails()
{
    $weaponID = htmlspecialchars($_POST['weaponID']);

    $weapon_data = new Weapons\WeaponManager();

    $result = $weapon_data->getWeaponDetails($weaponID);

    $encode = array();
    foreach ($result as $row) {
        $encode["weaponID"] = $row[0];
        $encode["weapon_type"] = $row[1];
        $encode["weapon_name"] = $row[2];
    }

    echo json_encode($encode);
}

function editWeapon()
{
    $id                    = !empty($_POST['weaponID']) ? htmlspecialchars($_POST['weaponID']) : '';
    $weapon_type         = !empty($_POST['weapon_type']) ? htmlspecialchars($_POST['weapon_type']) : '';
    $weapon_name         = !empty($_POST['weapon_name']) ? htmlspecialchars($_POST['weapon_name']) : '';


    $weapon_data = new Weapons\WeaponManager();

    $result = $weapon_data->editWeapon($weapon_name, $weapon_type, $id);


    if (!$result) {
        //Let the user know their information was updated
        $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Weapon ' . $weapon_name . ' ' . $weapon_type . ' edited successfully.</span></div>';
        header("Location: " . BASE_URL . "/oc-admin/dataManagement/weaponManager.php");
    } else {
        echo "Error updating record";
    }
}

/**#@+
 * function deleteWeapon()
 * Delete a given Weapon from the database.
 *
 * @since OpenCAD 0.2.6
 *
 **/
function deleteWeapon()
{
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $id = htmlspecialchars($_POST['WeaponID']);

    $weapon_data = new Weapons\WeaponManager();

    $weapon_data->deleteWeapon($id);

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Successfully removed weapon from database</span></div>';
    header("Location: " . BASE_URL . "/oc-admin/dataManagement/weaponManager.php");
}

//** END Weapon Manager FUNCTIONS **//


//** BEGIN Data Import/Export/Reset FUNCTIONS **//

/**#@+
 * function resetData();
 *
 * Accepts "dataType" from "Reset Data" and purges table based on input or  
 * if "allData" is passed then it will purge ALL user game specific data.
 *
 * This s function does not purge the users table or reset any administrative
 * permissions.
 * 
 * @since OpenCAD 0.2.6
 *
 **/
function resetData()
{
    $dataType     =   !empty($_POST['dataType']) ? $_POST['dataType'] : '';

    if ($_POST["dataType"] == "allData") {
        $tables = array(
            "user_departments",
            "user_departments_temp",
            "active_users",
            "aop",
            "bolos_persons",
            "bolos_vehicles",
            "calls",
            "calls_users",
            "call_history",
            "call_list",
            "civilian_names",
            "colors",
            "departments",
            "dispatchers",
            "incident_types",
            "ncic_arrests",
            "ncic_citations",
            "ncic_names",
            "ncic_plates",
            "ncic_warnings",
            "ncic_warrants",
            "ncic_weapons",
            "statuses",
            "streets",
            "tones",
            "vehicles",
            "weapons",
            "radio_codes",
            "warning_types",
            "warrant_types",
            "citation_types"
        );
        foreach ($tables as $value) {
            $system_data = new System\dbReset();
            $system_data->clearData($value);
        };
    } else {
        $system_data = new System\dbReset();
        $system_data->clearData($dataType);
    }

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } ;
    $_SESSION['successMessage'] = '<div class="alert alert-success"><span>Successfully reset the ' . strtoupper($dataType) . ' table.</span></div>';
    header("Location: " . BASE_URL . "/oc-admin/admin.php");
}
//** END Data Import/Export/Reset FUNCTIONS **//
