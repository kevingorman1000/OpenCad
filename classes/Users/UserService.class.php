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
}