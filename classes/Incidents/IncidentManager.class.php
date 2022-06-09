<?php

namespace Incidents;

class IncidentManager extends \Dbh{
    
    public function getIncidentTypes(){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "incident_types");
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

    public function getIncidentTypeDetails($incidentTypeID){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "incident_types WHERE id = ?");
        if (!$stmt->execute(array($incidentTypeID))) {
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

    public function editIncidentType($incident_code, $incident_name, $id){
        $stmt = $this->connect()->prepare("UPDATE " . DB_PREFIX . "incident_types SET code_id = ?, code_name = ? WHERE id = ?");
        if (!$stmt->execute(array($incident_code, $incident_name, $id))) {
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

    public function deleteIncidentType($id){
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "incident_types WHERE id = ?");
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