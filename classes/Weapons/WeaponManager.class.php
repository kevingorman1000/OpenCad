<?php

namespace Weapons;

class WeaponManager extends \Dbh
{
    public function getWeapons(){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "weapons");
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

    public function getWeaponDetails($id){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "weapons WHERE id = ?");
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

    public function editWeapon($weapon_name, $weapon_type, $id){
        $stmt = $this->connect()->prepare("UPDATE " . DB_PREFIX . "weapons SET weapon_name = ?, weapon_type = ? WHERE id = ?");
        if (!$stmt->execute(array($weapon_name, $weapon_type, $id))) {
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

    public function deleteWeapon($id){
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "weapons WHERE id = ?");
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
}
