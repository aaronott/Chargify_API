<?php

class Chargify_Customer extends Chargify_Common
{
  
  public function listCustomers()
  {
    try{
      $list = $this->sendRequest('customers');
    } catch(Exception $e) {
      throw new Chargify_Exception();
    }
  }
}

?>