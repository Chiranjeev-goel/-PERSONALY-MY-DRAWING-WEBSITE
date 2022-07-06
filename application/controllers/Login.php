<?php

 class Login extends CI_Controller
 {
    public function index()
    {
    $this->load->view('login');
 }
 

 
 
 public function model()

 {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('uname','username','required|alpha');
    $this->form_validation->set_rules('pass','password','required|max_length[12]');
    $this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
 
    
    if($this->form_validation->run())
 {
     $uname=$this->input->post('uname');
     $pass=$this->input->post('pass');
     // echo"Username is".$uname."</br>"."password is".$pass;
     $q =$this->load->model('loginmodel');
     $g = $this->loginmodel->isvalidate($uname,$pass);
      if($g){
         $this->load->library('session');
        $this->session->set_userdata('id',$g);
        $this->load->view('Dashboard/dashboard');
 
      }
      else{
         echo "details not match";
      }
 }
 } 
}
?>