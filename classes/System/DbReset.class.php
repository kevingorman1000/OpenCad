<?php

namespace System;

class dbReset extends \Dbh{
    public function clearData($data){
        $stmt = $this->connect()->prepare("TRUNCATE TABLE " . DB_PREFIX . $data);
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