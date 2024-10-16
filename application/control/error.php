<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class error extends Controller{
        
    
    function index($error='')
    {
        
        switch ($error)
        {
            case '404':$block = '404';break;
            case '403':$block = '403';break;
            default:   $block = '404';break;
        }
        //get error
        $value['error'] = $block;

        out([
            'content' => assign_value($value,$block)
        ]);
    }
    
    
    function ban ()
    {
        out([
            'content' => assign_value([],'ban')
        ],'site');
    
    }
    

}