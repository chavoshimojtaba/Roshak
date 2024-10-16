<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class logout extends Controller{ 
    
    function index ()
    { 
        $this->session->log_out(); 
        redirect(BASE_URL.'login');
    }
 
}
