<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_category extends DB
{
	public function get_list ($just_parent=false,$for_website=false)
    {
        $whr = " "; 
        if($just_parent){
            $whr .= ' AND category.`tp_pid` = 0 ';
        }
       
        return $this->select(
            "SELECT
                category.tp_id       AS id,
                category.tp_slug     AS `slug`,
                category.tp_statistic_product  AS statistic_product,
                category.`tp_desc` AS `desc`,
                category.tp_short_desc     AS `short_desc`,
                category.tp_update  AS update_date,
                category.tp_order  AS `order`,
                category.tp_seo_title     AS `seo_title`,
                category.`tp_meta` AS meta, 
                category.tp_publish  AS publish,
                category.tp_cat_path     AS `path`, 
                category.tp_icon    AS icon, 
                category.tp_pid      AS pid,
                category.tp_title    AS title
			FROM
                `tp_category`        AS category
			WHERE
                category.`tp_delete` = '0' $whr
        ");
    }

	public function get_list_sitemap ()
    { 
        
        return $this->select(
            "SELECT
                category.tp_id       AS id,
                category.tp_cat_path     AS `cat_path`, 
                category.tp_pid     AS `pid`, 
                category.tp_order     AS `order`,  
                category.tp_slug     AS `slug`,  
                category.tp_date      AS createAt,
                DATE(category.tp_update)      AS `update` 
			FROM
                `tp_category`        AS category
			WHERE
                category.`tp_delete` = '0'  
        ")->result;
    }

	public function get_list_childs_sitemap ($pids)
    { 
        
        return $this->select(
            "SELECT
                category.tp_id       AS id,
                category.tp_cat_path     AS `cat_path`, 
                category.tp_pid     AS `pid`, 
                category.tp_order     AS `order`,  
                category.tp_slug     AS `slug`,  
                category.tp_date      AS createAt,
                DATE(category.tp_update)      AS `update` 
			FROM
                `tp_category`        AS category
			WHERE
                category.`tp_delete` = '0'  AND ($pids)
        ")->result;
    }

	public function sub_categories ($pid)
    {
        return $this->select(
            "SELECT
                    category.tp_id      AS id,
                    category.tp_pid     AS pid,
                category.tp_order  AS `order`,
                    category.tp_icon    AS icon,
                    category.tp_slug    AS slug,
                    category.tp_title   AS title
			FROM
                `tp_category`        AS category

			WHERE
                category.`tp_delete` = '0' AND category.tp_pid =  $pid
        ");
    }

	public function get_category_detail ($id)
    {
        return $this->select(
            "SELECT
                    category.tp_id      AS id,
                    category.tp_order  AS `order`, 
                    category.tp_pid     AS pid,
                    category.tp_publish AS publish,
                    category.tp_meta    AS meta,
                    category.tp_desc    AS `desc`,
                    category.tp_icon    AS icon,
                    category.tp_slug    AS slug,
                    category.tp_short_desc   AS short_desc,
                    category.tp_seo_title   AS seo_title,
                    category.tp_title   AS title,
                    category.tp_date    AS createAt
			FROM
                `tp_category`        AS category
			WHERE
                category.`tp_delete` = '0' AND category.tp_id=$id
        ");
    } 

    public function update_category ( $data)
    {
        return $this->update("
            UPDATE
                `tp_category`
            SET
                tp_slug     = '{$data['slug']}',
                tp_icon     = '{$data['icon']}',
                tp_meta     = '{$data['meta']}', 
                tp_desc     = '{$data['desc']}',
                `tp_title`  = '{$data['title']}',
                `tp_seo_title`  = '{$data['seo_title']}',
                `tp_short_desc`  = '{$data['short_desc']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$data['id']}'
        ");
    }

    public function dec_inc_cat_product ($id,$type='+')
    { 
        if($type === '-'){
            return $this->update("
                UPDATE
                    tp_category
                SET
                    `tp_statistic_product`    = `tp_statistic_product` - 1 ,
                    `tp_update`     = now()
                WHERE
                    `tp_id` = '{$id}'
            ");
        }else{
            return $this->update("
                UPDATE
                    tp_category
                SET
                    `tp_statistic_product`    = `tp_statistic_product` + 1 ,
                    `tp_update`     = now()
                WHERE
                    `tp_id` = '{$id}'
            ");
        }
    }

    public function update_path ($id,$data)
    {
        $data['path'] = ($data['pid'] == 0)?'_'.$id.'_':$this->get_path($data['pid']).'-_'.$id.'_';

        return $this->update("
            UPDATE
                `tp_category`
            SET
                tp_cat_path     = '{$data['path']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function get_path ($id)
    {
        $res = $this->select(
            "SELECT
                tp_cat_path      AS cat_path
			FROM
                `tp_category`
			WHERE
                `tp_delete` = '0' AND tp_id =  $id
        ");
        if($res->count > 0){
            return $res->result[0]['cat_path'];
        }else{
            return '_'.$id.'_';
        }
    } 
    public function check_slug_exist ($slug)
    {
        $res = $this->select(
            "SELECT
                COUNT(tp_id)      AS cnt
			FROM
                `tp_category`
			WHERE
                `tp_delete` = '0' AND tp_slug LIKE  '{$slug}'
        ");
        if($res->count > 0){
            return false;
        }else{
            return true;
        }
    }
    public function delete_category ($id)
    {
        return $this->update("
            UPDATE
                `tp_category`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");

    }
    public function add_category ($post)
    {
        return $this->insert("
            INSERT INTO
                `tp_category`
            SET
                tp_pid   = '{$post['pid']}',
                tp_icon   = '{$post['icon']}', 
                tp_slug      = '{$post['slug']}',
                tp_meta      = '{$post['meta']}',
                tp_desc      = '{$post['desc']}', 
                tp_title   = '{$post['title']}',
                `tp_seo_title`  = '{$post['seo_title']}',
                `tp_short_desc`  = '{$post['short_desc']}',
                tp_uid      = {$_SESSION['uid']},
                tp_date     = now()
        ");
    }
}
