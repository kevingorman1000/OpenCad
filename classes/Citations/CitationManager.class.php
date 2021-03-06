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

    public function deleteCitationType($id){
        $stmt = $this->connect()->prepare("DELETE FROM " . DB_PREFIX . "citation_types WHERE citation_id = ?");
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

    public function getCitations(){
        $stmt = $this->connect()->prepare("SELECT citation_name FROM ".DB_PREFIX."citations");
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

    public function rms_citations(){
        $stmt = $this->connect()->prepare("SELECT ".DB_PREFIX."ncic_names.name, ".DB_PREFIX."ncic_citations.id, ".DB_PREFIX."ncic_citations.citation_name, ".DB_PREFIX."ncic_citations.citation_fine, ".DB_PREFIX."ncic_citations.issued_date, ".DB_PREFIX."ncic_citations.issued_by FROM ".DB_PREFIX."ncic_citations INNER JOIN ".DB_PREFIX."ncic_names ON ".DB_PREFIX."ncic_citations.name_id=".DB_PREFIX."ncic_names.id WHERE ".DB_PREFIX."ncic_citations.status = '1'");
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