<?php 

namespace Citations;

class CitationManager extends \Dbh{

    public function getCitationTypeDetails($id){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "citation_types WHERE citation_id = ?");
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

    public function getCitationTypes(){
        $stmt = $this->connect()->prepare("SELECT * FROM " . DB_PREFIX . "citation_types");
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

    public function editCitationType($citation_description, $citation_fine, $id){
        $stmt = $this->connect()->prepare("UPDATE ".DB_PREFIX."citation_types SET citation_description = ?, citation_fine = ? WHERE citation_id = ?");
        if (!$stmt->execute(array($citation_description, $citation_fine, $id))) {
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