<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_role extends DB
{


    public function get_list_all ()
    {
        return $this->select(
            "SELECT
                role.tp_id AS id,
                role.tp_name AS name
            FROM
                `tp_role` as   role
            WHERE
                role.`tp_delete` = '0' 
            ORDER BY  role.tp_id DESC
        ");
    }

    public function get_list ($params)
    {
        return $this->pager(
            "SELECT
                role.tp_id AS id,
                role.tp_name AS name,
                role.tp_desc AS `desc`,
                role.tp_date AS createAt
            FROM
                `tp_role` as   role
            WHERE
                role.`tp_delete` = '0'
            ORDER BY  role.tp_id DESC
        " ,$params['limit'],$params['page'],true);
    }

    public function role_permissions ($rid)
    {
        return $this->select(
            "SELECT
                role_permissions.tp_reid AS reid,
                role_permissions.tp_perm AS perm,
                role.tp_name AS role_name,
                resource.tp_title AS resource_title
            FROM
                `tp_role_permissions` AS role_permissions
                INNER JOIN tp_role AS role ON(role.tp_id = role_permissions.tp_rid AND role.tp_delete=0)
                INNER JOIN tp_resource AS resource ON(resource.tp_id = role_permissions.tp_reid AND resource.tp_delete=0)

            WHERE
                role_permissions.`tp_delete` = '0'  AND role_permissions.`tp_rid` =  '{$rid}'
        ");
    }
    public function user_permissions ($rid)
    {
        return $this->select(
            "SELECT
                role_permissions.tp_reid AS reid,
                role_permissions.tp_perm AS perm,
                role.tp_name AS role_name,
                resource.tp_title AS resource_title
            FROM
                `tp_role_permissions` AS role_permissions
                INNER JOIN tp_role AS role ON(role.tp_id = role_permissions.tp_rid AND role.tp_delete=0 AND role.tp_id =  '{$rid}')
                INNER JOIN tp_resource AS resource ON(resource.tp_id = role_permissions.tp_reid AND resource.tp_delete=0)

            WHERE
                role_permissions.`tp_delete` = '0'
        ");
    }
    public function resource_list ()
    {
        return $this->select(
            "SELECT
                tp_id AS id,
                tp_title AS title
            FROM
                `tp_resource`

            WHERE
                `tp_delete` = '0'
        ");
    } 
    public function update_role ($id,$data)
    {
        return $this->update(
            "UPDATE
                `tp_role`
            SET
                `tp_name` = '{$data['name']}',
                `tp_desc` = '{$data['desc']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }
    public function delete_role ($id)
    {
        return $this->update("
            UPDATE
                `tp_role`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }
    public function add_role ($post)
    {
        return $this->insert("
            INSERT INTO
                `tp_role`
            SET
                tp_name   = '{$post['name']}',
                tp_desc     = '{$post['desc']}',
                tp_uid      = '{$_SESSION['uid']}',
                tp_date     = now()
        ");
    }
    public function add_role_permissions ($post)
    {
        $rows = [] ;
        foreach ($post['data'] as  $value) {
            $rows[] = "({$value['reid']},{$value['rid']},{$value['perm']},{$_SESSION['uid']},now())";
        }
        $values = implode(',',$rows);

        $this->update("
            UPDATE
                `tp_role_permissions`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_rid` = '{$post['rid']}'
        ");
        return $this->insert(
            "INSERT INTO
                `tp_role_permissions`
                (tp_reid,tp_rid,tp_perm,tp_uid,tp_date)
            VALUES
                {$values};
        ");
    }
}
