<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_comment extends DB
{
    public $_table = 'tp_product_comments' ;
    public $_tableType  ='product';
    public function get_rate ($pbid)
    {
        $res= $this->select(
            "SELECT
                COUNT(comments.`tp_rate`) AS cnt,
                FORMAT(AVG(comments.`tp_rate`),1) AS rate
            FROM
                tp_product_comments AS comments
            WHERE
                comments.`tp_delete` = 0 AND comments.`tp_pbid`= {$pbid} AND comments.`tp_publish` = 'publish'  AND comments.`tp_pid` = 0
        ");
        if($res->count>0 && $res->result[0]['cnt'] > 0){
            return $res->result[0];
        }
        return ['rate'=> '0','cnt'=>0];
    }
    public function get_content_comments ($pbid)
    {
        $whr = '';
        if(!isset($_SESSION['admin'])){
            $whr = " AND comments.`tp_publish` = 'publish'";
        }
        return $this->select(
            "SELECT
                comments.tp_id 			AS id,
                comments.`tp_mid` 		AS mid,
                comments.`tp_statistic_like` 	AS statistic_like,
                comments.`tp_publish` 	AS publish,
                comments.`tp_pbid` 		AS pbid,
                comments.`tp_pid` 		AS pid,
                comments.`tp_rate` 		AS rate,
                comments.`tp_uid` 		AS `uid`,
                '$this->_tableType'     AS `type`,
                comments.`tp_text` 		AS `text`,
                CASE
                    comments.`tp_mid`
                    WHEN 0 THEN
                    users.tp_pic
                    ELSE members.tp_pic
                END 					AS `img`,
                CASE
                    comments.`tp_mid`
                    WHEN 0 THEN
                    CONCAT( users.tp_name, ' ', users.tp_family )
                    ELSE
                    CONCAT( members.tp_name, ' ', members.tp_family )
                END 					AS `full_name`,
                comments.tp_date 		AS createAt
            FROM
                tp_product_comments AS comments
                LEFT JOIN `tp_members` AS members ON ( members.tp_id = comments.tp_mid )
                LEFT JOIN `tp_users` AS users ON ( users.tp_id = comments.tp_uid)
            WHERE
                comments.`tp_delete` = 0 AND comments.`tp_pbid`= {$pbid} $whr
        ",true);
    }
    public function get_content_comments_of_members ($params)
    {
        if($$this->_tableType=='product'){
            $pic_field = 'content.`tp_name` AS `content_name`, content.tp_thumbnail AS `content_pic`';
            $content_table = 'tp_products';
        }else{
            $pic_field = 'content.`tp_title` AS `content_name`, content.tp_pic AS `content_pic`';
            $content_table = 'tp_blog';
        }

        return $this->pager(
            "SELECT
                comments.tp_id 			AS id,
                comments.`tp_mid` 		AS mid,
                comments.`tp_publish` 	AS publish,
                comments.`tp_pbid` 		AS pbid,
                comments.`tp_pid` 		AS pid,
                comments.`tp_rate` 		AS rate,
                '$this->_tableType'         		AS `type`,
                comments.`tp_uid` 		AS `uid`,
                comments.`tp_text` 		AS `text`,
                {$pic_field},
                comments.tp_date 		AS createAt
            FROM
                tp_product_comments AS comments
                INNER JOIN $content_table  AS content ON ( content.tp_id = comments.tp_pbid )
            WHERE
                comments.`tp_delete` = 0 AND comments.`tp_mid`= {$params['mid']}
        ",$params['limit'],$params['page']);
    }
    public function get_detail ($id)
    {

        return $this->select(
            "SELECT
                comments.`tp_mid` 		AS mid,
                comments.`tp_path` 	AS `path`,
                comments.`tp_parent_mid` 	AS parent_mid,
                comments.`tp_publish` 	AS publish,
                comments.`tp_pbid` 		AS pbid,
                comments.`tp_pid` 		AS pid
            FROM
                tp_product_comments AS comments
            WHERE
                comments.`tp_delete` = 0 AND comments.`tp_id`= {$id}
        ")->result[0];
    }
    public function get_statistics()
    {
        return $this->select(
            "SELECT
                SUM(IF(tp_publish = 'publish', 1, 0)) AS approved,
                SUM(IF(tp_publish = 'reject', 1, 0)) AS reject,
                SUM(IF(tp_publish = 'pend', 1, 0)) AS pend
            FROM tp_product_comments
            WHERE `tp_delete` = 0 AND tp_pid =0
        ");
    }

	public function add_reply ($post )
    {
        $res =  $this->insert(
            "INSERT INTO
                tp_product_comments
            SET
				tp_mid			= {$post['mid']},
				tp_pid			= {$post['pid']},
				tp_uid			= {$post['uid']},
				tp_pbid			= {$post['pbid']},
				tp_parent_mid   = {$post['parent_mid']},
				tp_publish		= '{$post['publish']}',
				tp_text			= '{$post['text']}',
                tp_date       = now()
        ");
        if($res->insert_id){
            $id =  $res->insert_id;
            $path=($post['pid']>0)?$post['path'].'-_'.$id.'_':'_'.$id.'_';
              $this->update("UPDATE
                tp_product_comments
                SET
                    `tp_path` = '{$path}',
                    `tp_update` = now()
                WHERE
                    `tp_id` = '{$id}'
            ");
        }
        return $res;
    }

    
    public function recent_comments ($type)
    {
        $whr = " AND comments.tp_type={$type} ";
        if($type == 'all'){
            $whr = '';
        }
        if(!isset($_SESSION['admin'])){
            $whr .= " AND comments.`tp_publish` = 'publish'";
        }
        return $this->select(
            "SELECT
				comments.tp_id 			AS id,
				comments.`tp_mid` 		AS mid,
				comments.`tp_publish` 	AS publish,
				comments.`tp_pbid` 		AS pbid,
				comments.`tp_pid` 		AS pid,
				comments.`tp_rate` 		AS rate,
                members.tp_pic 			AS `img`,
				CONCAT(
                    members.tp_name,
                    ' ',
                    members.tp_family ) AS `full_name`,
				comments.`tp_text` 		AS `text`,
				comments.tp_date 		AS createAt
			FROM
                tp_product_comments AS comments
				LEFT JOIN `tp_members` AS members ON ( members.tp_id = comments.tp_mid )
			WHERE
				comments.`tp_delete` = 0 AND comments.tp_uid=0   $whr
			ORDER BY
				comments.tp_id DESC limit 5
        ");
    }

    public function get_list ($params)
    {
        return $this->pager(
            "SELECT
				comments.`tp_id`		AS id,
				comments.`tp_mid` 		AS mid,
				comments.`tp_publish` 	AS publish,
				comments.`tp_pbid` 		AS pbid,
				'product' 	AS `type`,
				comments.`tp_pid` 		AS pid,
				comments.`tp_rate` 		AS rate,
				CASE
					comments.`tp_mid`
					WHEN 0 THEN
					users.tp_pic
					ELSE members.tp_pic
				END 					AS `img`,
				CASE
					comments.`tp_mid`
					WHEN 0 THEN
					CONCAT(
                        users.tp_name,
                        ' ',
                        users.tp_family
                    )
					ELSE
					CONCAT(
                        members.tp_name,
                        ' ',
                        members.tp_family
                    )
				END 					AS `full_name`,
				comments.`tp_uid` 		AS `uid`,
				comments.`tp_text` 		AS `text`,
				answers.tp_text 		AS ans,
				comments.tp_date 		AS createAt
			FROM
				tp_product_comments AS comments
				LEFT JOIN `tp_members` AS members ON ( members.tp_id = comments.tp_mid )
				LEFT JOIN `tp_users` AS users ON ( users.tp_id = comments.tp_uid )
				LEFT JOIN (SELECT  tp_text,tp_pid FROM tp_product_comments )   answers ON (answers.tp_pid = comments.tp_id)
			WHERE
				comments.`tp_delete` = 0  AND comments.`tp_pid` = 0
			GROUP BY
				comments.tp_id
			ORDER BY
				comments.tp_id DESC
        " ,$params['limit'],$params['page'],true);
    }

    public function get_comment ($get)
    {
        $join = '';
        $fields = '';
        if(isset($get['pbid'])){
            $whr = ' AND  comments.tp_pbid = '.$get['pbid'];
        }else{
            $whr = ' AND (comments.`tp_id` = '.$get['id'].'  OR comments.`tp_pid` = '.$get['id'].')';
        }
		if($this->_tableType == 'blog'){
			$join = 'INNER JOIN `tp_blog` AS content ON ( content.tp_id = comments.tp_pbid )';
			$fields = 'content.`tp_title` AS content_title,content.`tp_pic` AS content_pic,';
		}else if($this->_tableType == 'product'){
			$fields = 'content.`tp_title` AS content_title,content.`tp_pic` AS content_pic,';
			$join = 'INNER JOIN `tp_product` AS content ON ( content.tp_id = comments.tp_pbid )';
		}


        return $this->pager(
            "SELECT
				comments.tp_id 			AS id,
				comments.`tp_mid` 		AS mid,
				comments.`tp_publish` 	AS publish,
                comments.`tp_statistic_like` 	AS statistic_like,
				comments.`tp_pbid` 		AS pbid,
				comments.`tp_pid` 		AS pid,
				comments.`tp_rate` 		AS rate,
                '$this->_tableType'     AS `type`,
				comments.`tp_uid` 		AS `uid`,
				comments.`tp_text` 		AS `text`,
				$fields
				CASE
					comments.`tp_mid`
					WHEN 0 THEN
					users.tp_pic
					ELSE members.tp_pic
				END 					AS `img`,
				CASE
					comments.`tp_mid`
					WHEN 0 THEN
					CONCAT( users.tp_name, ' ', users.tp_family )
					ELSE
					CONCAT( members.tp_name, ' ', members.tp_family )
				END 					AS `full_name`,
				comments.tp_date 		AS createAt
			FROM
				tp_product_comments AS comments
				$join
				LEFT JOIN `tp_members` AS members ON ( members.tp_id = comments.tp_mid )
				LEFT JOIN `tp_users` AS users ON ( users.tp_id = comments.tp_uid)
			WHERE
				comments.`tp_delete` = 0  $whr
        ",$get['limit'],$get['page'],true);
    }

    public function get_content_comment ($post )
    {
        $res=[];
        $whr = ' ' ;
        if(isset($post['mid']) && $post['mid']>0){
            $whr .= ' AND comments.tp_mid='.$post['mid'];
        }
        if(isset($post['pbid'])){
            $whr .= ' AND comments.tp_pbid='.$post['pbid'];
        }
        if(isset($post['publish'])){
            $whr .= " AND comments.tp_publish='".$post['publish']."'";
        }
        // //pr($whr,true);
        $ids_res = $this->pager(
            "SELECT
				comments.tp_id 			AS id
			FROM
				tp_product_comments AS comments
			WHERE
				comments.`tp_delete` = 0   AND comments.tp_pid = 0 $whr
        ",isset($post['limit'])?$post['limit']:10,isset($post['page'])?$post['page']:1,true);
        $res['count'] = $ids_res->count;
        $res['total'] = $ids_res->total;
        if($ids_res->count > 0){
            $ids = [];
            foreach ($ids_res->result as $v) {
                $ids[] = " comments.tp_path  LIKE '%_".$v['id']."_%' ";
            }
            $whr = " ";
            if(isset($post['publish'])){
                $whr = " AND comments.tp_publish='".$post['publish']."'";
            }
            $ids_concat = implode('OR',$ids);
            $res['data']=$this->select(
                "SELECT
                    comments.tp_id 			AS id,
                    comments.`tp_mid` 		AS mid,
                    comments.`tp_publish` 	AS publish,
                    comments.`tp_statistic_like` 	AS statistic_like,
                    comments.`tp_pbid` 		AS pbid,
                    comments.`tp_path` 		AS `path`,
                    comments.`tp_pid` 		AS pid,
                    comments.`tp_rate` 		AS rate,
                    comments.`tp_uid` 		AS `uid`,
                    comments.`tp_text` 		AS `text`,
                    CASE
                        comments.`tp_mid`
                        WHEN 0 THEN
                        users.tp_pic
                        ELSE members.tp_pic
                    END 					AS `img`,
                    CASE
                        comments.`tp_mid`
                        WHEN 0 THEN
                        CONCAT( users.tp_name, ' ', users.tp_family )
                        ELSE
                        CONCAT( members.tp_name, ' ', members.tp_family )
                    END 					AS `full_name`,
                    comments.tp_date 		AS createAt
                FROM
                    tp_product_comments AS comments
                    LEFT JOIN `tp_members` AS members ON ( members.tp_id = comments.tp_mid )
                    LEFT JOIN `tp_users` AS users ON ( users.tp_id = comments.tp_uid)
                WHERE
                    comments.`tp_delete` = 0 AND ( {$ids_concat} ) $whr
            ")->result;
        }
        // //pr($data,true);

        return $res;
    }



    public function edit ($id,$data)
    {
        return $this->update("
            UPDATE
                tp_product_comments
            SET
                `tp_publish`    = 'publish',
                `tp_text`    = '{$data['text']}',
                `tp_update`     = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }
    public function like ($cid)
    {
        $mid = $_SESSION['mid'];
        $res = $this->select(
            "SELECT
                tp_id  AS id
            FROM
                `tp_product_comment_likes`
            WHERE
                tp_delete = 0 AND tp_mid={$mid} AND   tp_cid={$cid}
        ");
        if($res->count == 0){
            // //pr($res,true);
            $res = $this->update(
                "UPDATE
                    `tp_product_comments`
                SET
                    `tp_statistic_like` =  tp_statistic_like + 1,
                    tp_update = now()
                WHERE
                    tp_id={$cid}
            ");
            $this->insert("
                INSERT INTO
                    `tp_product_comment_likes`
                SET
                    tp_mid = {$mid},
                    tp_cid = {$cid},
                    tp_date   = now()
            ");
            return true;
        }
        return false;
    }
    public function set_publish ($id,$data)
    {
        return $this->update("
            UPDATE
                tp_product_comments
            SET
                `tp_publish`    = '{$data['publish']}',
                `tp_update`     = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }


    public function add_comment ($post)
    {
        return $this->insert("
            INSERT INTO
                tp_product_comments
            SET
                tp_cid        = '{$post['cid']}',
                tp_title      = '{$post['title']}',
                tp_pic        = '{$post['pic']}',
                tp_desc       = '{$post['desc']}',
                tp_tags       = '{$post['tags']}',
                tp_short_desc = '{$post['short_desc']}',
                tp_uid        = '{$_SESSION['uid']}',
                tp_date       = now()
        ");
    }

    public function delete_comment ($id)
    {
        return $this->update("
            UPDATE
            tp_product_comments
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}' OR `tp_pid` = '{$id}'
        ");
    }
}
