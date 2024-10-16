<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_settings extends DB{

    function get_resources ()
    {
        return $this->select(
            "SELECT
                `tp_id`   AS `id`,
                `tp_title`   AS `title_fa`,
                `tp_title`   AS `title`,
                `tp_date` AS `date`
            FROM
                `tp_resource`
            WHERE
                tp_delete = '0'
        ");
    }
    
    function get_seo_info ()
    {

        return $this->select(
            "SELECT
                tp_id             AS `id`,
                tp_tel            AS `tel`,
                tp_email          AS `email`,
                tp_system_name AS `system_name`,
                tp_owner       AS `owner`,
                tp_copy_right  AS `copy_right`,
                tp_address     AS `address`,
                tp_footer_desc         AS `footer_desc`,
                tp_exp         AS `exp`,
                tp_mobile       AS `mobile`,
                tp_keywords       AS `keywords`,
                tp_keywords       AS `keywords`,
                tp_home_desc       AS `home_desc`,
                tp_sub_title_home       AS `sub_title_home`,
                tp_h1_home       AS `h1_home`,
                tp_seo_meta_home       AS `seo_meta_home`,
                tp_seo_title_home       AS `seo_title_home`,
                tp_social_instagram  AS `social_instagram`,
                tp_social_youtube  AS `social_youtube`,
                tp_social_telegram  AS `social_telegram`,
                tp_social_whatsapp  AS `social_whatsapp`,
                tp_social_linkedin  AS `social_linkedin`,
                tp_social_aparat  AS `social_aparat`,
                tp_social_pinterest  AS `social_pinterest`
            FROM
                `cms_setting_general`
            WHERE
                tp_delete = '0' limit 1
        ");
    }
    
    function home_desc ()
    {

        return $this->select(
            "SELECT 
                tp_home_desc       AS `home_desc`
            FROM
                `cms_setting_general`
            WHERE
                tp_delete = '0' limit 1
        ")->result[0]['home_desc'];
    }

    public function update_seo($data)
    {
        return $this->update(
            "UPDATE
                `cms_setting_general`
            SET
                `tp_system_name`    = '{$data['system_name']}',
                `tp_owner`          = '{$data['owner']}',
                `tp_copy_right`     = '{$data['copy_right']}',
                `tp_tel`            = '{$data['tel']}',
                `tp_footer_desc`            = '{$data['footer_desc']}',
                `tp_mobile`            = '{$data['mobile']}',
                `tp_seo_title_home` = '{$data['seo_title_home']}',
                `tp_h1_home`  = '{$data['h1_home']}',
                `tp_seo_meta_home`  = '{$data['seo_meta_home']}',
                `tp_sub_title_home` = '{$data['sub_title_home']}',
                `tp_home_desc`          = '{$data['home_desc']}',
                `tp_email`          = '{$data['email']}',
                `tp_address`        = '{$data['address']}',
                `tp_keywords`       = '{$data['keywords']}',
                `tp_exp`            = '{$data['exp']}',
                `tp_update`         = now()
            WHERE
                `tp_id` = 1
        ");
    }

    public function social_update($data)
    {
        return $this->update(
            "UPDATE
                `cms_setting_general`
            SET
                `tp_social_instagram` = '{$data['social_instagram']}',
                `tp_social_youtube` = '{$data['social_youtube']}',
                `tp_social_telegram` = '{$data['social_telegram']}',
                `tp_social_whatsapp` = '{$data['social_whatsapp']}',
                `tp_social_linkedin` = '{$data['social_linkedin']}',
                `tp_social_aparat` = '{$data['social_aparat']}',
                `tp_social_pinterest` = '{$data['social_pinterest']}' ,
                `tp_update`         = now()
            WHERE
                `tp_id` = 1
        ");
    }

    /* -------------------------------------------------------------------------- */
    /*                                user_stories                                */
    /* -------------------------------------------------------------------------- */
    public function get_user_stories ( $id=0,$params=[] )
    {
        $WHERE = '';
        if( $id > 0 ) $WHERE = " AND `tp_id` = '$id'";
        return $this->pager(
            "SELECT
                user_stories.tp_id AS id,
                user_stories.`tp_url` AS `url`,
                user_stories.`tp_pic` AS `pic`,
                user_stories.`tp_text` AS `text`,
                user_stories.`tp_fullname` AS `fullname`,
                user_stories.`tp_sub_title` AS `sub_title`
            FROM
                `tp_user_stories` AS  user_stories
            WHERE
                user_stories.`tp_delete` = 0 $WHERE
                " ,isset($params['limit'])?$params['limit']:10,isset($params['page'])?$params['page']:1,true);
    }

    public function update_user_stories ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_user_stories`
            SET
				tp_pic       = '{$data['pic']}',
				tp_text      = '{$data['text']}',
                tp_url = '{$data['url']}',
				tp_fullname  = '{$data['fullname']}',
				tp_sub_title = '{$data['sub_title']}',
                `tp_update`  = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function delete_user_stories ($id)
    {
        return $this->update("
            UPDATE
                `tp_user_stories`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function add_user_stories ($data)
    {
        return $this->insert("
            INSERT INTO
                `tp_user_stories`
            SET
                tp_pic       = '{$data['pic']}',
                tp_text      = '{$data['text']}',
                tp_fullname  = '{$data['fullname']}',
                tp_url = '{$data['url']}',
                tp_sub_title = '{$data['sub_title']}',
                tp_date      = now()
        ");
    }

    /* -------------------------------------------------------------------------- */
    /*                               email_tamplate                               */
    /* -------------------------------------------------------------------------- */

    public function fetch_email_tamplate ( $id=0 )
    {
        $WHERE = '';
        if( $id > 0 ) $WHERE = " AND `tp_id` = '$id'";

        return $this->select("
            SELECT
                `tp_id`       AS `id`,
                `tp_title`    AS `title`,
                `tp_eid`      AS `eid`,
                `tp_template` AS `template`
            FROM
                `sys_email_template`
            WHERE
                `tp_delete` = '0'
            $WHERE
        ");
    }

    public function fetch_sms_tamplate($id=0)
    {
        $WHERE = '';

        if( $id > 0 ) $WHERE = " AND `tp_id` = '$id'";

        return $this->select("
            SELECT
                `tp_id`             AS `id`,
                `tp_title`          AS `title`,
                `tp_template`       AS `template`
            FROM
                `sys_sms_template`
            WHERE
                `tp_delete` =   '0'
                $WHERE
        ");
    }

    function fetch_template_by_eid ($eid)
    {
        return $this->select("
            SELECT
                `tp_title`    AS `title`,
                `tp_template` AS `template`
            FROM
                `sys_email_template`
            WHERE
                `tp_delete` = '0'
            AND `tp_eid`    = '$eid'
        ");
    }

    /* -------------------------------------------------------------------------- */
    /*                                  back_liks                                 */
    /* -------------------------------------------------------------------------- */

    public function get_public_links ( $params)
    {
        list($q,$order) = create_query_filters($params,[
            'public_links'=>['type','title'] 
        ]);  
        return $this->pager(
            "SELECT
                public_links.`tp_id` AS id,
                public_links.`tp_type` AS `type`,
                public_links.`tp_url` AS `url`,
                public_links.`tp_title` AS `title`
            FROM
                `tp_public_links` AS  public_links
            WHERE
                public_links.`tp_delete` = 0   $q $order
        " ,isset($params['limit'])?$params['limit']:10,isset($params['page'])?$params['page']:1,true);
    }
    public function get_public_link_detail ( $id=0 ,$type='')
    {
        $WHERE = '';
        if( $id > 0 ) $WHERE = " AND `tp_id` = '$id'"; else  if( $type != '' ){
            $WHERE = " AND public_links.`tp_type` = '$type'";
        }

       
        return $this->select(
            "SELECT
                `tp_id` AS id,
                `tp_type` AS `type`,
                `tp_url` AS `url`,
                `tp_title` AS `title`
            FROM
                `tp_public_links` AS  public_links
            WHERE
                `tp_delete` = 0   $WHERE
        ",true);
    }


    public function update_public_links ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_public_links`
            SET
				tp_title      = '{$data['title']}',
				tp_type      = '{$data['type']}',
                tp_url = '{$data['url']}',
                `tp_update`  = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function delete_public_links ($id)
    {
        return $this->update("
            UPDATE
                `tp_public_links`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function add_public_links ($data)
    {
        return $this->insert("
            INSERT INTO
                `tp_public_links`
            SET
				tp_type      = '{$data['type']}',
                tp_title      = '{$data['title']}',
                tp_url = '{$data['url']}',
                tp_date      = now()
        ");
    }

    /* -------------------------------------------------------------------------- */
    /*                                footer_links                                */
    /* -------------------------------------------------------------------------- */

    public function footer_link_list ()
    {

        return $this->select(
            "SELECT
                footer_links.tp_id AS id,
                footer_links.`tp_pid` AS `pid`,
                footer_links.`tp_type` AS `type`,
                footer_links.`tp_date` AS `createAt`,
                footer_links.`tp_url` AS `url`,
                footer_links.`tp_title` AS `title`
            FROM
                `tp_footer_links` AS  footer_links
            WHERE
                footer_links.`tp_delete` = 0 AND footer_links.`tp_pid` <> 0 order by footer_links.tp_id desc
        ",true);
    }

    public function footer_link_columns ()
    {
        return $this->select(
            "SELECT
                footer_links.tp_id AS id,
                footer_links.`tp_title` AS `title`
            FROM
                `tp_footer_links` AS  footer_links
            WHERE
                footer_links.`tp_delete` = 0 AND footer_links.`tp_pid` = 0
        ",true);
    }

    public function update_footer_columns ($data)
    {
        $res=[];
        foreach ($data as $key => $value) {
            $res = $this->update("
                UPDATE
                    `tp_footer_links`
                SET
                    tp_title      = '{$value}',
                    `tp_update`  = now()
                WHERE
                    `tp_id` = '{$key}'
            ");
        }
        return $res;
    }

    public function update_footer_links ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_footer_links`
            SET
				tp_title      = '{$data['title']}',
                tp_url = '{$data['url']}',
                tp_pid      = '{$data['pid']}',
                `tp_update`  = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function delete_footer_links ($id)
    {
        return $this->update("
            UPDATE
                `tp_footer_links`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function add_footer_links ($data)
    {
        return $this->insert("
            INSERT INTO
                `tp_footer_links`
            SET
                tp_pid      = '{$data['pid']}',
                tp_title      = '{$data['title']}',
                tp_url = '{$data['url']}',
                tp_date      = now()
        ");
    }

    /* -------------------------------------------------------------------------- */
    /*                                footer_links                                */
    /* -------------------------------------------------------------------------- */

    public function get_header_links ( $params=0 )
    {
        $WHERE = '';
        if( isset($params['id']) && $params['id'] > 0 ) $WHERE = " AND `tp_id` = {$params['id']}";
        return $this->select(
            "SELECT
                header_links.tp_id AS id,
                header_links.`tp_date` AS `createAt`,
                header_links.`tp_url` AS `url`,
                header_links.`tp_title` AS `title`
            FROM
                `tp_header_links` AS  header_links
            WHERE
                header_links.`tp_delete` = 0   $WHERE order by header_links.tp_id desc
        ",true);
    }


    public function update_header_links ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_header_links`
            SET
				tp_title      = '{$data['title']}',
                tp_url = '{$data['url']}',
                `tp_update`  = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function delete_header_links ($id)
    {
        return $this->update("
            UPDATE
                `tp_header_links`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function add_header_links ($data)
    {
        return $this->insert("
            INSERT INTO
                `tp_header_links`
            SET
                tp_title      = '{$data['title']}',
                tp_url = '{$data['url']}',
                tp_date      = now()
        ");
    }


    /* -------------------------------------------------------------------------- */
    /*                                event                                */
    /* -------------------------------------------------------------------------- */

    public function get_events ( $params = [] )
    {
        $WHERE = '';
        if( isset($params['id']) && $params['id'] > 0 ) $WHERE = " AND `tp_id` = {$params['id']}";
        if( isset($params['count']) && $params['count'] > 0 ) {
            $WHERE = "  AND events.`tp_delete` = 0     AND (events.`tp_start_date` BETWEEN DATE(NOW())  AND DATE(DATE_ADD(now(), INTERVAL 30 DAY)) ) ";
        }
        return $this->select(
            "SELECT
                events.tp_id AS id,
                events.`tp_class` AS `className`,
                events.`tp_start_date` AS `start`,
                events.`tp_end_date` AS `end`,
                events.`tp_value` AS `value`,
                events.`tp_title` AS `title`
            FROM
                `tp_events` AS  events
            WHERE
                events.`tp_delete` = 0   $WHERE order by events.tp_start_date desc
        ",true);
    }


    public function update_event ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_events`
            SET
				tp_title = '{$data['title']}',
                tp_value = '{$data['value']}',
                `tp_update`  = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function delete_event ($id)
    {
        return $this->update("
            UPDATE
                `tp_events`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function add_event ($data)
    { 
        return $this->insert("
            INSERT INTO
                `tp_events`
            SET
                tp_start_date = '{$data['start']}',
                tp_end_date   = '{$data['end']}',
                tp_class  = '{$data['className']}',
                tp_title      = '{$data['title']}',
                tp_value      = '{$data['value']}',
                tp_date       = now()
        ");
    }

}