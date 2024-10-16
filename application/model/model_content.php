<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_content extends DB
{
	public function list_loc ( $just_parent=false,$for_website=false)
    {
        $whr = " ";
        $feilds = "";
        
        if($just_parent === 'info'){
            $feilds = ' 
            location_content.tp_desc     AS `desc`,
            location_content.tp_seo_title     AS `seo_title`,
            location_content.tp_meta     AS `meta`,
            ';
        }else if($just_parent){
            $whr .= ' AND location_content.`tp_pid` = 0 ';
        }
        if($for_website){
            $whr .= " AND location_content.`tp_publish` = 1 ";
            $feilds = '
                location_content.`tp_desc` AS `desc`,
                location_content.tp_seo_title     AS `seo_title`,
                location_content.`tp_meta` AS seo_meta,
            ';
        }
        
        return $this->select(
            "SELECT
                location_content.tp_id       AS id,
                location_content.tp_tags     AS `tags`,
                location_content.tp_slug     AS `slug`,
                location_content.tp_statistic_product  AS statistic_product,
                location_content.tp_short_desc     AS `short_desc`,
                location_content.tp_update  AS update_date, 
                {$feilds}
                location_content.tp_publish  AS publish,
                location_content.tp_path     AS `path`,
                $feilds 
                location_content.tp_type     AS `type`,
                location_content.tp_pid      AS pid,
                location_content.tp_order    AS `order`,
                location_content.tp_title    AS title
			FROM
                `tp_location_content`        AS location_content
			WHERE
                location_content.`tp_delete` = '0'   ORDER BY location_content.`tp_order` ASC
        ");
    }

    public function order_loc ($id,$value)
    { 
        return $this->update("
            UPDATE
                `tp_location_content`
            SET
                tp_order     = {$value},
                `tp_update` = now()
            WHERE
                `tp_id` = {$id}
        ");
    }

    public function update_loc ($data)
    {
        return $this->update("
            UPDATE
                `tp_location_content`
            SET
                `tp_slug`     = '{$data['slug']}', 
                `tp_meta`     = '{$data['meta']}',
                `tp_tags`   = '{$data['tags']}',
                `tp_type`   = '{$data['type']}',
                `tp_desc`     = '{$data['desc']}',
                `tp_title`  = '{$data['title']}',
                `tp_seo_title`  = '{$data['seo_title']}',
                `tp_short_desc`  = '{$data['short_desc']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$data['id']}'
        ");
    }

    public function delete_loc ($id)
    {
        return $this->update("
            UPDATE
                `tp_location_content`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");

    }
    public function add_loc ($data)
    {
        return $this->insert("
            INSERT INTO
                `tp_location_content`
            SET
                tp_slug     = '{$data['slug']}', 
                tp_meta     = '{$data['meta']}',
                tp_tags   = '{$data['tags']}',
                tp_type   = '{$data['type']}',
                tp_desc     = '{$data['desc']}',
                `tp_title`  = '{$data['title']}',
                `tp_seo_title`  = '{$data['seo_title']}',
                `tp_short_desc`  = '{$data['short_desc']}',
                tp_uid      = {$_SESSION['uid']},
                tp_date     = now()
        ");
    }






	public function get_list_sitemap ()
    { 
        
        return $this->select(
            "SELECT
                location_content.tp_id       AS id,
                location_content.tp_path     AS `path`, 
                location_content.tp_pid     AS `pid`, 
                location_content.tp_filetype     AS `filetype`,  
                location_content.tp_slug     AS `slug`,  
                location_content.tp_date      AS createAt,
                DATE(location_content.tp_update)      AS `update` 
			FROM
                `tp_location_content`        AS location_content
			WHERE
                location_content.`tp_delete` = '0'  
        ")->result;
    }

	public function get_list_childs_sitemap ($pids)
    { 
        
        return $this->select(
            "SELECT
                location_content.tp_id       AS id,
                location_content.tp_path     AS `path`, 
                location_content.tp_pid     AS `pid`, 
                location_content.tp_filetype     AS `filetype`,  
                location_content.tp_slug     AS `slug`,  
                location_content.tp_date      AS createAt,
                DATE(location_content.tp_update)      AS `update` 
			FROM
                `tp_location_content`        AS location_content
			WHERE
                location_content.`tp_delete` = '0'  AND ($pids)
        ")->result;
    } 

	public function sub_categories ($pid)
    {
        return $this->select(
            "SELECT
                    location_content.tp_id      AS id,
                    location_content.tp_pid     AS pid,
                location_content.tp_filetype  AS filetype,
                    location_content.tp_icon    AS icon,
                    location_content.tp_slug    AS slug,
                    location_content.tp_title   AS title
			FROM
                `tp_location_content`        AS location_content

			WHERE
                location_content.`tp_delete` = '0' AND location_content.tp_pid =  $pid
        ");
    } 

	public function get_location_content_detail ($id)
    {
        return $this->select(
            "SELECT
                    location_content.tp_id      AS id,
                location_content.tp_filetype  AS filetype,
                    location_content.tp_type    AS `type`,
                    location_content.tp_pid     AS pid,
                    location_content.tp_publish AS publish,
                    location_content.tp_meta    AS meta,
                    location_content.tp_desc    AS `desc`,
                    location_content.tp_icon    AS icon,
                    location_content.tp_slug    AS slug,
                    location_content.tp_short_desc   AS short_desc,
                    location_content.tp_seo_title   AS seo_title,
                    location_content.tp_title   AS title,
                    location_content.tp_date    AS createAt
			FROM
                `tp_location_content`        AS location_content
			WHERE
                location_content.`tp_delete` = '0' AND location_content.tp_id=$id
        ");
    } 
    
    public function dec_inc_cat_product ($id,$type='+')
    { 
        if($type === '-'){
            return $this->update("
                UPDATE
                    tp_location_content
                SET
                    `tp_statistic_product`    = `tp_statistic_product` - 1 ,
                    `tp_update`     = now()
                WHERE
                    `tp_id` = '{$id}'
            ");
        }else{
            return $this->update("
                UPDATE
                    tp_location_content
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
                `tp_location_content`
            SET
                tp_path     = '{$data['path']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function get_path ($id)
    {
        $res = $this->select(
            "SELECT
                tp_path      AS path
			FROM
                `tp_location_content`
			WHERE
                `tp_delete` = '0' AND tp_id =  $id
        ");
        if($res->count > 0){
            return $res->result[0]['path'];
        }else{
            return '_'.$id.'_';
        }
    } 

    
}
