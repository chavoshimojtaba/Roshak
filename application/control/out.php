<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class out extends Controller{
    function index ()
    { 
        $this->session->log_out(); 
        redirect(HOST);
        exit;
    }
}