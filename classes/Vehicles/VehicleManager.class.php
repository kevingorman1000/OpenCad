<?php

namespace Vehicles;

class vehicleManager extends \Dbh
{
    public function getVehicles()
    {
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "vehicles");
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

    public function getVehicleDetails($vehId)
    {
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "vehicles WHERE id = ?");
        if (!$stmt->execute(array($vehId))) {
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
    public function editVehicle($make, $model, $id)
    {
        $stmt = $this->connect()->prepare("UPDATE " . DB_PREFIX . "vehicles SET make = ?, model = ? WHERE id = ?");
        if (!$stmt->execute(array($make, $model, $id))) {
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

    public function deleteVehicle($id)
    {
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "vehicles WHERE id = ?");
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
