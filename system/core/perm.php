<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class perm extends Controller {
	
    function set (Router $R)
    {
        if ( $R->default_class == $R->class ) return TRUE;

        if (check_perm ( $R->class ) == FALSE)
        {
        // $R->class  = 'errors';
        // $R->method = 'e_404'; 
        // $R->dir_file =  DIR_CONTROL.'errors'.EXT;
        }
    }
}