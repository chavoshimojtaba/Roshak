<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class visit{
	

    var $result = '';
    public $C;
    
    public $method = array(
        'y' => 'year',
        'm' => 'month',
        'd' => 'day',
        'download' => 'download'
    );
    
    private $conf = array(
        'year' => array(),
        'chart' => array(
            'id' => '',
            'type' => 'column', 
            'title' => '',
            'subtitle' => '',
            'y' => '',
            'drilldown' => FALSE,
            'color' => '#3C8DBC'
        ),
        'alias_year' => 'name',
        'alias_visit' => 'y',
        'type' => 'json',
        'json' => array(),
        'return' => 'string'
    );
    
    function __construct() 
    {
        $this->C = &get_instance();
    }

    function __set($name , $value)
    {
        
        if( isset($this->method[$name]) )
        {
            $method = 'visit_'.$this->method[$name];
            $this->$method($value);
        }
        else
        {
            exit('This method not found!');
        }
    }
/*
    $visit->y = array(
            'year' => array(
            '1393', '1394'
        ),
            'chart' => array(
            'type' => 'bar',
            'title' => LANG_VISIT_MONTH,
            'subtitle' => '',
            'y' => 'تعداد'
        ),
            'alias_year' => 'name',
            'alias_visit' => 'y',
            'type' => 'json',
            'json' => array(),
            'return' => 'string',
            'color' => '#3C8DBC'
    );
*/
    function visit_year( $conf ) 
    {
        $conf = $this->conf = array_replace_recursive($this->conf, $conf);
        extract($conf);
        $this->C->load->model("model_log");

        $len_year = count($year);

        switch ($len_year)
        {
            case 0://all between del
                $conf['year'][0] = $year[0] = "2000-01-01";
                $conf['year'][1] = $year[1] = "2150-01-01";
                break;
            case 1://1380  current || 1394 == current=>date php
                $conf['year'][0] = $year[0] = "2000-01-01";
                $conf['year'][1] = $year[1] = date("Y-m-d");
                break;
            case 2:
                $conf['year'][0] = $year[0] = str_replace("/", "", substr(p2g("$year[0]/01/01"), 0, 10));
                $conf['year'][1] = $year[1] = str_replace("/", "", substr(p2g("$year[1]/12/29"), 0, 10));
                break;// between
        }
        
        $res = $this->C->model_log->visit_year($year[0], $year[1], $alias_year, $alias_visit, $type, $json);
        if (isset($chart)) 
        {
            $this->conf['chart']['data'] = implode(',', $res->result);
            $obj_chart = &load_class('chart','libs');
            
            if ($this->conf['return'] != 'string') 
            {
                $this->result[$this->conf['return']] = $obj_chart->execute($this->conf);
            } 
            else 
            {
                $this->result = $obj_chart->execute($this->conf);
            }            
        }
        return $res;

    }
    
    
    /*
    $conf = array(
        'year' => '1394',
        'chart' => array(
            'type' => 'bar', 
            'title' => 'bar',
            'subtitle' => 'subtitle',
            'y' => 'value'
        ),
        'alias_month' => 'month',
        'alias_visit' => 'visit',
        'type' => 'array',
        'json' => array(),
        'return' => 'array',
        'color' => '#3C8DBC'
    );
    */

    public function visit_month( $conf ) 
    {
        $conf = $this->conf = array_replace_recursive($this->conf, $conf);
        extract($conf);
        $this->C->load->model("model_log");
        $res = $this->C->model_log->visit_month($year,$alias_month,$alias_visit,$type,$json);
        if (isset($chart)) 
        {
            $this->conf['chart']['data'] = implode(',', $res->result);
            $obj_chart = &load_class('chart', 'libs');
            if ($this->conf['return'] != 'string') 
            {
                $this->result[$this->conf['return']] = $obj_chart->execute($this->conf);
            } 
            else 
            {
                $this->result = $obj_chart->execute($this->conf);
            }
        }
        return $res;
    }

    /*
      $conf = array(
        'chart' => array(
            'type' => 'bar',
            'title' => 'bar',
            'subtitle' => 'subtitle',
            'y' => 'value'
        ),
        'type' => 'array',
        'json' => array(),
        'return' => 'array',
        'color' => '#3C8DBC'
      );
     */
    public function visit_download( $conf ) 
    {
        $conf = $this->conf = array_replace_recursive($this->conf,$conf);
        extract($conf);
        $this->C->load->model("model_log");
        $data = $this->C->model_log->download_bakhsh($type, $json)->result;
        $drilldown = NULL;
        if( isset($chart['drilldown']) && ($chart['drilldown'] == TRUE) )
        {
            $drilldown = $this->C->model_log->download_filename('array', $json)->result;
        }
        $res = array(
            'data' => $data,
            'drilldown' => $drilldown
        );
        if (isset($chart)) 
        {
            $this->conf['chart']['data'] = implode(',', $data);
            $obj_chart = &load_class("chart","libs");
            if( $drilldown != NULL ) 
            {
                $this->conf['chart']['drilldown'] = $obj_chart->json_drilldown($drilldown);
            }
            if ($this->conf['return'] != 'string') 
            {
                $this->result[$this->conf['return']] = $obj_chart->execute($this->conf);
            } 
            else 
            {
                $this->result = $obj_chart->execute($this->conf);
            }
        }
        return $res;
    }
    
 
    
}

//vist visit->year = array();
// visit->result;