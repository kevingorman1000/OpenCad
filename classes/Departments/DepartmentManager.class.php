<?php

namespace Departments;

class DepartmentManager extends \Dbh{
    public function getDepartments(){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "departments");
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

    public function getDepartmentDetails($departmentId){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "departments WHERE department_id = ?");
        if (!$stmt->execute(array($departmentId))) {
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

    public function editDepartment($department_name, $department_short_name, $department_long_name, $departmentID){
        $stmt = $this->connect()->prepare("UPDATE " . DB_PREFIX . "departments SET	department_name = ?, department_short_name = ?, department_long_name = ? WHERE department_id = ?");
        if (!$stmt->execute(array($department_name, $department_short_name, $department_long_name, $departmentID))) {
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

    public function deleteDepartment($departmentID){
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "departments WHERE department_id = ?");
        if (!$stmt->execute(array($departmentID))) {
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