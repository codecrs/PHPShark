<?php 

namespace error{
  class Error extends \AppController{
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        $this->view->render("error");
    }
  }
}
