<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_pages extends DB
{



    public function get_list ($params)
    {
        $order = 'pages.tp_id DESC';

        return $this->pager(
            "SELECT
                pages.tp_id AS id,
                pages.`tp_title` AS title,
                pages.`tp_desc` AS `desc`,
                pages.tp_date AS createAt
            FROM
                `tp_pages` AS pages
            WHERE
                pages.`tp_delete` = 0
            ORDER BY $order
        " ,$params['limit'],$params['page'],true);
    }

    public function update_about_us_policy ($data,$id=1)
    {
        $data['seo_title'] = isset($data['seo_title'])?$data['seo_title']:'';
        $data['sub_title'] = isset($data['sub_title'])?$data['sub_title']:'none';
        return $this->update("
            UPDATE
                `tp_pages_public`
            SET
				`tp_sub_title` = '{$data['sub_title']}',
				`tp_title` = '{$data['title']}',
				`tp_seo_title`  = '{$data['seo_title']}',
				`tp_meta`  = '{$data['meta']}',
				`tp_desc`  = '{$data['desc']}',
				`tp_cover` = '{$data['cover']}'
            WHERE
                `tp_id` = $id
        ");
    } 

    public function update_pages ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_pages`
            SET
				tp_meta     = '{$data['meta']}',
				tp_title    = '{$data['title']}',
				tp_slug     = '{$data['slug']}',
				tp_desc     = '{$data['desc']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function about_us_policy ($id=1)
    {
        return $this->select(
			"SELECT
				about.`tp_cover` AS `cover`,
				about.`tp_slug`  AS `slug`,
				about.`tp_seo_title`  AS `seo_title`,
				about.`tp_sub_title`  AS `sub_title`,
				about.`tp_meta`  AS `meta`,
				about.`tp_title` AS `title`,
                about.`tp_desc`  AS `desc`
            FROM
                `tp_pages_public` as   about
            WHERE
                about.tp_id = $id
            ORDER BY  about.tp_id DESC limit 1
        ");
    }
    
    public function file_types ($slug)
    {
        return $this->select(
			"SELECT
				pages.`tp_cover` AS `cover`,
				pages.`tp_slug`  AS `slug`,
				pages.`tp_seo_title`  AS `seo_title`,
                pages.`tp_update` AS update_date,
				pages.`tp_sub_title`  AS `sub_title`,
				pages.`tp_sub_title`  AS `short_desc`,
				pages.`tp_meta`  AS `meta`,
				pages.`tp_title` AS `title`,
                pages.`tp_desc`  AS `desc`
            FROM
                `tp_pages_public` as   pages
            WHERE
                pages.tp_slug = '$slug'
            ORDER BY  pages.tp_id DESC limit 1
        ");
    }

    public function member_messages ($params)
    {
        return $this->pager(
			"SELECT
				messages.`tp_id`        AS id,
                messages.tp_read   AS `read`,
                messages.tp_full_name   AS `full_name`,
                messages.tp_email       AS `email`,
                messages.tp_subject     AS `subject`,
                (CASE
                    WHEN messages.tp_mobile = '' THEN '-'
                    ELSE messages.tp_mobile
                END) AS `mobile`,
                messages.tp_exp         AS `exp`,
                messages.tp_date        AS createAt
            FROM
                `tp_pages_contact_us` as   messages
            WHERE
                messages.tp_delete = 0
            ORDER BY  messages.tp_id DESC
        ",$params['limit'],$params['page'],true);
    }

    public function get_page ($params)
    {
        return $this->select(
			"SELECT
                pages.tp_id      AS `id`,
				pages.`tp_slug`  AS `slug`,
				pages.`tp_title` AS `title`,
				pages.`tp_meta`  AS `meta`,
                pages.`tp_desc`  AS `desc`,
                pages.tp_date    AS `createAt`
            FROM
                `tp_pages` as   pages
            WHERE
                pages.`tp_delete` = '0' AND pages.tp_id = {$params['id']}
            ORDER BY  pages.tp_id DESC
        ");
    }

    /*
    * Created     : Wed Aug 17 2022 1:20:21 PM
    * Author      : Chavoshi Mojtaba
    * Description : add member messages in contact us page
    * return      : query res
    */

    public function add_member_message ($post)//contact us
    {
        return $this->insert("
            INSERT INTO
                `tp_pages_contact_us`
            SET
                tp_full_name  = '{$post['full_name']}',
                tp_email      = '{$post['email']}',
                tp_subject    = {$post['subject']},
                tp_exp        = '{$post['exp']}',
                tp_date       = now()
        ");
    }

    public function add_pages ($post)
    {
        return $this->insert("
            INSERT INTO
                `tp_pages`
            SET
                tp_meta  = '{$post['meta']}',
                tp_title = '{$post['title']}',
                tp_slug  = '{$post['slug']}',
                tp_desc  = '{$post['desc']}',
                tp_uid   = '{$_SESSION['uid']}',
                tp_date  = now()
        ");
    }

    public function delete_pages ($id)
    {
        return $this->update("
            UPDATE
                `tp_pages`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }


    public function update_faq($data)
    {
        return $this->update(
            "UPDATE
                `tp_faq_policy`
            SET
                `tp_title` = '{$data['title']}',
                `tp_desc` = '{$data['desc']}',
                `tp_update`         = now()
            WHERE
                `tp_id` = {$data['id']}
        ");
    }

    public function del_faq($id)
    {
        return $this->update(
            "UPDATE
                `tp_faq_policy`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = {$id}
        ");
    }
    function get_faq ($id)
    {
        return $this->select(
            "SELECT
                tp_id         AS `id`,
                tp_title AS `title`,
                tp_desc  AS `desc`
            FROM
                `tp_faq_policy`
            WHERE
                tp_delete = '0' AND tp_id = $id
        ",true);
    }

    function faq_list ($type='faq')
    {
        return $this->select(
            "SELECT
                tp_id         AS `id`,
                tp_title AS `title`,
                tp_desc  AS `desc`
            FROM
                `tp_faq_policy`
            WHERE
                tp_delete = '0'  AND tp_type='$type'
        ",true);
    }

    public function add_faq  ($post)
    {
        return $this->insert("
            INSERT INTO
                `tp_faq_policy`
            SET
                `tp_title`  = '{$post['title']}',
                `tp_desc`   = '{$post['desc']}',
                `tp_type`   = '{$post['type']}',
                tp_date        = now()
        ");
    }

        /* -------------------------------------------------------------------------- */
    /*                                team_members                                */
    /* -------------------------------------------------------------------------- */
    public function get_team_members ($id=0)
    {
        $WHERE = '';
        if( $id > 0 ) $WHERE = " AND `tp_id` = '$id'";
        return $this->select(
            "SELECT
                team_members.tp_id AS id,
                team_members.`tp_social_2` AS `social_2`,
                team_members.`tp_social_3` AS `social_3`,
                team_members.`tp_social_1` AS `social_1`,
                team_members.`tp_pic` AS `pic`,
                team_members.`tp_expert` AS `expert`,
                team_members.`tp_fullname` AS `fullname`
            FROM
                `tp_pages_team` AS  team_members
            WHERE
                team_members.`tp_delete` = 0 $WHERE
        ");
    }

    public function update_team_members ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_pages_team`
            SET
				tp_social_2 = '{$data['social_2']}',
				tp_social_3 = '{$data['social_3']}',
				tp_social_1 = '{$data['social_1']}',
				tp_pic      = '{$data['pic']}',
				tp_expert   = '{$data['expert']}',
				tp_fullname = '{$data['fullname']}',
                `tp_update`  = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function member_messages_read ($id)
    {
        return $this->update("
            UPDATE
                `tp_pages_contact_us`
            SET
                `tp_read` = 'yes',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }
    public function delete_team_members ($id)
    {
        return $this->update("
            UPDATE
                `tp_pages_team`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function add_team_members ($data)
    {
        return $this->insert("
            INSERT INTO
                `tp_pages_team`
            SET
                tp_social_2 = '{$data['social_2']}',
                tp_social_3 = '{$data['social_3']}',
                tp_social_1 = '{$data['social_1']}',
                tp_pic      = '{$data['pic']}',
                tp_expert   = '{$data['expert']}',
                tp_fullname = '{$data['fullname']}',
                tp_date      = now()
        ");
    }

}
