<?php

namespace Warnings;

class WarningManager extends \Dbh
{
    public function getWarningTypes(){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "warning_types");
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

    public function getWarningTypeDetails($id){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "warning_types WHERE id = ?");
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

    public function editWarningType($warning_description, $id){
        $stmt = $this->connect()->prepare("UPDATE " . DB_PREFIX . "warning_types SET warning_description = ? WHERE id = ?");
        if (!$stmt->execute(array($warning_description, $id))) {
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

    public function deleteWarningType($id){
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "warning_types WHERE id = ?");
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
