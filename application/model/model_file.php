<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_file extends DB{

    function del_file_relations ($rid,$type)
    {
        return $this->update("
            UPDATE
                `sys_file_relation`
            SET
                `tp_delete` = '1',
                `tp_update` = now()
            WHERE
                `tp_rid`  = $rid AND tp_type='$type'
        ",false);
    }

    function detail ( $id )
    {
        return $this->select("
            SELECT
                `tp_name` AS `name`,
                `tp_title` AS `title`,
                `tp_alt` AS `alt`,
                `tp_alias` AS `alias`,
                `tp_dir` AS `dir`,
                `tp_ext` AS `ext`,
                `tp_size` AS `size`,
                `tp_old_name` AS `old_name`,
                `tp_type` AS `type`
            FROM
                `sys_file`
            WHERE
                `tp_id` = '$id'
            AND `tp_delete` = '0'
        ");
    }
    public function get_resource_files ($rid,$type)
    {
        return $this->select("SELECT
                CONCAT(tp_dir,tp_name) AS `dir` ,
                `file_relation`.tp_rid AS `rid`,
                `file`.tp_ext          AS `ext` ,
                `file`.tp_id           AS `id`
            FROM
                `sys_file` AS `file`
            INNER JOIN
                `sys_file_relation` AS `file_relation`
                ON( `file`.`tp_id` = `file_relation`.`tp_fid`
                    AND `file_relation`.`tp_delete` = '0'
                    AND `file_relation`.`tp_rid`
                    IN ($rid)
                    AND `file_relation`.`tp_type` = '$type'
                    )
            WHERE
                `file`.`tp_delete`  = '0'
        ");
    }

    function del_category ($id)
    {
        return $this->update("
            UPDATE
                `sys_file_category`
            SET
                `tp_delete` = '1',
                `tp_update` = now()
            WHERE
                `tp_id`  = '$id'
        ",false);
    }

    public function update_file ($id,$data)
    {
        return $this->update("
            UPDATE
                `sys_file`
            SET
                `tp_alt` = '{$data['alt']}',
                `tp_title` = '{$data['title']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }
    public function up_category ($id,$data)
    {
        return $this->update("UPDATE
                `sys_file_category`
            SET
                `tp_alias`          = '{$data['alias']}',
                `tp_title`          = '{$data['title']}',
                `tp_thumbnail_size` = '{$data['thumbnail_size']}',
                `tp_original_size`  = '{$data['original_size']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function add_category ($data)
    {
        return $this->insert("
            INSERT INTO
                `sys_file_category`
                (tp_alias,tp_title,tp_uid,tp_thumbnail_size,tp_original_size,tp_date)
            VALUES
                ('{$data['alias']}','{$data['title']}','{$_SESSION['uid']}','{$data['thumbnail_size']}','{$data['original_size']}', now());
        ");

    }

    public function add_file_relation ($data)
    {
        $rows = [] ;
        foreach ($data['files'] as $key =>  $value) {
            $rows[] = "({$key},{$data['pbid']},'{$data['type']}',now())";
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
    public function update_file_relations ($files)
    {

        $rows = [] ;
        $rid = $files['rid'];
        $type = $files['type'];
        $this->update("
            UPDATE
                `sys_file_relation`
            SET
                `tp_delete` = '1',
                `tp_update` = now()
            WHERE
                `tp_rid`  = $rid AND `tp_delete` = '0' AND `tp_type` = '{$type}'
        ",false);
        if(count($files['files'])>0){
            foreach ($files['files'] as $key =>  $value) {
                $rows[] = "({$value},{$rid},'{$type}',now())";
            }
            $values = implode(',',$rows);
            return $this->insert("
                INSERT INTO
                    `sys_file_relation`
                    (tp_fid,tp_rid,tp_type,tp_date)
                VALUES
                    {$values}
            ");
        }
        return true;
    }

    function delete_file ($id)
    {
        return $this->update("
            UPDATE
                `sys_file`
            SET
                `tp_delete` = '1',
                `tp_update` = now()
            WHERE
                `tp_id`  = '$id'
        ",false);
    }

    function get_file_name ($id)
    {
        return $this->select("
            SELECT
                CONCAT(tp_dir,tp_name)  AS `dir`
            FROM
                `sys_file`
            WHERE
                `tp_id` = '$id'
        ");
    }
    public function get_count ($params)
    {
        $whr = '';
        if(isset($params['formats'])){
            $whr = ' AND  tp_ext IN('.$params['formats'].')';
        }
        if(isset($params['cat']) && $params['cat'] > 0){
            $whr .= ' AND tp_cid = '.$params['cat'];
        }
        if(isset($params['q']) && $params['q'] != ''){
            $whr .= " AND  tp_alias LIKE '%{$params['q']}%' " ;
        }
        return $this->total_count("sys_file","`tp_delete` = '0' ".$whr);
    }

    public function get_category_list ($cid=0)
    {
        $whr = '';
        if($cid > 0){
            $whr = ' AND tp_id = '.$cid;
        }
        return $this->select("
            SELECT
                tp_id AS id,
                tp_delete  AS pid,
                tp_thumbnail_size AS thumbnail_size,
                tp_original_size AS original_size,
                tp_alias AS alias,
                tp_title AS title,
                tp_date AS createAt
            FROM
                `sys_file_category`
            WHERE
                `tp_delete`  = '0' $whr
            ORDER BY  tp_id DESC
        ");
    }

    public function get_list ($params)
    {
        $whr = '';
        if(isset($params['formats'])){
            $whr = ' AND `file`.tp_ext IN('.$params['formats'].')';
        }
        if(isset($params['cat']) && $params['cat'] > 0){
            $whr .= ' AND `file`.tp_cid = '.$params['cat'];
        }
        if(isset($params['q']) && $params['q'] != ''){
            $whr .= " AND (`file`.tp_alias LIKE '%{$params['q']}%' OR DATE(`file`.tp_date) = '{$params['q']}')" ;
        }
        $res = $this->pager(
            "SELECT
                `file`.tp_id                    AS `id`,
                COALESCE(`file`.tp_alias, '_')  AS `alias`,
                `file`.tp_name                  AS `name`,
                `file`.tp_cid                   AS `cid`,
                `file`.tp_title                 AS `title`,
                `file`.tp_alt                   AS `alt`,
                `file`.tp_dir                   AS `dir`,
                `file`.tp_type                  AS `type`,
                `file`.tp_ext                   AS `ext`,
                `file`.tp_date                  AS `createAt`,
                `file`.tp_size                  AS `size`,
                `file`.tp_old_name              AS `old_name`,
                `category`.tp_alias             AS `cat_alias`,
                `category`.tp_title             AS `category_title`
            FROM
                `sys_file` AS `file`
            INNER JOIN
                `sys_file_category` AS `category`
                    ON(
                        category.tp_id = file.tp_cid
                        AND category.tp_delete = '0'
                    )
            WHERE
                `file`.`tp_delete`  = '0' AND `file`.`tp_is_thumbnail`  = 0 $whr
            ORDER BY  file.tp_id DESC
        " ,$params['limit'],$params['page'],
           true
        );

        return ['total' => $res->total, 'count' => $res->count, 'result' => $res->result];
    }




    public function fetch_file_img ($rid,$section)
    {
        return $this->select("
            SELECT
                `file`.tp_name,
                `file`.tp_dir,
                `file`.tp_ext,
                `file`.tp_size,
                `file`.tp_old_name
            FROM
                `sys_file` AS `file`
            INNER JOIN
                `sys_file_relation` AS `file_relation`
                    ON(
                            file.tp_id = file_relation.tp_fid
                        AND file.tp_delete = '0'
                    )
            WHERE
                `file_relation`.`tp_section` = '$section'
            AND `file_relation`.`tp_rid`     = '$rid'
            AND `file_relation`.`tp_delete`  = '0'
            LIMIT 1
        ");
    }


    function insert_file ($array)
    {
        if(isset($_SESSION['uid'])){
            $uid = $_SESSION['uid'];
        }else if(isset($_SESSION['mid'])){
            $uid = $_SESSION['mid'];
        }else{
            $uid = 0;
        }

        if(!isset($array['is_thumbnail'])){
            $array['is_thumbnail'] = 0;
        }

        return $this->insert(
            "INSERT INTO
                `sys_file`
            SET
                `tp_uid`          = ".$uid.",
                `tp_name`         = '".@$array['name']."',
                `tp_dir`          = '".@$array['dir']."',
                `tp_ext`          = '".@$array['ext']."',
                `tp_alt`          = '".@$array['alt']."',
                `tp_title`        = '".@$array['title']."',
                `tp_size`         = '".@$array['size']."',
                `tp_is_thumbnail` = '".@$array['is_thumbnail']."',
                `tp_old_name`     = '".@$array['old_name']."',
                `tp_type`         = '".@$array['type']."',
                `tp_alias`        = '".@$array['alias']."',
                `tp_cid`          = '".@$array['cid']."',
                `tp_quality`      = '0',
                `tp_date`         = now()
        ");
    }



/*     function insert_file_relation ($fid,$rid,$section,$type)
    {

        return $this->insert("
            INSERT INTO
                `sys_file_relation`
            SET
                `tp_fid`     = '$fid',
                `tp_rid`     = '$rid',
                `tp_section` = '$section',
                `tp_type`    = '$type',
                `tp_date`    = now()
        ");
    } */



    function fetch_files($record_id,$section,$type ='')
    {
        $where = "";

        if ( $type != '' )
        {
            $where = "AND sys_file_relation.tp_type = '".$type."'";
        }

        return $this->select("
            SELECT
                `sys_file`.`tp_id`          AS `file_id`,
                `sys_file`.`tp_old_name`    AS `old_name`,
                `sys_file`.`tp_dir`         AS `dir`,
                `sys_file`.`tp_name`        AS `name`,
                `sys_file`.`tp_size`        AS `size`,
                `sys_file`.`tp_ext`         AS `ext`,
                `sys_file_relation`.`tp_id` AS `file_relation_id`,
                CONCAT(
                    tbl_users.tp_name,
                    ' ',
                    tbl_users.tp_family
                ) AS `creator`
            FROM
                `sys_file`
            INNER JOIN sys_file_relation ON (
                sys_file.tp_id = sys_file_relation.tp_fid
            )
            LEFT JOIN tp_users tbl_users ON (
                sys_file.tp_uid = tbl_users.tp_id
            )
            WHERE
                sys_file.tp_delete = '0'
            AND sys_file_relation.tp_delete = '0'
            AND sys_file_relation.tp_section = '$section'
            AND sys_file_relation.tp_rid = '$record_id' "
                . $where
        );
    }

    function remove_file ($id)
    {
        return $this->update("
            UPDATE
                `sys_file_relation`
            SET
                `tp_delete` = '1',
                `tp_update` = now()
            WHERE
                `tp_id`  = '$id'
        ",false);
    }

    function fetch_file_grid ($page,$limit=10,$url='manage/',$search='')
    {
        return $this->pager("
            SELECT
                `tbl_file`.`tp_id`           AS `id`,
                `tbl_file`.`tp_uid`          AS `uid`,
                `tbl_file`.`tp_name`         AS `name`,
                `tbl_file`.`tp_dir`          AS `dir`,
                `tbl_file`.`tp_ext`          AS `ext`,
                `tbl_file`.`tp_size`         AS `size`,
                `tbl_file`.`tp_old_name`     AS `old_name`,
                `tbl_file`.`tp_type`         AS `type`,
                `tbl_file`.`tp_quality`      AS `quality`,
                `tbl_relation`.`tp_rid`      AS `rid`,
                `tbl_relation`.`tp_section`  AS `section`,
                `tbl_file`.`tp_date`         AS `date`,
                tbl_file.tp_date             AS `pdate`,
                CONCAT(
                    tbl_users.tp_name,
                    ' ',
                    tbl_users.tp_family
                ) AS `creator`
            FROM
                `sys_file` AS `tbl_file`
            LEFT JOIN sys_file_relation AS tbl_relation ON (
                tbl_file.tp_id = tbl_relation.tp_fid
            )
            LEFT JOIN tp_users tbl_users ON (
                tbl_file.tp_uid = tbl_users.tp_id
            )
            WHERE
                tbl_file.tp_delete = '0'
                ".$search."
            ORDER BY tbl_file.tp_id DESC
        ",$limit,$page,$url);
    }


    function get_file_by_id ( $id )
    {
        return $this->select("
            SELECT
                `tp_name` AS `name`,
                `tp_dir` AS `dir`,
                `tp_ext` AS `ext`,
                `tp_size` AS `size`,
                `tp_old_name` AS `old_name`,
                `tp_type` AS `type`
            FROM
                `sys_file`
            WHERE
                `tp_id` = '$id'
            AND `tp_delete` = '0'
        ");
    }

    function get_file_id ($rid,$section)
    {
        return $this->select("
            SELECT
                `tp_fid` AS `fid`
            FROM
                `sys_file_relation`
            WHERE
                `tp_rid` = '$rid'
            AND `tp_section` = '$section'
            AND `tp_delete`  = '0'
        ");
    }

    function list_file ($rid,$section)
    {
        return $this->select("
            SELECT
                `sys_file`.`tp_id`           AS `fid`,
                `sys_file`.`tp_ext`          AS `ext`,
                `sys_file`.`tp_size`         AS `size`
            FROM
                `sys_file_relation`
            INNER JOIN `sys_file` ON (
                    sys_file_relation.tp_fid = sys_file.tp_id
                AND sys_file.tp_delete = '0'
            )
            WHERE
                sys_file_relation.`tp_rid`     = '$rid'
            AND sys_file_relation.`tp_section` = '$section'
            AND sys_file_relation.`tp_delete`  = '0'
        ");
    }

}
