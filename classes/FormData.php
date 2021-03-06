<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/aLog.php');


class FormData extends aLog{

  var $row;
  var $type;
  var $date;
  var $index;
  var $id;
  var $isComplete;

  public function __construct($row, $type){
    $this->row = $row;
    $this->type = $type;
    if(isset($row['Date'])){
      $this->date = $row['Date'];
    } else {
      //Enable server time caching
      date_default_timezone_set('America/New_York');
      $current_date = date('d/m/Y  H:i:s');
      $this->row['Date'] = $current_date;
      $this->date = $current_date;
    }
    if(isset($row['id'])){
      $this->id = $row['id'];
    }
    if($type == "Work Order"){
      if($row['dateComplete'] == 0){
        $this->isComplete = false;
      } else {
        $this->isComplete = $row['dateComplete'];
      }
    }
  }

  public function del(){
    $d = Database::getInstance();
    if($this->type === "Work Order"){
      $q = "DELETE FROM WorkOrder WHERE id=".$this->id;
    } else {
      $q = "DELETE FROM Contact WHERE id=".$this->id;
    }
    if($d->query($q)){
      return true;
    } else {
      return false;
    }
  }


  public function genDoc(){
    switch ($this->type) {
      case 'Work Order':
      return $this->genWorkOrder();
      break;
      case 'Contact':
      return $this->genContactForm();
      break;
    }
  }

  private function genWorkOrder(){
    $row = $this->row;
    $c = 'Date Uploaded: ' . $row['Date'] .nLine;
    $c .= 'First Name: ' . $row['fName'] . nLine;
    $c .= 'Last Name: ' . $row['lName'] .nLine;
    $c .= 'Request: '.nLine;
    $r = $row['request'];
    $r = str_split($r, 100);
    foreach($r as $line){
      $c .= $line . nLine;
    }
    $c .= 'Address: ' . $row['address'] . nLine;
    $c .= 'Zip: ' . $row['zip'] . nLine;
    $c .= 'City: ' . $row['city'] . nLine;
    $c .= 'Email: ' . $row['email'] . nLine;
    return $c;
  }

  private function genContactForm(){
    $row = $this->row;
    $c = 'Date Uploaded: ' . $row['Date'] .nLine;
    $c .= 'First Name: ' . $row['fName'] . nLine;
    $c .= 'Last Name: ' . $row['lName'] .nLine;
    $c .= 'Message: '.nLine;
    $r = $row['message'];
    $r = str_split($r, 100);
    foreach($r as $line){
      $c .= $line . nLine;
    }
    $c .= "Email Address: " . $row['email'] . nLine;
    return $c;
  }

  //For Work Order Completion
  public function mark(){
    $db = Database::getInstance();
    $query = "UPDATE WorkOrder SET dateComplete=NOW() WHERE id=".$this->id;
    $res = $db->query($query);
    return $res;
  }

  public function unMark(){
    $db = Database::getInstance();
    $blankDate = "0000-00-00 00:00:00";
    $query = "UPDATE WorkOrder SET dateComplete=0 WHERE id=".$this->id;
    $res = $db->query($query);
    return $res;
  }






}


?>
