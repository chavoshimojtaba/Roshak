<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function tr_num($str, $mod = 'en', $mf = '٫')
{
	$num_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.');
	$key_a = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $mf);
	return ($mod == 'fa') ? str_replace($num_a, $key_a, $str) : str_replace($key_a, $num_a, $str);
}

class persian_date
{

	public $persian_month_names = array('','فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'); 
    public $persian_day_names   = array('یکشنبه','دوشنبه','سه شنبه','چهارشنبه','پنجشنبه','جمعه','شنبه');
    public $persian_day_small   = array('ی','د','س','چ','پ','ج','ش');

    public $j_days_in_month    = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29); 


	var $month_no   = array(
		1 => 31,
		2 => 31,
		3 => 31,
		4 => 31,
		5 => 31,
		6 => 31,
		7 => 30,
		8 => 30,
		9 => 30,
		10 => 30,
		11 => 30,
		12 => 29
	);

	var $persian_month_name = array(
		1 => PERSIAN_MONTH_ONE,
		2 => PERSIAN_MONTH_TWO,
		3 => PERSIAN_MONTH_THREE,
		4 => PERSIAN_MONTH_FOUR,
		5 => PERSIAN_MONTH_FIVE,
		6 => PERSIAN_MONTH_SIX,
		7 => PERSIAN_MONTH_SEVEN,
		8 => PERSIAN_MONTH_EIGHT,
		9 => PERSIAN_MONTH_NINE,
		10 => PERSIAN_MONTH_TEN,
		11 => PERSIAN_MONTH_ELEVEN,
		12 => PERSIAN_MONTH_TWELVE
	);

	var $persian_week_name = array(
		1 => PERSIAN_WEEK_ONE,
		2 => PERSIAN_WEEK_TWO,
		3 => PERSIAN_WEEK_THREE,
		4 => PERSIAN_WEEK_FOUR,
		5 => PERSIAN_WEEK_FIVE
	);

	private function div($a, $b)
	{
		return (int) ($a / $b);
	}

	private function gregorian_to_persian($g_y, $g_m, $g_d)
	{

		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		$gy = $g_y - 1600;
		$gm = $g_m - 1;
		$gd = $g_d - 1;
		$g_day_no = 365 * $gy + $this->div($gy + 3, 4) - $this->div($gy + 99, 100) + $this->div($gy + 399, 400);
		for ($i = 0; $i < $gm; ++$i)
			$g_day_no += $g_days_in_month[$i];
		if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
			/* leap and after Feb */
			$g_day_no++;
		$g_day_no += $gd;
		$j_day_no = $g_day_no - 79;
		$j_np = $this->div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
		$j_day_no = $j_day_no % 12053;
		$jy = 979 + 33 * $j_np + 4 * $this->div($j_day_no, 1461); /* 1461 = 365*4 + 4/4 */
		$j_day_no %= 1461;
		if ($j_day_no >= 366) {
			$jy += $this->div($j_day_no - 1, 365);
			$j_day_no = ($j_day_no - 1) % 365;
		}
		for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
			$j_day_no -= $j_days_in_month[$i];
		$jm = $i + 1;
		$jd = $j_day_no + 1;
		if (strlen($jm) == 1) $jm = '0' . $jm;
		if (strlen($jd) == 1) $jd = '0' . $jd;
		return array($jy, $jm, $jd);
	}

	function jalali_to_miladi($j_y, $j_m, $j_d, $mod = '')
	{
		$j_y = tr_num($j_y);
		$j_m = tr_num($j_m);
		$j_d = tr_num($j_d);/* <= :اين سطر ، جزء تابع اصلي نيست */
		$d_4 = ($j_y + 1) % 4;
		$doy_j = ($j_m < 7) ? (($j_m - 1) * 31) + $j_d : (($j_m - 7) * 30) + $j_d + 186;
		$d_33 = (int)((($j_y - 55) % 132) * .0305);
		$a = ($d_33 != 3 and $d_4 <= $d_33) ? 287 : 286;
		$b = (($d_33 == 1 or $d_33 == 2) and ($d_33 == $d_4 or $d_4 == 1)) ? 78 : (($d_33 == 3 and $d_4 == 0) ? 80 : 79);
		if ((int)(($j_y - 19) / 63) == 20) {
			$a--;
			$b++;
		}
		if ($doy_j <= $a) {
			$gy = $j_y + 621;
			$gd = $doy_j + $b;
		} else {
			$gy = $j_y + 622;
			$gd = $doy_j - $a;
		}
		foreach (array(0, 31, ($gy % 4 == 0) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31) as $gm => $v) {
			if ($gd <= $v) break;
			$gd -= $v;
		}
		return ($mod == '') ? array($gy, $gm, $gd) : $gy . $mod . $gm . $mod . $gd;
	}

	public function persian_to_gregorian($j_y, $j_m, $j_d)
	{
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		$jy = $j_y - 979;
		$jm = $j_m - 1;
		$jd = $j_d - 1;
		$j_day_no = 365 * $jy + $this->div($jy, 33) * 8 + $this->div($jy % 33 + 3, 4);
		for ($i = 0; $i < $jm; ++$i)
			$j_day_no += $j_days_in_month[$i];

		$j_day_no += $jd;
		$g_day_no = $j_day_no + 79;
		$gy = 1600 + 400 * $this->div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
		$g_day_no = $g_day_no % 146097;
		$leap = true;

		if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */ {
			$g_day_no--;
			$gy += 100 * $this->div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
			$g_day_no = $g_day_no % 36524;
			if ($g_day_no >= 365)
				$g_day_no++;
			else
				$leap = false;
		}

		$gy += 4 * $this->div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
		$g_day_no %= 1461;

		if ($g_day_no >= 366) {
			$leap = false;
			$g_day_no--;
			$gy += $this->div($g_day_no, 365);
			$g_day_no = $g_day_no % 365;
		}

		for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
			$g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);

		$gm = $i + 1;
		$gd = $g_day_no + 1;
		if (strlen($gm) == 1) $gm = '0' . $gm;
		if (strlen($gd) == 1) $gd = '0' . $gd;
		$dateMilady = $gy . '-' . $gm . '-' . $gd;
		return $dateMilady;
	}

	public function to_date($g_date, $input)
	{

		$g_date = str_replace('-', '', $g_date);
		$g_date = str_replace('/', '', $g_date);
		$g_year = substr($g_date, 0, 4);
		$g_month = substr($g_date, 4, 2);
		$g_day = substr($g_date, 6, 2);
		$persian_date = $this->gregorian_to_persian($g_year, $g_month, $g_day);
		// pr($this->persian_month_name[(int)$persian_date[1]],true);
		if ($input == 'array') return $persian_date;
		if ($input == 'Y') return $persian_date[0];
		if ($input == 'y') return substr($persian_date[0], -2);
		if ($input == 'M') return $this->persian_month_name[(int)$persian_date[1]];
		if ($input == 'm') return $persian_date[1];
		if ($input == 'D') return $this->persian_day_names[date('w')];
		if ($input == 'd') return $persian_date[2];
		if ($input == 'j') {
			$persian_d = $persian_date[2];
			if ($persian_d[0] == '0') $persian_d = substr($persian_d, 1);
			return $persian_d;
		}

		if ($input == 'n') {
			$persian_n = $persian_date[1];
			if ($persian_n[0] == '0') $persian_n = substr($persian_n, 1);
			return $persian_n;
		}



		if ($input == 'Y/m/d') return $persian_date[0] . '/' . $persian_date[1] . '/' . $persian_date[2];
		if ($input == 'Ymd') return $persian_date[0] . $persian_date[1] . $persian_date[2];
		if ($input == 'y/m/d') return substr($persian_date[0], -2) . '/' . $persian_date[1] . '/' . $persian_date[2];
		if ($input == 'ymd') return substr($persian_date[0], -2) . $persian_date[1] . $persian_date[2];
		if ($input == 'd/m/Y') return $persian_date[2] . '/' . $persian_date[1] . '/' . $persian_date[0];
		if ($input == 'dmY') return $persian_date[2] . $persian_date[1] . $persian_date[0];
		if ($input == 'Y-m-d') return $persian_date[0] . '-' . $persian_date[1] . '-' . $persian_date[2];
		if ($input == 'y-m-d') return substr($persian_date[0], -2) . '-' . $persian_date[1] . '-' . $persian_date[2];
		if ($input == 'd-m-Y') return $persian_date[2] . '-' . $persian_date[1] . '-' . $persian_date[0];
		if ($input == 'Y-m-d') return $persian_date[2] . '-' . $persian_date[1] . '-' . substr($persian_date[0], -2);
		if ($input == 'dmy')   return $persian_date[2] . $persian_date[1] . substr($persian_date[0], -2); //$persian_date[2].'-'.$persian_date[1].'-'.
		if ($input == 'string') {
			return substr($persian_date[0], 2, 2) . $persian_date[1] . $persian_date[2];
		}


		if ($input == 'compelete') {
			$persian_d = $persian_date[2];
			if ($persian_d[0] == '0') $persian_d = substr($persian_d, 1);
			return $this->persian_day_names[date('w')] . ' ' . $persian_d . ' ' . $this->persian_month_names[$persian_date[1]] . ' ' . $persian_date[0];
		}
	}

	public function date($input)

	{
		return $this->to_date(date('Y') . date('m') . date('d'), $input);
	}


	public function date_to($j_date)

	{
		$j_date = str_replace('/', '', $j_date);
		$j_date = str_replace('-', '', $j_date);
		$j_year = substr($j_date, 0, 4);
		$j_month = substr($j_date, 4, 2);
		$j_day = substr($j_date, 6, 2);
		$gregorian_date = $this->persian_to_gregorian($j_year, $j_month, $j_day);
		return $gregorian_date[0] . '-' . $gregorian_date[1] . '-' . $gregorian_date[2];
	}

	public function increase_date($j_date, $plus)
	{
		$j_date = str_replace('/', '', $j_date);
		$j_date = str_replace('-', '', $j_date);
		$j_year = substr($j_date, 0, 4);
		$j_month = substr($j_date, 4, 2);
		$j_day = substr($j_date, 6, 2);
		$tommarrow = mktime(0, 0, 0, $j_month, $j_day + $plus, $j_year);
		return date("Y-m-d", $tommarrow);
	}
}
