<?php

namespace Warrants;

class WarrantManager extends \Dbh
{
    public function getWarrantTypes(){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "warrant_types");
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

    public function getWarrantTypeDetails($id){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "warrant_types WHERE id = ?");
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

    public function editWarrantType($warrant_violent, $warrant_description, $id){
        $stmt = $this->connect()->prepare("UPDATE " . DB_PREFIX . "warrant_types SET warrant_violent = ?, warrant_description = ? WHERE id = ?");
        if (!$stmt->execute(array($warrant_violent, $warrant_description, $id))) {
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

    public function deleteWarrantType($id){
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "warrant_types WHERE id = ?");
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
