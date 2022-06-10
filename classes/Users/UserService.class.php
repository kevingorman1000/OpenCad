<?php

namespace Users;

class UserService extends \Dbh{

    public function LoginUser($email){
        $stmt = $this->connect()->prepare("SELECT id, name, password, email, identifier, admin_privilege, password_reset, approved, suspend_reason FROM ".DB_PREFIX."users WHERE email = ?");
        if (!$stmt->execute(array($email))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            foreach($results as $result){
                return $result;
            }
        }
    }

    public function LogOutUser($identifier){
        $stmt = $this->connect()->prepare("DELETE FROM ".DB_PREFIX."active_users WHERE identifier = ?");
        if (!$stmt->execute(array($identifier))) {
            $_SESSION['error'] = $stmt->errorInfo();
            header('Location: ' . BASE_URL . '/plugins/error/index.php');
            die();
        }
        if ($stmt->rowCount() <= 0) {
            return false;
        } else {
            $results = $stmt->fetchAll();
            foreach($results as $result){
                return $result;
            }
        }
    }

    public function getUserDetails($uid){
        $stmt = $this->connect()->prepare("SELECT id, name, email, identifier, admin_privilege FROM ".DB_PREFIX."users WHERE ID = ?");
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

    public function getUserGroupsEditor($uid){
        $stmt = $this->connect()->prepare("SELECT ".DB_PREFIX."departments.department_name FROM ".DB_PREFIX."user_departments INNER JOIN ".DB_PREFIX."departments on ".DB_PREFIX."user_departments.department_id=".DB_PREFIX."departments.department_id WHERE ".DB_PREFIX."user_departments.user_id = ?");
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