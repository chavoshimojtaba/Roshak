<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class model_util extends DB
{
	public function multi_select($q, $type = 'tags')
	{
		$field = ' ';
		$where = '';
		switch ($type) {
			case 'product':
				$where = " AND (tp_title LIKE '%{$q}%')";
				$field = 'tp_title';
				break;
			case 'product_tags':
				$where = " AND (tp_title LIKE '%{$q}%')";
				$field = 'tp_title';
				break;
			case 'member':
				$where = " AND (tp_name LIKE '%{$q}%' || tp_family LIKE '%{$q}%')";
				$field = " CONCAT( tp_family, ' ', tp_name ) ";
				$type = 'members';
				break;
			default:
				$where = " AND (tp_title LIKE '%{$q}%' )";
				$field = 'tp_title';
				$type = 'tags';
				break;
		}
		return $this->select("
            SELECT
				tp_id AS id,
                " . $field . " AS title
            FROM
                `tp_" . $type . "`
            WHERE
                 `tp_delete` = '0' $where
            ORDER BY   tp_id DESC
        ");
	}
	public function check_slug_exist($table,$slug)
	{

		return $this->select("
            SELECT
				tp_id AS id
            FROM
                `tp_" . $table . "`
            WHERE
                tp_slug LIKE '{$slug}' AND tp_delete = 0
            ORDER BY   tp_id DESC
        ");
	}
	public function seach_in_tags_and_designers_products($q)
	{
		$data['designer'] =  $this->select(
			"SELECT
                tp_slug               AS `slug`,
                CONCAT(
                    tp_family,
                    ' ',
                    tp_name )         AS `full_name`,
                tp_pic                AS `img`
            FROM
                tp_members
            WHERE
				tp_type = 'designer'  AND tp_delete = 0  AND (tp_name LIKE '%{$q}%' OR tp_family LIKE '%{$q}%' )
        "
		)->result;
		$data['tag'] =  $this->select(
			"SELECT
				`tp_title`      AS title,
				`tp_slug`       AS `slug`
			FROM
				tp_product_tags
			WHERE
				`tp_delete` = 0 AND  tp_title LIKE '%{$q}%'
        "
		)->result;
		$data['category'] =  $this->select(
			"SELECT
				`tp_title`      AS title,
				`tp_slug`       AS `slug`
			FROM
				tp_category
			WHERE
				`tp_delete` = 0 AND  tp_title LIKE '%{$q}%'
        "
		)->result;
		$data['product'] =  $this->select(
			"SELECT
                tp_pic                AS `img`,
				`tp_title`      AS title,
				`tp_slug`       AS `slug`
			FROM
				tp_product
			WHERE
				`tp_delete` = 0    AND  tp_title LIKE '%{$q}%'
        "
		)->result;
		return $data;
	}
}
