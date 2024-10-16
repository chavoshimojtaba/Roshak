<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class chart
{
    public function execute( $conf ) 
    {
        $C = &get_instance();
        $chart = $conf['chart'];
        $C->template->restart(_VIEW.'chart'.EXT, DIR_VIEW);
        $C->template->assign($chart);
        $C->template->parse($chart['type']);
        return $C->template->text($chart['type']);

    }
    
    public function json_drilldown($data) 
    {
        $drill = array();
        // first arrange all name and id without data needed in drilldown
        foreach ($data as $row)
        {
            array_push($drill, array(
                'name' => $row['name'],
                'id' => $row['name'],
                'index' => $row['index'],
                'data' => array()
            ));
        }
        while ( $data )
        {
            $row = array_pop($data);
            for( $i=0; $i < count($drill); $i++ )
            {
                if( $drill[$i]['index'] == $row['index'] )
                {
                    array_push($drill[$i]['data'], array(
                        $row['filename'],
                        (int) $row['count']
                    ));
                }
            }
        }
        return json_encode($drill);
    }

}