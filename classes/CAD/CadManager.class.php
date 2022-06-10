<?php

namespace CAD;

class CadManager extends \Dbh{

    public function quickStatus($narrativeAdd, $callId){
        $stmt = $this->connect()->prepare("UPDATE ".DB_PREFIX."calls SET call_narrative = concat(call_narrative, ?) WHERE call_id = ?");
        if (!$stmt->execute(array($narrativeAdd, $callId))) {
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

    public function getMyCall($uid){
        $stmt = $this->connect()->prepare("SELECT ".DB_PREFIX."active_users.* from ".DB_PREFIX."active_users WHERE ".DB_PREFIX."active_users.id = ? AND ".DB_PREFIX."active_users.status = '0' AND ".DB_PREFIX."active_users.status_detail = '3'");
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

    public function getUsersCalls($uid){
        $stmt = $this->connect()->prepare("SELECT call_id from ".DB_PREFIX."calls_users WHERE id = ?");
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

    public function getUserCallDetails($uid){
        $stmt = $this->connect()->prepare("SELECT * from ".DB_PREFIX."calls WHERE call_id = ?");
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

    public function checkTone(){
        $stmt = $this->connect()->prepare("SELECT * from ".DB_PREFIX."tones");
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

    public function setTone($status,$tone){
        $stmt = $this->connect()->prepare("UPDATE ".DB_PREFIX."tones SET active = ? WHERE name = ?");
        if (!$stmt->execute(array($status,$tone))) {
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

    public function changeStatus($statusId, $statusDet, $unit){
        $stmt = $this->connect()->prepare("UPDATE ".DB_PREFIX."active_users SET status = ?, status_detail = ? WHERE identifier = ?");
        if (!$stmt->execute(array($statusId, $statusDet, $unit))) {
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

    public function selectActiveCallUsers($unit){
        $stmt = $this->connect()->prepare("SELECT call_id FROM ".DB_PREFIX."calls_users WHERE identifier = ?");
        if (!$stmt->execute(array($unit))) {
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

    public function selectActiveUsers($unit){
        $stmt = $this->connect()->prepare("SELECT callsign FROM ".DB_PREFIX."active_users WHERE identifier = ?");
        if (!$stmt->execute(array($unit))) {
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

    public function updateCallNarrative($narrativeAdd, $callId){
        $stmt = $this->connect()->prepare("UPDATE ".DB_PREFIX."calls SET call_narrative = concat(call_narrative, ?) WHERE call_id = ?");
        if (!$stmt->execute(array($narrativeAdd, $callId))) {
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

    public function deleteActiveCall($unit){
        $stmt = $this->connect()->prepare("DELETE FROM ".DB_PREFIX."calls_users WHERE identifier = ?");
        if (!$stmt->execute(array($unit))) {
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

    public function deleteDispatcher($id){
        $stmt = $this->connect()->prepare("DELETE FROM ".DB_PREFIX."dispatchers WHERE identifier = ?");
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

    public function setDispatcher($id, $callsign, $status){
        $stmt = $this->connect()->prepare("INSERT INTO ".DB_PREFIX."dispatchers (identifier, callsign, status) VALUES (?, ?, ?)");
        if (!$stmt->execute(array($id, $callsign, $status))) {
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

    public function getAOP(){
        $stmt = $this->connect()->prepare("SELECT * from ".DB_PREFIX."aop");
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

    public function getDispatchers(){
        $stmt = $this->connect()->prepare("SELECT * from ".DB_PREFIX."dispatchers WHERE status = '1'");
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

    public function setUnitActive($identifier, $id, $status, $uid){
        $stmt = $this->connect()->prepare("REPLACE INTO ".DB_PREFIX."active_users (identifier, callsign, status, status_detail, id) VALUES (?, ?, ?, '6', ?)");
        if (!$stmt->execute(array($identifier, $id, $status, $uid))) {
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

    public function getAvailableUnits(){
        $stmt = $this->connect()->prepare("SELECT * from ".DB_PREFIX."active_users WHERE status = '1'");
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

    public function getUnAvailableUnits(){
        $stmt = $this->connect()->prepare("SELECT * from ".DB_PREFIX."active_users WHERE status = '0'");
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

    public function getIndividualStatus($callsign){
        $stmt = $this->connect()->prepare("SELECT status_detail FROM ".DB_PREFIX."active_users WHERE callsign = ?");
        if (!$stmt->execute(array($callsign))) {
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

    public function getIndividualStatusText($callsign){
        $stmt = $this->connect()->prepare("SELECT status_text FROM ".DB_PREFIX."statuses WHERE status_id = ?");
        if (!$stmt->execute(array($callsign))) {
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

    public function getIncidentType(){
        $stmt = $this->connect()->prepare("SELECT code_name FROM ".DB_PREFIX."incident_type");
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

    public function getStreet(){
        $stmt = $this->connect()->prepare("SELECT name FROM ".DB_PREFIX."streets");
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

    public function getActiveUnits(){
        $stmt = $this->connect()->prepare("SELECT callsign FROM ".DB_PREFIX."active_users WHERE status = '1'");
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

    public function getActiveUnitsModal(){
        $stmt = $this->connect()->prepare("SELECT callsign, identifier FROM ".DB_PREFIX."active_users WHERE status = '1'");
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

    public function getActiveCalls(){
        $stmt = $this->connect()->prepare("SELECT * from ".DB_PREFIX."calls");
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

    public function getActivePersonBOLO(){
        $stmt = $this->connect()->prepare("SELECT * from ".DB_PREFIX."bolos_persons");
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

    public function getUnitsOnCall($callId){
        $stmt = $this->connect()->prepare("SELECT * FROM ".DB_PREFIX."calls_users WHERE call_id = ?");
        if (!$stmt->execute(array($callId))) {
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

    public function getCallDetails($callId){
        $stmt = $this->connect()->prepare("SELECT * FROM ".DB_PREFIX."calls WHERE call_id = ?");
        if (!$stmt->execute(array($callId))) {
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

    public function getCivilianNamesOption(){
        $stmt = $this->connect()->prepare("SELECT id, name FROM ".DB_PREFIX."ncic_names");
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

    public function callCheck(){
        $stmt = $this->connect()->prepare("SELECT id, name FROM ".DB_PREFIX."ncic_names");
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
