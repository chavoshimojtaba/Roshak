<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class log_errors {



	function get_error ( $error = array() )
	{
		if ( count ($error) > 0 )
		{
			$C = &get_instance();
			$li = '';
			$lii = '';
			foreach ( $error as $index=>$error )
			{
				$name = array ();
				$name = explode ('|',$index);
				$li  .= '<li class="error_list_item" aria-item="'.$name[0].'">'.$error.'</li>';
				$lii .= '<li class="error_list_item" aria-item="'.$name[0].'">'.$error."[$index]".'</li>';
			}
			$ul = '<ul class="list_error">'.$lii.'</ul>';

			$C->load->model("model_log_error");

			//$C->model_log_error->insert_log($ul,$C->router->ip(),$C->router->query_string (),$C->router->dir_file);

			//////pr($C->router);

		}
	}


}

?>