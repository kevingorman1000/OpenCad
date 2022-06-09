<?php

namespace Radio;

class radioCodesManager extends \Dbh
{
    public function getRadioCodes(){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "radio_codes");
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

    public function getRadioCodeDetails($id){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "radio_codes WHERE id = ?");
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

    public function editRadioCode($code_description, $code, $id){
        $stmt = $this->connect()->prepare("UPDATE " . DB_PREFIX . "radio_codes SET code_description = ?, code = ? WHERE id = ?");
        if (!$stmt->execute(array($code_description, $code, $id))) {
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

    public function deleteRadioCode($id){
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "radio_codes WHERE id = ?");
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
