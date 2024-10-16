<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_blog extends DB
{

    private $sort = [
        'createAt'  => 'blog.tp_date ',
        'category'  => 'category.tp_title ',
        'title'     => 'blog.tp_title '
    ];

    public function q_type($params)
    {
        $types = [
            'title' => " AND blog.tp_title LIKE '%{$params['q']}%' "
        ];
        return $types[$params['q_type']];
    }

    public function get_count ($params=[])
    {
        $q = '';
        $where = '';
        if(isset($params['q'])  && $params['q'] != ''){
            if(!isset($params['q_type']) || $params['q_type'] != ''){
                $params['q_type'] = 'title';
            }
            $q = $this->q_type($params) ;
        }

        if(isset($params['cid'])  && $params['cid'] > 0 ){
            $where =" AND (category.`tp_id` = {$params['cid']} OR category.`tp_pid` = {$params['cid']})";
        }

        return $this->select(
            "SELECT
               COUNT(blog.tp_id) AS cnt
            FROM
                `tp_blog` AS blog
            WHERE
                blog.`tp_delete` = 0    {$q}
        " )->result[0]['cnt'];
    }

  /*   public function get_list ($params=[])
    {
        $where = '';
        $q = '';
        $order = 'blog.tp_id DESC';

        if(isset($params['sort_by']) && isset($this->sort[$params['sort_by']])  && $params['sort_type'] != null){
            $order = $this->sort[$params['sort_by']] .$params['sort_type'];
        }

        if(isset($params['q'])  && $params['q'] != ''){
            if(!isset($params['q_type']) || $params['q_type'] != ''){
                $params['q_type'] = 'title';
            }
            $q = $this->q_type($params) ;
        }

        if(isset($params['cid'])  && $params['cid'] > 0 ){
            $where =" AND (category.`tp_id` = {$params['cid']} OR category.`tp_pid` = {$params['cid']})";
        }

        return $this->pager(
            "SELECT
                blog.tp_id            AS `id`,
                blog.`tp_cid`         AS `cid`,
                blog.`tp_title`       AS `title`,
                blog.`tp_pic`         AS `img`,
                blog.`tp_tags`        AS `tags`,
                CONCAT(
                    users.tp_name,
                     ' ',
                    users.tp_family ) AS `full_name`,
                blog.`tp_slug`        AS `slug`,
                blog.`tp_meta`        AS `meta`,
                blog.`tp_desc`        AS `desc`,
                blog.`tp_short_desc`  AS `short_desc`,
                category.`tp_title`   AS `category`,
                blog.`tp_date`        AS `createAt`
            FROM
                `tp_blog`     AS blog     INNER JOIN
                `tp_category` AS category ON(category.tp_id = blog.tp_cid $where ) INNER JOIN
                `tp_users`    AS users    ON(users.tp_id =blog.tp_uid)
            WHERE
                blog.`tp_delete` = 0 $q
            ORDER BY $order
        " ,isset($params['limit'])?$params['limit']:10,isset($params['page'])?$params['page']:1,true);
    }
 */    public function get_list ($params=[])
    {
        $where = '';
        $q = '';
        $order = 'blog.tp_id DESC';

        if(isset($params['sort_by']) && isset($this->sort[$params['sort_by']])  && $params['sort_type'] != null){
            $order = $this->sort[$params['sort_by']] .$params['sort_type'];
        }

        if(isset($params['q'])  && $params['q'] != ''){
            if(!isset($params['q_type']) || $params['q_type'] != ''){
                $params['q_type'] = 'title';
            }
            $q = $this->q_type($params) ;
        }

        if(isset($params['cid'])  && $params['cid'] > 0 ){
            $where =" AND (category.`tp_id` = {$params['cid']} OR category.`tp_pid` = {$params['cid']})";
        }

        return $this->pager(
            "SELECT
                blog.tp_id            AS `id`,
                blog.`tp_url`         AS `url`,
                blog.`tp_pic`         AS `img`,
                blog.`tp_blog_date`        AS `blog_date`,
                blog.`tp_avatar`        AS `avatar`,
                blog.`tp_title`        AS `title`,
                blog.`tp_reading_duration`        AS `reading_duration`,
                blog.`tp_author`  AS `author`
            FROM
                `tp_blog`     AS blog
            WHERE
                blog.`tp_delete` = 0 $q
            ORDER BY $order
        " ,isset($params['limit'])?$params['limit']:10,isset($params['page'])?$params['page']:1,true);
    }

    public function get_blog_detail ($id)
    {
        return $this->select(
            "SELECT
                blog.tp_id            AS `id`,
                blog.`tp_pic`         AS `img`,
                blog.`tp_cid`         AS `cid`,
                blog.`tp_uid`         AS `uid`,
                blog.`tp_tags`        AS `tags`,
                blog.`tp_title`       AS `title`,
                blog.`tp_slug`        AS `slug`,
                blog.`tp_meta`        AS `meta`,
                blog.`tp_desc`        AS `desc`,
                blog.`tp_short_desc`  AS `short_desc`,
                cat.tp_title          AS `category`,
                CONCAT(
                    users.tp_name,
                     ' ',
                    users.tp_family ) AS `user_name`,
                users.tp_pic          AS user_pic,
                users.tp_mail         AS user_email,
                blog.tp_date          AS createAt
            FROM
                `tp_blog`     AS blog  INNER JOIN
                `tp_category` AS cat   ON(cat.tp_id = blog.tp_cid ) INNER JOIN
                `tp_users`    AS users ON(users.tp_id =blog.tp_uid)
            WHERE
                blog.`tp_delete` = 0 AND blog.tp_id = $id
            ORDER BY blog.tp_id DESC
        ");
    }


/*     public function update_blog ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_blog`
            SET
                `tp_cid`        = '{$data['cid']}',
                `tp_pic`        = '{$data['pic']}',
                `tp_tags`       = '{$data['tags']}',
                `tp_slug`       = '{$data['slug']}',
                `tp_title`      = '{$data['title']}',
                `tp_meta`       = '{$data['meta']}',
                `tp_desc`       = '{$data['desc']}',
                `tp_short_desc` = '{$data['short_desc']}',
                `tp_update`     = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    } */
    public function update_blog ($id,$post)
    {

        return $this->update("
            UPDATE
                `tp_blog`
            SET
            tp_pic        = '{$post['pic']}',
            tp_url       = '{$post['url']}',
            tp_blog_date       = '{$post['blog_date']}',
            tp_reading_duration       = '{$post['reading_duration']}',
            tp_author       = '{$post['author']}',
            tp_title      = '{$post['title']}',
            tp_avatar = '{$post['avatar']}',
            tp_uid        = '{$_SESSION['uid']}',
                `tp_update`     = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function add_file_relation ($data)
    {
        $rows = [] ;
        foreach ($data['files'] as $key =>  $value) {
            $rows[] = "({$key},{$data['pbid']},{$data['type']},now())";
        }
        $values = implode(',',$rows);
        return $this->insert("
            INSERT INTO
                `sys_file_relation`
                (tp_fid,tp_rid,tp_type,tp_date)
            VALUES
                {$values};
        ");

    }

    public function add_content_tags ($data)
    {
        $rows = [] ;
        foreach ($data['tags'] as $key =>  $value) {
            $rows[] = "({$key},{$data['pbid']},{$data['type']},{$_SESSION['uid']},now())";
        }
        $values = implode(',',$rows);
        return $this->insert("
            INSERT INTO
                `tp_tags_map`
                (tp_tid,tp_pbid,tp_type,tp_uid,tp_date)
            VALUES
                {$values};
        ");
    }

    public function get_blog ($id)
    {
        return $this->select(
            "SELECT
                blog.`tp_id`         AS `id`,
                blog.`tp_pic`        AS `pic`,
                blog.`tp_avatar`       AS `avatar`,
                blog.`tp_url`       AS `url`,
                blog.`tp_author`       AS `author`,
                blog.`tp_blog_date`       AS `blog_date`,
                blog.`tp_reading_duration`       AS `reading_duration`,
                blog.`tp_title`      AS `title`,
                blog.`tp_date`       AS `createAt`
            FROM
                `tp_blog` as   blog
            WHERE
                blog.`tp_delete` = '0'
                AND
                blog.`tp_id` = $id
            ORDER BY
                blog.`tp_id` DESC
        ");
    }

    /* public function add_blog ($post)
    {
        return $this->insert(
            "INSERT INTO
                `tp_blog`
            SET
                tp_cid        = '{$post['cid']}',
                tp_pic        = '{$post['pic']}',
                tp_slug       = '{$post['slug']}',
                tp_desc       = '{$post['desc']}',
                tp_meta       = '{$post['meta']}',
                tp_title      = '{$post['title']}',
                tp_short_desc = '{$post['short_desc']}',
                tp_tags       = '{$post['tags']}',
                tp_uid        = '{$_SESSION['uid']}',
                tp_date       = now()
        ");
    }   */
    public function add_blog ($post)
    {
        return $this->insert(
            "INSERT INTO
                `tp_blog`
            SET
                tp_cid        = '1',
                tp_pic        = '{$post['pic']}',
                tp_blog_date       = '{$post['blog_date']}',
                tp_reading_duration       = '{$post['reading_duration']}',
            tp_url       = '{$post['url']}',
                tp_author       = '{$post['author']}',
                tp_title      = '{$post['title']}',
                tp_avatar = '{$post['avatar']}',
                tp_uid        = '{$_SESSION['uid']}',
                tp_date       = now()
        ");
    }






    public function delete_blog ($id)
    {
        return $this->update("
            UPDATE
                `tp_blog`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }


}
