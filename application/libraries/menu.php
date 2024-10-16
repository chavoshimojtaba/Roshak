<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class menu {

    function add_to_menu ($data, $values='')
    {
        //////pr($_SESSION,1);
        if ($values['selected'] == 'selected') {
            // set field to special section.

            // $helper_clinic->add_to_menu([
            //     'selected'  => 'selected',
            //     'vals'      => [
            //         'exception' => [
            //             'expid' => 'exppppppppp'
            //         ],
            //         'partners'  => [
            //             'cdid'  => 'aaaa',
            //             'cid'   => 'bbbbbb'
            //         ]
            //     ]
            // ]);
            foreach ($values['vals'] as $key => $value) {
                $data[$key] = array_merge($data[$key],$value);
            }
        }
        else if ($values['selected'] == 'all')
        {
            // set field to all exist section.
            // $helper_clinic->add_to_menu(array('selected' => 'all', 'vals' => array('cdid' => $cdid,'cid'=> $cid )));
            foreach ($values['vals'] as $keys => $value) {
                foreach ($data as $key => $row) {
                    $data[$key][$keys] = $values['vals'][$keys];
                }
            }
        }
        return $data;
    }

    function edit_to_menu ($data,$values='')
    {
        // $helper_clinic->edit_to_menu( array('insurance' => array('_cid' => 'ppppppp' ) ,'secretary' => array('lang' => LANG_CLINIC_ADD )));
        foreach ($values as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $data[$key][$key1] = $value1;
            }
        }
        return $data;
    }

    function get_menu ($data)
    {
        return $data;
    }

}
