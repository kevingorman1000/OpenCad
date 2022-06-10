<?php

namespace Civilian;

class CivilianManager extends \Dbh
{

    public function ncicGetNamesAdmin()
    {
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "ncic_names");
        if (!$stmt->execute()) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function ncicGetPlatesAdmin()
    {
        $stmt = $this->connect()->prepare("SELECT " . DB_PREFIX . "ncic_plates.*, " . DB_PREFIX . "ncic_names.name FROM " . DB_PREFIX . "ncic_plates INNER JOIN " . DB_PREFIX . "ncic_names ON " . DB_PREFIX . "ncic_names.id=" . DB_PREFIX . "ncic_plates.name_id");
        if (!$stmt->execute()) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function ncicGetWeaponsAdmin()
    {
        $stmt = $this->connect()->prepare("SELECT " . DB_PREFIX . "ncic_weapons.*, " . DB_PREFIX . "ncic_names.name FROM " . DB_PREFIX . "ncic_weapons INNER JOIN " . DB_PREFIX . "ncic_names ON " . DB_PREFIX . "ncic_names.id=" . DB_PREFIX . "ncic_weapons.name_id");
        if (!$stmt->execute()) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function delete_weaponAdmin($id)
    {
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "ncic_weapons WHERE id = ?");
        if (!$stmt->execute(array($id))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function delete_citationAdmin($id)
    {
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "ncic_citations WHERE id = ?");
        if (!$stmt->execute(array($id))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function delete_arrestAdmin($id)
    {
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "ncic_arrests WHERE id = ?");
        if (!$stmt->execute(array($id))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function delete_warningAdmin($id)
    {
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "ncic_warnings WHERE id = ?");
        if (!$stmt->execute(array($id))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function delete_warrantAdmin($id)
    {
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "ncic_warrants WHERE id = ?");
        if (!$stmt->execute(array($id))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function ncic_arrestsAdmins()
    {
        $stmt = $this->connect()->prepare("SELECT " . DB_PREFIX . "ncic_arrests.*, " . DB_PREFIX . "ncic_names.name FROM " . DB_PREFIX . "ncic_arrests INNER JOIN " . DB_PREFIX . "ncic_names ON " . DB_PREFIX . "ncic_names.id=" . DB_PREFIX . "ncic_arrests.name_id");
        if (!$stmt->execute()) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function ncic_warrantsAdmin()
    {
        $stmt = $this->connect()->prepare("SELECT " . DB_PREFIX . "ncic_warrants.*, " . DB_PREFIX . "ncic_names.name FROM " . DB_PREFIX . "ncic_warrants INNER JOIN " . DB_PREFIX . "ncic_names ON " . DB_PREFIX . "ncic_names.id=" . DB_PREFIX . "ncic_warrants.name_id");
        if (!$stmt->execute()) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function ncic_citationsAdmin()
    {
        $stmt = $this->connect()->prepare("SELECT " . DB_PREFIX . "ncic_citations.*, " . DB_PREFIX . "ncic_names.name FROM " . DB_PREFIX . "ncic_citations INNER JOIN " . DB_PREFIX . "ncic_names ON " . DB_PREFIX . "ncic_names.id=" . DB_PREFIX . "ncic_citations.name_id");
        if (!$stmt->execute()) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function ncic_warningsAdmin()
    {
        $stmt = $this->connect()->prepare("SELECT " . DB_PREFIX . "ncic_warnings.*, " . DB_PREFIX . "ncic_names.name FROM " . DB_PREFIX . "ncic_warnings INNER JOIN " . DB_PREFIX . "ncic_names ON " . DB_PREFIX . "ncic_names.id=" . DB_PREFIX . "ncic_warnings.name_id");
        if (!$stmt->execute()) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function getUserList()
    {
        $stmt = $this->connect()->prepare("SELECT users.id, users.name FROM ".DB_PREFIX."users");
        if (!$stmt->execute()) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function delete_name($uid)
    {
        $stmt = $this->connect()->prepare("DELETE FROM ".DB_PREFIX."ncic_names WHERE id = ?");
        if (!$stmt->execute(array($uid))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function delete_plate($uid)
    {
        $stmt = $this->connect()->prepare("DELETE FROM ".DB_PREFIX."ncic_plates WHERE id = ?");
        if (!$stmt->execute(array($uid))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function edit_name($uid)
    {
        $stmt = $this->connect()->prepare("SELECT first_name FROM ".DB_PREFIX."ncic_names WHERE first_name = ?");
        if (!$stmt->execute(array($uid))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function edit_name_update($name, $dob, $address, $sex, $race, $dlstatus, $hair, $build, $weapon, $deceased, $editid)
    {
        $stmt = $this->connect()->prepare("UPDATE ".DB_PREFIX."ncic_names SET name = ?, dob = ?, address = ?, gender = ?, race = ?, dl_status = ?, hair_color = ?, build = ?, weapon_permit = ?, deceased = ? WHERE id = ?");
        if (!$stmt->execute(array($name, $dob, $address, $sex, $race, $dlstatus, $hair, $build, $weapon, $deceased, $editid))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function edit_plate($userId, $veh_plate, $veh_make, $veh_model, $veh_pcolor, $veh_scolor, $veh_insurance, $flags, $veh_reg_state, $notes, $plate_id)
    {
        $stmt = $this->connect()->prepare("UPDATE ".DB_PREFIX."ncic_plates SET name_id = ?, veh_plate = ?, veh_make = ?, veh_model = ?, veh_pcolor = ?, veh_scolor = ?, veh_insurance = ?, flags = ?, veh_reg_state = ?, notes = ? WHERE id = ?");
        if (!$stmt->execute(array($userId, $veh_plate, $veh_make, $veh_model, $veh_pcolor, $veh_scolor, $veh_insurance, $flags, $veh_reg_state, $notes, $plate_id))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function editNameId($uid)
    {
        $stmt = $this->connect()->prepare("SELECT ".DB_PREFIX."ncic_names.* FROM ".DB_PREFIX."ncic_names WHERE id = ?");
        if (!$stmt->execute(array($uid))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }

    public function editplateid($uid)
    {
        $stmt = $this->connect()->prepare("SELECT ".DB_PREFIX."ncic_plates.* FROM ".DB_PREFIX."ncic_plates WHERE id = ?");
        if (!$stmt->execute(array($uid))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            return $results;
        }
    }
}
