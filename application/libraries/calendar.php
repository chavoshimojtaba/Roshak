<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'persian_date.php';

function select_time()
{
    $min = '<option value="-1">دقیقه</option>';
    $hour = '<option value="-1">ساعت</option>';
    for ($i = 0; $i < 60; $i++) {
        if ($i < 10) {
            $v = '0' . $i;
        } else $v = $i;
        $min .= '<option value="' . $v . '">' . $v . '</option>';
    }

    for ($i = 1; $i < 25; $i++) {
        if ($i < 10) {
            $v = '0' . $i;
        } else $v = $i;
        $hour .= '<option value="' . $v . '">' . $v . '</option>';
    }
    return array('minute' => $min, 'hour' => $hour);
}

class calendar extends persian_date
{


    var $current_month = 1;

    var $current_year  = 1394;

    var $current_day   = 1;

    var $current_week  = 1;

    var $current_date_array = array();

    var $current_date_json = array();

    private $control;

    private static $base_skin = 'view_calendar';

    var $calendar = array();

    var $event = TRUE;

    var $add_event = TRUE;

    var $result = array();

    var $week = array('1', '2', '3', '4', '5', '7', '0');

    var $cur_date = '';

    var $output = 'full';

    var $holiday = array();

    var $plugin = array();

    var $pdate = '';


    function setup($start = FALSE, $date = '')
    {
        if ($start === FALSE) {
            $this->cur_date = date("Y-m-d");
        } else {
            if ($date == '') $date = date("Y-m-d");
            $date = new DateTime($date);
            if ($start != 0) {
                $date->modify("$start month");
            }
            $this->cur_date = $date->format('Y-m-d');
        }
        $this->control =  &get_instance();
        $this->current_date_array = $this->to_date($this->cur_date, 'array');
        $this->pdate = implode('-', $this->current_date_array);
        list($this->current_date_json['year'], $this->current_date_json['month'], $this->current_date_json['day']) = $this->to_date($this->cur_date, 'array');
    }


    function start_day_of_week($year, $month, $day = '01')
    {
        return $this->week[date('w', strtotime($this->jalali_to_miladi($year, $month, $day, '-')))];
    }


    function persian_calendar($year = 0, $month = 0)
    {
        $this->control->template->restart(self::$base_skin . EXT, DIR_VIEW);
        $day  = 1;
        $week = 1;
        $d = 1;
        $j = 1;
        $start_day_of_week = $this->start_day_of_week($this->current_date_array[0], $this->current_date_array[1]);
        $len = ($this->month_no[(int)$this->current_date_array[1]] + $start_day_of_week);
        $sdow = $start_day_of_week == 7 ? 6 : $start_day_of_week;
        for ($i = 0; $i < $len; $i++) {
            if ($day > $this->month_no[(int)$this->current_date_array[1]]) {
                break;
            }

            if ($i < $sdow) {
                $class = 'empty_day';
                $day   = 0;
            } else {
                $class = 'exist_day';
            }

            if ((int)$this->current_date_array[2] == $day) {
                $class = 'exist_day current_day';
            }
            if ($j == 7  || isset($this->holiday[$day])) //
            {
                $f_day = 'holiday pointer';
                $class_js_exist = 'add_event_day';
                if ($j == 7) {
                    $this->holiday[$day] = LANG_HOLIDAY;
                    $class_js_exist = '';
                    $f_day = 'holiday';
                }
            } else {
                $f_day = 'no_day pointer';
                $class_js_exist = 'add_event_day';
            }

            if ($class == 'empty_day') $class_js_exist = '';

            $this->control->template->assign('f_day', $f_day);
            $this->control->template->assign('class_js_exist', $class_js_exist);
            $title = (isset($this->holiday[$day])) ? $this->holiday[$day] : '';
            $this->control->template->assign('title', $title);

            $class_no = '';
            $this->current_date_json['week'] = $week;
            $this->current_date_json['day'] = $day;
            $_day = $day == 0 ? '&nbsp' : $day;
            $this->control->template->assign('exist_day', $class);
            $this->control->template->assign('day', tr_num($_day, 'fa'));
            $this->control->template->assign('json_date', json_encode($this->current_date_json));
            $this->control->template->parse('body.week.day');
            if ($j == 7 or $day == $this->month_no[(int)$this->current_date_array[1]]) {
                $this->control->template->parse('body.week');
                $j = 0;
                $week++;
            }
            $j++;
            $day++;
        }


        $this->control->template->parse('body');
        $this->calendar['body'] = $this->control->template->text('body');

        $array_day_of_the_week = explode(',', WEEK_SHORTNAMEDAY);

        foreach ($array_day_of_the_week as $d => $n) {
            $this->control->template->assign('name', $n);
            $this->control->template->parse('header_week.name');
        }
        ksort($this->holiday);
        $this->control->template->parse('header_week');
        $this->calendar['header_week'] = $this->control->template->text('header_week');
        $this->control->template->assign('cur_date',  $this->cur_date);
        $this->control->template->assign('month_name', $this->persian_month_name[(int)$this->current_date_array[1]]);
        $this->control->template->assign('year_no', tr_num($this->current_date_array[0], 'fa'));
        $this->control->template->parse('header');
        $this->calendar['header'] = $this->control->template->text('header');


        foreach ($this->holiday as $day => $event) {
            $this->control->template->assign('Hday', $day);
            $this->control->template->assign('Fday', fa_int($day));
            $this->control->template->assign('Month', $this->persian_month_name[(int)$this->current_date_array[1]]);
            $this->control->template->assign('Event', $event);
            $this->control->template->parse('event.item');
        }
        $this->control->template->parse('event');
        $this->calendar['show_event'] = $this->control->template->text('event');

        switch ($this->output) {
            case 'full':
                return $this->release();
                break;
            case 'array':
                return $this->calendar;
                break;
            case 'string':
                return implode('', $this->calendar);
                break;
            case 'json':
                return $this->control->template->out($this->calendar, TRUE, TRUE);
                break;
        }
    }



    function date_event()
    {
        $this->control->template->restart(self::$base_skin . EXT, DIR_VIEW);
        $this->control->template->assign($this->calendar);
        $this->control->load->model("model_calendar");
        $r =  $this->control->model_calendar->fetch_event($this->current_date_json['year'] . '-' . $this->current_date_json['month']);
        for ($i = 0; $i < $r->count; $i++) {
            $this->control->template->assign($r->result[$i]);
            $this->control->template->parse('event.record');
        }

        $this->control->template->parse('event');
        $this->result['event'] = $this->control->template->text('event');
    }


    function date_addevent($desc = '', $post)
    {
        $d = fa_int($post['day']) . '-' . fa_int($post['month']) . '-' . fa_int($post['year']);
        $g = $post['year'] . '-' . $post['month'] . '-' . $post['day'];
        $this->control->template->restart(self::$base_skin . EXT, DIR_VIEW);
        $this->control->template->assign('date_string', $d);
        $this->control->template->assign('date', $g);
        $this->control->template->assign('desc', $desc);
        if ($desc != '') {
            $this->control->template->parse('add_event_day.cancel');
        }
        $this->control->template->parse('add_event_day');
        return $this->control->template->text('add_event_day');
    }

    function release()
    {
        $this->control->template->restart(self::$base_skin . EXT, DIR_VIEW);
        $this->control->template->assign($this->calendar);
        $this->control->template->parse();
        return $this->control->template->text();
    }


    function add_event()
    {
        $post   = $this->control->security->input();
        $array  = array('sdate', 'sminute', 'shour', 'edate', 'eminute', 'ehour', 'view', 'desc');
        $values = array();
        foreach ($array as $key) {
            if (!isset($post[$key])) {
                $error[1] = _MSG_EMPTY_FIELD_;
            } else {
                $values[$key] = "`tp_{$key}`='" . $post[$key] . "'";
            }
        }


        if (isset($error)) {
            $this->control->template->out(get_error($error, FALSE), TRUE);
            return -1;
        }

        $this->control->load->model("model_calendar");
        $this->control->model_calendar->insert_event(implode(',', $values));
        $insert_id = $this->control->model_calendar->result->insert_id;
        $post['to'][$this->control->session->uid] = $this->control->session->uid;
        if (isset($post['to'])) {
            $to = array();
            foreach ($post['to'] as $value) {
                $to[] = "('$insert_id','$value',now())";
            }
            $this->control->model_calendar->insert_event_for_user(implode(',', $to));
        }

        $this->result['success']['msg'] = _MSG_SUCCESS_;
        $this->_set_msg();
    }

    function _set_msg()
    {

        $this->control->template->restart(self::$base_skin . EXT, DIR_VIEW);

        foreach ($this->result as $block => $assign) {
            $this->control->template->assign($assign);
        }

        $this->control->template->parse($block);
        $this->control->template->out($block);
    }

    function get_event()
    {
        $r =  $this->control->model_calendar->fetch_event($this->current_date_json['year'] . '-' . $this->current_date_json['month'] . '-' . $this->current_date_json['day']);
    }

    function remove_event($id)
    {
        $this->control->load->model("model_calendar");
        $this->control->model_calendar->remove_event($id);
        $this->result['success']['msg'] = _MSG_SUCCESS_;
        $this->_set_msg();
    }
}
