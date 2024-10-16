<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_tag extends DB
{
    
    public function get_list ($params)
    {
        list($q,$order) = create_query_filters($params,[
            'tags'=>['title','date']
        ]);
        return $this->pager(
            "SELECT
                tags.`tp_id`         AS `id`,
                tags.`tp_update`     AS `update`,
                tags.`tp_short_desc` AS `short_desc`,
                tags.`tp_seo_title`  AS `seo_title`,
                tags.`tp_title`      AS `title`, 
                tags.`tp_slug`       AS `slug`,
                tags.`tp_desc`       AS `desc`,
                tags.`tp_meta`       AS `meta`,
                tags.`tp_date`       AS `createAt`
            FROM
                tp_tags AS tags
            WHERE
                tags.`tp_delete` = 0 $q $order
        " ,$params['limit'],$params['page'],true);
    }

    public function get_tag_getail ($id,$by_slug=false)
    {
        $whr = " tags.tp_id =". $id;
        if($by_slug){
            $whr = " tags.tp_slug LIKE '". $id."' ";
        }
        return $this->select(
            "SELECT
                tags.tp_id AS id,
                tags.`tp_short_desc` AS short_desc,
                tags.`tp_update` AS update_date,
                tags.`tp_title` AS title,
                tags.`tp_seo_title` AS seo_title,
                tags.`tp_slug` AS `slug`,
                tags.`tp_desc` AS `desc`,
                tags.`tp_meta` AS meta,
                tags.tp_date AS createAt
            FROM
                tp_tags AS tags
            WHERE
                tags.`tp_delete` = 0  AND $whr
        " );
    }

    public function get_content_tags ($pid)
    {
        return $this->select(
            "SELECT
                tags.tp_slug AS slug,
                tags.tp_id AS id,
                tags.`tp_title` AS title
            FROM
                tp_tags AS tags INNER JOIN
                tp_tags_map AS tag_map ON(tag_map.tp_tid = tags.tp_id AND tag_map.tp_pid = $pid AND  tag_map.`tp_delete` = 0  )
            WHERE
                tags.`tp_delete` = 0
        " );
    }
    
    public function update_tag ($id,$data)
    { 
        return $this->update(
            " UPDATE
                tp_tags
            SET
                `tp_short_desc` = '{$data['short_desc']}',
                `tp_seo_title` = '{$data['seo_title']}',
                `tp_title` = '{$data['title']}',
                `tp_desc` = '{$data['desc']}',
                `tp_meta` = '{$data['meta']}',
                `tp_slug` = '{$data['slug']}', 

                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function delete_tag ($id)
    {
        return $this->update(
            " UPDATE
                tp_tags
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function add_tag ($post)
    {
        return $this->insert(
            " INSERT INTO
                tp_tags
            SET
                tp_seo_title = '{$post['seo_title']}',
                tp_short_desc = '{$post['short_desc']}',
                tp_title = '{$post['title']}',
                tp_slug = '{$post['slug']}',
                tp_meta  = '{$post['meta']}',
                tp_desc  = '{$post['desc']}',
                tp_uid   = {$_SESSION['uid']},
                tp_date  = now()
        ");
    }

    public function add_content_tags ($data,$pid=false)
    {
        if($pid){
            $this->update(
                " UPDATE
                    `tp_tags_map`
                SET
                    `tp_delete` = 1,
                    `tp_update` = now()
                WHERE
                    `tp_pid` = '{$pid}'
            ");
        }
        $rows = [] ;
        foreach ($data as $key =>  $value) {
            $rows[] = "({$value['tid']},{$value['pid']},now())";
        }
        if(count($rows)>0){
            $values = implode(',',$rows);
            return $this->insert(
                " INSERT INTO
                    `tp_tags_map`
                    (tp_tid,tp_pid,tp_date)
                VALUES
                    {$values};
            ");
        }
        return true;
    }

/* public function search ($params)
    {
        $whr ='';
        if(isset($params['q'])){
            $whr =" AND (tags.tp_title LIKE '%{$params['q']}%')";
        }
        return $this->select(
            " SELECT
                tags.tp_id AS id,
                tags.tp_title AS title,
                tags.tp_slug AS slug
            FROM
                tp_tags as   tags
            WHERE
                tags.`tp_delete` = '0' $whr
            ORDER BY  tags.tp_id DESC
        ");
    } */
  /*   public function get_sitemap_list ($params)
    { 
        return $this->pager(
            "SELECT 
               DATE( tags.`tp_update`) AS `update`, 
                tags.`tp_slug`       AS `slug`, 
                DATE(tags.tp_date)         AS createAt
            FROM
                tp_tags AS tags
            WHERE
                tags.`tp_delete` = 0  
        " ,$params['limit'],$params['page']);
    } */
 

}
