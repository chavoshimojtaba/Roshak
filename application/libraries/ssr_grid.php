<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once DIR_HELPER . "helper_html.php";

class SSR_Grid extends Controller
{
	private array $temp_params = [];
	private array $params = [];
	private array $fetched_data = [];
	private array $config;
	private int $current_page = 1;

	function __construct($params)
	{
		parent::__construct();
		$this->params = array_merge($params,decode_html_tag($this->router->request_get(),true));
		foreach ($this->params as $key => $value) { 
			$explode = explode(',',$value);
			if(count($explode)>1){
				$this->params[$key] = [];
				foreach ($explode as $k => $v) {
					$this->params[$key][] = $v;
				}
			}
		}
		$this->load->model('model_ssr_grid');
		$this->params['limit'] = (array_key_exists('limit',$this->params))?$this->params['limit']:10;
		$this->temp_params =  $this->params;
		$this->params['page'] = (array_key_exists('page',$this->params))?$this->params['page']:1;
		$this->params['q'] = (array_key_exists('q',$this->params))?$this->params['q']:'';
		$this->current_page = ($this->params['page'])?$this->params['page']:1;
		if(isset($params['pagination'])){

		}else{
			$this->Fetch_Data();
		}
	}

	public function Fetch_Data(){

		$this->fetched_data = $this->model_ssr_grid->{$this->params['type']}($this->params);
		include  DIR_CONFIG.$this->params['type'].'/config.php' ;
		if(isMobile()){
			$this->config = $grid_mobile;
		}else{
			$this->config = $grid;
		}
	}
	function getData ()
	{
		$data = $this->params;
		$data['count'] = $this->fetched_data['count'];
		$data['total'] = $this->fetched_data['total'];
		if(isset($this->temp_params['page']) && $this->temp_params['page']>1){
			$totalPages = ceil($data['total'] / $this->temp_params['limit']);
			if($totalPages == 0){ 
				error_404($this);
			}
		}
		// pr($this->params,true);
		return $data;
	}
	public function html()
	{
		$template = $this->config['template'];
		if(is_array($template)){
			if(isset($this->params['template'])){
				$template = $template[$this->params['template']];
			}else{
				$template = array_values($template)[0];
			}
		}
		$html_rows = '';
		$json = json_encode(['tt'=>$this->fetched_data['total'] , 'ct'=>$this->fetched_data['count'],'lm'=>$this->params['limit'],'p'=>$this->params['page'],'ap'=>$this->config['api']]);
		if ($this->fetched_data['count'] > 0) {
			foreach ($this->fetched_data['result'] as $key => $value) {
				$value = decode_html_tag($value,true);
				$value['key'] = $key+1;
				$html_rows .= $this->replace($template,$value);
			}
		}else{
			$svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="ms-2" xmlns="http://www.w3.org/2000/svg">
			<path d="M1 12C1 14.4477 1.13246 16.3463 1.46153 17.827C1.78807 19.2963 2.29478 20.2921 3.00136 20.9986C3.70794 21.7052 4.70365 22.2119 6.17298 22.5385C7.65366 22.8675 9.55232 23 12 23C14.4477 23 16.3463 22.8675 17.827 22.5385C19.2963 22.2119 20.2921 21.7052 20.9986 20.9986C21.7052 20.2921 22.2119 19.2963 22.5385 17.827C22.8675 16.3463 23 14.4477 23 12C23 9.55232 22.8675 7.65366 22.5385 6.17298C22.2119 4.70365 21.7052 3.70794 20.9986 3.00136C20.2921 2.29478 19.2963 1.78807 17.827 1.46153C16.3463 1.13246 14.4477 1 12 1C9.55232 1 7.65366 1.13246 6.17298 1.46153C4.70365 1.78807 3.70794 2.29478 3.00136 3.00136C2.29478 3.70794 1.78807 4.70365 1.46153 6.17298C1.13246 7.65366 1 9.55232 1 12Z" stroke="#E10000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			<path fill-rule="evenodd" clip-rule="evenodd" d="M13 8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8V14C11 14.5523 11.4477 15 12 15C12.5523 15 13 14.5523 13 14V8ZM12 16C11.4477 16 11 16.4477 11 17C11 17.5523 11.4477 18 12 18C12.5523 18 13 17.5523 13 17C13 16.4477 12.5523 16 12 16Z" fill="#E10000"/>
			</svg>';
			if($this->params['type'] === 'product'){
				$html_rows .= '<div class="d-flex flex-column align-items-center  justify-content-center w-100">
				<img width="200px" class="rounded-2" src="'.HOST.'file/client/images/no_result.svg" />
				<div class="fs-4 fw-bold text-secondary">
					نتیجه ای یافت نشد!
				</div>
			</div>'; 
			}else{ 
				if($this->config['container_element'] == 'tbody'){
					$html_rows .= '<tr><td colspan="100%" class="border-0 px-0"><div class="alert alert-danger w-100 my-2 border-0 py-2 d-flex align-items-center justify-content- " role="alert">
						'.$svg.'<span class="pe-2">'.LANG_NONE_DATA_FOUNDED.' </span></div></td></tr>';
				}else{
					if(strpos($this->config['container_class'],'row') >= 0){
						$html_rows .= '<div class="col-12"><div class=" alert alert-danger w-100 my-2 border-0 py-2 d-flex align-items-center " role="alert">'.$svg.' '.LANG_NONE_DATA_FOUNDED.' </div></div>';
					}else{
						$html_rows .= '<div class=" my-2 alert alert-danger w-100 " role="alert">'.LANG_NONE_DATA_FOUNDED.' </div>';
					}
				}
			}
		}
		// pr($this->fetched_data,true);
		$element = isset($this->config['container_element'])?$this->config['container_element']:'div';
		if(!$this->params['no_template']){
			$html_rows .= '<'.$element.' style="display:none !important" class="ssr_grid_template"><!-- '.$template.'--></'.$element.'>';
		}
		return '<'.$element.' class="ssr_grid '.$this->config['container_class'].'"  '.@$this->config['container_attr'].'  data-jsn=\''.$json.'\'>
		'.$html_rows.'
		</'.$element.'>';
	}

	public function json()
	{
		$rows = [];
		if ($this->fetched_data['count'] > 0) {
			foreach ($this->fetched_data['result'] as $key => $value) {
				$value['key'] = $key+1;
				$rows[] = decode_html_tag($value,true);
			}
			$this->fetched_data['result'] = $rows;
		}

		return $this->fetched_data;
	}


	function replace($template,$data)
	{
		$data['img_tag'] = 'img';
		$data['a'] = 'a';
		$data['href'] = 'href';
		$data['HOST'] = HOST;
		foreach ($data as $key => $value) {
			$template = str_replace('['.$key.']',$value,$template);
		}
		return $template;
	}



	/* -------------------------------------------------------------------------- */
	/*                                 pagination                                 */
	/* -------------------------------------------------------------------------- */

	function pagination_records_counter($totalRecordsPerPage)
	{
		$currentPage = $this->current_page;
		$totalPreviousRecords = ($currentPage - 1) * $totalRecordsPerPage;
		$dataCounter = $totalPreviousRecords + 1;
		return $dataCounter;
	}

	function previous_page()
	{
		$currentPage = $this->current_page;
		$previousPage = $currentPage - 1;
		if ($currentPage > 1) {
			$previous = "<a class='previous' data-page='" . $previousPage . "'>".LANG_PREVIOUS."</a>";
			return $previous;
		}
	}

	function next_page($totalPages)
	{
		$currentPage = $this->current_page;
		$nextPage = $currentPage + 1;
		if ($currentPage < $totalPages) {
			$next = "<a class='next' data-page='" . $nextPage . "' >".LANG_NEXT."</a>";
			return $next;
		}
	}
	function pagination_numbers($totalPages)
	{

		$currentPage = $this->current_page;
		$adjacents = "2";
		$second_last = $totalPages - 1; // total pages minus 1
		$pagelink = '';
		if ($totalPages <= 5) {
			for ($counter = 1; $counter <= $totalPages; $counter++) {
				if ($counter == $currentPage) {
					$pagelink .= "<a class='current '>" . $counter . "</a>";
				} else {
					$pagelink .= "<a data-page='" . $counter . "'>" . $counter . "</a>";
				}
			}
		} elseif ($totalPages > 5) {
			if ($currentPage <= 4) {
				for ($counter = 1; $counter < 4; $counter++) {
					if ($counter == $currentPage) {
						$pagelink .= "<a class='current ' data-page='" . $counter . "'>" . $counter . "</a>";
					} else {
						$pagelink .= "<a data-page='" . $counter . "'>" . $counter . "</a>";
					}
				}
				$pagelink .= "<a class=' more'>...</a>";
				$pagelink .= "<a data-page='" . $second_last . "'>" . $second_last . "</a>";
				$pagelink .= "<a data-page='" . $totalPages . "'>" . $totalPages . "</a>";
				// //pr(546,true);
			} elseif ($currentPage > 4 && $currentPage < $totalPages - 4) {
				$pagelink .= "<a href='?=1'>1</a>";
				$pagelink .= "<a data-page='2'>2</a>";
				$pagelink .= "<a class=' more'>...</a>";

				for (
					$counter = $currentPage - $adjacents;
					$counter <= $currentPage + $adjacents;
					$counter++
				) {
					if ($counter == $currentPage) {
						$pagelink .= "<a class='current '>" . $counter . "</a>";
					} else {
						$pagelink .= "<a data-page='" . $counter . "'>" . $counter . "</a>";
					}
				}
				$pagelink .= "<a class=' more'>...</a>";
				$pagelink .= "<a data-page='" . $second_last . "'>" . $second_last . "</a>";
				$pagelink .= "<a data-page='" . $totalPages . "'>" . $totalPages . "</a>";
			} else {

				$pagelink .= "<a data-page='1'>1</a>";
				$pagelink .= "<a data-page='2'>2</a>";
				$pagelink .= "<a class=' more'>...</a>";
				for (
					$counter = $totalPages - 2;
					$counter <= $totalPages;
					$counter++
				) {
					if ($counter == $currentPage) {
						$pagelink .= "<a class='current '>" . $counter . "</a>";
					} else {
						$pagelink .= "<a data-page='" . $counter . "'>" . $counter . "</a>";
					}
				}
			}
		}
		return $pagelink;
	}

	public function pagination($totalRecordsPerPage,$totalRecords)
	{
		$totalPages = ceil($totalRecords / $totalRecordsPerPage);
		pr($totalPages,true);
		$pagination = '<div class="blog_pagination"><div class="pagination">';
		$pagination .= $this->previous_page();
		$pagination .= $this->pagination_numbers($totalPages);
		$pagination .= $this->next_page($totalPages);
		$pagination .= '</div></div>';
		return $pagination;
	}
}
