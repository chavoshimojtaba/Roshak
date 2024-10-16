<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_product extends DB
{
	/* A sort array. */
    private $sort = [
        'type'      => 'product.tp_type ',
        'full_name' => 'member.tp_family ',
        'statistics_downloads'  => 'product.tp_statistics_downloads ',
        'createAt'  => 'product.tp_date ',
        'attribute'  => 'attribute.tp_grp ',
        'attribute_title'  => 'attribute.tp_title ',
        'attribute_createAt'  => 'attribute.tp_date ',
        'category'  => 'category.tp_title ',
        'title'     => 'product.tp_title '
    ];
    
    public function add_product ($data)
    { 
        return $this->insert(
            "INSERT INTO
                `tp_product`
            SET
                tp_cid        = {$data['cid']},
                tp_cat_path   = '{$data['cat_path']}', 
                tp_seo_title  = '{$data['seo_title']}', 
                tp_pic        = '{$data['pic']}',
                tp_slug       = '{$data['slug']}',
                tp_address      = '{$data['address']}',
                tp_address_json = '{$data['address_json']}',
                tp_address_lat  = '{$data['address_lat']}',
                tp_lid          = '{$data['lid']}',
                tp_address_lng  = '{$data['address_lng']}', 
                tp_desc       = '{$data['desc']}', 
                tp_serial     = '{$data['serial']}',
                tp_meta       = '{$data['meta']}',
                tp_title      = '{$data['title']}',
                tp_follow     = '{$data['follow']}',
                tp_index      = '{$data['index']}',
                tp_mid        = {$data['mid']},
                tp_date       = now()
        ");
    }

    public function get_rate ($pid)
    {
        $res= $this->select(
            "SELECT
                COUNT(rates.`tp_rate`) AS cnt,
                FORMAT(AVG(rates.`tp_rate`),1) AS rate
            FROM
                tp_product_rates AS rates
            WHERE
                rates.`tp_delete` = 0 AND rates.`tp_pid`= {$pid}  
        ");
        if($res->count>0 && $res->result[0]['cnt'] > 0){
            return $res->result[0];
        }
        return ['rate'=> '0','cnt'=>0];
    }

    public function q_type($params)
    {
        $types = [
            'attribute_title' => " AND attribute.tp_title LIKE '%{$params['q']}%' ",
            'full_name' => " AND member.tp_family  LIKE '%{$params['q']}%' OR member.tp_name  LIKE '%{$params['q']}%'",
            'title'     => " AND product.tp_title LIKE '%{$params['q']}%' "
        ];
        return $types[$params['q_type']];
    }

    public function get_reports_count ($params=[])
    {

        return $this->select(
            "SELECT
               COUNT(product.tp_id) AS cnt
            FROM
                `tp_product_reports` AS product
            WHERE
                product.`tp_delete` = 0
        " )->result[0]['cnt'];
    }

    public function get_print_request_count ($params=[])
    {

        return $this->select(
            "SELECT
               COUNT(print_request.tp_id) AS cnt
            FROM
                `tp_product_print_request` AS print_request
            WHERE
                print_request.`tp_delete` = 0
        " )->result[0]['cnt'];
    }

    public function report_reply ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_product_reports`
            SET
                `tp_reply`  = '{$data['reply']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function get_sitemap_list ($cid=0)
    {
        $where = '';
 
        if( $cid > 0 ){
            $where =" AND category.`tp_id` = {$cid}";
        } 

        return $this->select(
            "SELECT
                product.tp_id       AS `id`, 
                DATE(product.`tp_update`)   AS `update`, 
                product.`tp_slug`   AS `slug`, 
                DATE(product.`tp_date`)      AS `createAt`
            FROM
                `tp_product`    AS product  INNER JOIN
                `tp_category`   AS category ON(category.tp_id = product.tp_cid $where ) INNER JOIN
                `tp_members`    AS member  ON(member.tp_id =product.tp_mid)
            WHERE
                product.`tp_delete` = 0  AND product.tp_status = 'accept'
        " );
    }
    
    public function get_list ($params=[])
    {
        $where = '';

        if( $params['sort_by'] == 'category' ){
            $params['sort_by'] = 'cid';
        }
        list($q,$order) = create_query_filters($params,[
            'product'=>['statistic_downloads','title',  'createAt','cid'],
            'member'=>[ 'full_name'], 
        ]);   
        if(isset($params['cid'])  && $params['cid'] > 0 ){
            $where =" AND (category.`tp_id` = {$params['cid']} OR category.`tp_pid` = {$params['cid']})";
        }

        if(isset($params['mid'])  && $params['mid'] > 0 ){
            $q.=" AND  product.`tp_mid` = {$params['mid']} ";
        }

        if(isset($params['cat_path'])  && $params['cat_path'] !== '' ){
            $q .=" AND product.tp_cat_path  LIKE '%\_{$params['cat_path']}\_%'" ;
        }

        if(isset($params['ids'])  && $params['ids'] !== '' ){
            $q .=" AND product.tp_id  IN ({$params['ids']})" ;
        }
 
        return $this->pager(
            "SELECT
                product.tp_id       AS `id`,
                product.`tp_mid`    AS `mid`,
                product.`tp_cid`    AS `cid`,
                product.`tp_title`  AS `title`, 
                product.`tp_slug`   AS `slug`, 
                product.`tp_serial` AS `serial`,
                product.`tp_pic`    AS `img`, 
                category.`tp_slug`    AS `cat_slug`,
                category.`tp_title`    AS `category`,
                location_content.`tp_title`    AS `city`,
                product.`tp_date`      AS `createAt`
            FROM
                `tp_product`    AS product  INNER JOIN
                `tp_category`   AS category ON(category.tp_id = product.tp_cid $where )   INNER JOIN
                `tp_location_content`   AS location_content ON(location_content.tp_id = product.tp_lid  ) 
            WHERE
                product.`tp_delete` = 0 $q  $order
        " ,isset($params['limit'])?$params['limit']:10,isset($params['page'])?$params['page']:1,true);
    }

    public function get_list_sitemap ($params)
    {
         
        return $this->pager(
            "SELECT 
                product.tp_id       AS `id`,
                DATE(product.`tp_update`)   AS `update`, 
                product.`tp_slug`   AS `slug`, 
                product.`tp_pic`    AS `pic`,
                DATE(product.`tp_date`)      AS `createAt`
            FROM
                `tp_product`    AS product  
            WHERE
                product.`tp_delete` = 0  and product.`tp_status` = 'accept'
        " ,
            ($params['limit'])?$params['limit']:10,
            ($params['page'])?$params['page']:1
        );
    }

    public function reports_list ($params=[])
    {
        $order = 'reports.tp_id DESC';


        return $this->pager(
            "SELECT
                reports.tp_id          AS `id`,
                reports.tp_pid       AS `pid`,
                reports.tp_subject   AS `subject`,
                reports.tp_title     AS `title`,
                reports.tp_desc      AS `desc`,
                reports.tp_reply     AS `reply`,
                product.`tp_slug`     AS `slug`,
                product.`tp_slug`         AS `slug`,
                product.`tp_title`     AS `product`,
                product.`tp_pic`       AS `img`,
                CONCAT(
                    member.tp_name,
                        ' ',
                    member.tp_family
                )                   AS `full_name`,
                reports.`tp_date`      AS `createAt`
            FROM
                `tp_product_reports`   AS reports INNER JOIN
                `tp_product`    AS product  ON(reports.tp_pid = product.tp_id  ) INNER JOIN
                `tp_members`    AS member  ON(reports.tp_pid = member.tp_id  )
            WHERE
                product.`tp_delete` = 0
            ORDER BY $order
        " ,isset($params['limit'])?$params['limit']:10,isset($params['page'])?$params['page']:1,true);
    }

    public function print_request_list ($params=[])
    {
        $order = 'print_request.tp_id DESC';


        return $this->pager(
            "SELECT
                print_request.tp_mid       AS `mid`,
                print_request.tp_id        AS `id`,
                print_request.tp_type      AS `type`,
                print_request.tp_pid       AS `pid`,
                print_request.tp_title     AS `title`,
                print_request.tp_desc      AS `desc`,
                print_request.tp_reply     AS `reply`,
                product.`tp_slug`         AS `slug`,
                product.`tp_title`         AS `product`,
                product.`tp_pic`           AS `img`,
                CONCAT(
                    member.tp_name,
                        ' ',
                    member.tp_family
                )                   AS `full_name`,
                print_request.`tp_date`      AS `createAt`
            FROM
                `tp_product_print_request`  AS print_request INNER JOIN
                `tp_product`        AS product  ON(print_request.tp_pid = product.tp_id  ) INNER JOIN
                `tp_members`        AS member  ON(print_request.tp_pid = member.tp_id  )
            WHERE
                print_request.`tp_delete` = 0
            ORDER BY $order
        " ,isset($params['limit'])?$params['limit']:10,isset($params['page'])?$params['page']:1,true);
    }

	public function get_product_detail ($id,$by_slug=false)
    {
        $whr = ' product.tp_id = '.$id;
        if($by_slug){
            $whr = " product.tp_slug LIKE '".$id."'";
        }
        
        return $this->select(
            "SELECT
                product.tp_id       AS `id`,
                product.`tp_pic`    AS `img`, 
                 
                product.`tp_update`    AS `update_date`, 
                product.`tp_address`    AS `address`, 
                product.`tp_address_json`    AS `address_json`, 
                product.`tp_address_lat`    AS `address_lat`, 
                product.`tp_cid`    AS `cid`, 
                product.`tp_lid`    AS `lid`, 
                product.`tp_address_lng`    AS `address_lng`, 
                product.`tp_seo_title`    AS `seo_title`,
                product.`tp_mid`    AS `mid`,
                product.`tp_serial` AS `serial`,
                product.`tp_follow` AS `follow`, 
                product.`tp_index`  AS `index`,
                product.`tp_statistic_view`  AS `statistic_view`, 
                product.`tp_title`  AS `title`, 
                product.`tp_slug`   AS `slug`, 
                product.`tp_meta`   AS `meta`, 
                product.`tp_desc`   AS `desc`, 
                cat.tp_cat_path        AS `cat_path`,
                cat.tp_title        AS `category`, 
                product.tp_date     AS createAt
            FROM
                `tp_product`  AS product  INNER JOIN
                `tp_category` AS cat      ON(cat.tp_id = product.tp_cid )  
             WHERE
                product.`tp_delete` = 0 AND $whr
            ORDER BY product.tp_id DESC
        ");
    }

	public function get_product_file ($by_slug )
    {
        return $this->select(
            "SELECT
                 
            FROM
                `tp_product`  AS product  INNER JOIN
                `tp_category` AS cat      ON(cat.tp_id = product.tp_cid ) INNER JOIN
                `tp_members`  AS members  ON(members.tp_id =product.tp_mid)INNER JOIN
                `tp_designers`  AS designers  ON(designers.tp_mid =members.tp_id)
            WHERE
                product.`tp_delete` = 0 AND product.tp_slug LIKE '".$by_slug."'
            ORDER BY product.tp_id DESC limit 1
        ");
    }

    public function add_view ($id)
    {
        return $this->update("
            UPDATE
                tp_product
            SET
                `tp_statistic_view`    = `tp_statistic_view` + 1 ,
                `tp_update`     = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function createProductSerial ()
    {
        $res = $this->select(
			"SELECT
				MAX(tp_serial) as `serial`
            FROM
                `tp_product`
            ORDER BY  tp_id DESC limit 1
        ")->result[0]['serial'];
		if($res > 0) return ((int)$res)+1 ;
		return 100001;
    }


    public function get_attributes_list ($params=[])
    {
        $where = '';
        $q = '';
        $order = 'attribute.tp_id DESC';

        if(isset($params['sort_by']) && isset($this->sort[$params['sort_by']])  && $params['sort_type'] != null){
            $order = $this->sort[$params['sort_by']] .$params['sort_type'];
        }

        if(isset($params['q'])  && $params['q'] != ''){
            if(!isset($params['q_type']) || $params['q_type'] != ''){
                $params['q_type'] = 'title';
            }
            $q = $this->q_type($params) ;
        }


        return $this->pager(
            "SELECT
                attribute.`tp_id`    AS `id`,
                attribute.`tp_grp`   AS `attribute_grp`,
                attribute.`tp_title` AS `attribute_title`,
                attribute.`tp_date`  AS `attribute_createAt`
            FROM
                `tp_attributes` AS attribute
            WHERE
                attribute.`tp_delete` = 0 $q
            ORDER BY $order
        " ,isset($params['limit'])?$params['limit']:10,isset($params['page'])?$params['page']:1 ,true);
    }
    public function downloads_by_category ($params=[])
    { 
        return $this->select(
            "SELECT 
                SUM(tp_statistic_downloads) downloads , 
                tp_cid cid,
                tp_cat_path cat_path 
            FROM tp_product  
            WHERE tp_delete=0 
            GROUP BY tp_cid
        ");
    }

    public function get_attribute_values ($grp=false)
    {
        $q = '';
        if( !$grp || $grp != 'all' ){
            $q =" AND attribute.`tp_grp`='$grp'";
        }
        return $this->select(
            "SELECT
                attribute.`tp_id`    AS `id`,
                attribute.`tp_grp`   AS `grp`,
                attribute.`tp_title` AS `title`
            FROM
                `tp_attributes` AS attribute
            WHERE
                attribute.`tp_delete` = 0 $q
        " );
    }

    public function add_attribute_value ($data)
    {
        return $this->insert(
            "INSERT INTO
                `tp_attributes`
            SET
                tp_grp   = '{$data['grp']}',
                tp_title = '{$data['title']}',
                tp_date  = now()
        ");
    }

    public function delete_attribute ($id)
    {
        return $this->update("
            UPDATE
                `tp_attributes`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function update_attribute ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_attributes`
            SET
                `tp_grp`    = '{$data['grp']}',
                `tp_title`  = '{$data['title']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }
    public function print_request_reply ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_product_print_request`
            SET
                `tp_reply`    = '{$data['reply']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function update_statistics ($mid,$ids)
    {
        $this->update("
            UPDATE
                `tp_product`
            SET
                `tp_statistic_downloads`  = `tp_statistic_downloads` + 1,
                `tp_update` = now()
            WHERE
                `tp_id`  IN ({$ids})
        ");
        $this->update("
            UPDATE
                `tp_members`
            SET
                `tp_statistic_downloads`  = `tp_statistic_downloads` + 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$mid}'
        ");
    }

    public function update_product ($id,$data)
    { 
        $feilds = '';
        
        if(isset($data['follow'])){
            $feilds .= "`tp_follow` = '{$data['follow']}', `tp_index` = '{$data['index']}',";
        }
        if(isset($data['pic'])){
            $feilds .= "`tp_pic` = '{$data['pic']}',";
        } 
        if(isset($data['cid'])){
            $feilds .= "`tp_cid` = '{$data['cid']}', `tp_cat_path` = '{$data['cat_path']}',";
        } 
        if(isset($data['slug'])){
            $feilds .= "`tp_slug` = '{$data['slug']}', `tp_meta` = '{$data['meta']}',`tp_seo_title` = '{$data['seo_title']}',";
        } 
        
        return $this->update("
            UPDATE
                `tp_product`
            SET
                $feilds
                `tp_title`  = '{$data['title']}', 
                tp_address      = '{$data['address']}',
                tp_address_json = '{$data['address_json']}',
                tp_address_lat  = '{$data['address_lat']}',
                tp_lid          = '{$data['lid']}',
                tp_address_lng  = '{$data['address_lng']}', 
                `tp_desc`   = '{$data['desc']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function delete_product ($id)
    {
        
     
        $res =  $this->update("
            UPDATE
                `tp_product`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
        
        return $res;
    }

    public function changeStatus ($status,$id)
    {
        return $this->update("
            UPDATE
                `tp_product`
            SET
                `tp_status` = '{$status}',
                `tp_change_status_date` = now(),
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function get_designer_statistics($id=0)
    {
        $whr = '';
        if($id>0){
            $whr = ' AND tp_mid='.$id;
        }
        return $this->select(
            "SELECT
                SUM(IF(tp_delete = 0, 1, 0)) AS `statistic_product_all`, 
                SUM(IF(tp_status = 'pend', 1, 0)) AS `statistic_product_status_pend`,
                SUM(IF(tp_status = 'reject', 1, 0)) AS `statistic_product_status_reject`
            FROM tp_product WHERE `tp_delete` = 0 $whr
        ");
    }
}
