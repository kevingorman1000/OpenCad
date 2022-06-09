<?php

namespace Vehicles;

class vehicleManager extends \Dbh
{
    public function getVehicles()
    {
        $stmt = $this->connect()->prepare("SELECT * FROM ".DB_PREFIX."vehicles");
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
}
