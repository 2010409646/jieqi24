<?php

class domainmodel
{
    public $db;
    public $base;
    public function __construct(&$base)
    {
        $this->domainmodel($base);
    }
    public function domainmodel(&$base)
    {
        $this->base = $base;
        $this->db = $base->db;
    }
    public function add_domain($domain, $ip)
    {
        if ($domain) {
            $this->db->query('INSERT INTO ' . UC_DBTABLEPRE . 'domains SET domain=\'' . $domain . '\', ip=\'' . $ip . '\'');
        }
        return $this->db->insert_id();
    }
    public function get_total_num()
    {
        $data = $this->db->result_first('SELECT COUNT(*) FROM ' . UC_DBTABLEPRE . 'domains');
        return $data;
    }
    public function get_list($page, $ppp, $totalnum)
    {
        $start = $this->base->page_get_start($page, $ppp, $totalnum);
        $data = $this->db->fetch_all('SELECT * FROM ' . UC_DBTABLEPRE . 'domains LIMIT ' . $start . ', ' . $ppp);
        return $data;
    }
    public function delete_domain($arr)
    {
        $domainids = $this->base->implode($arr);
        $this->db->query('DELETE FROM ' . UC_DBTABLEPRE . 'domains WHERE id IN (' . $domainids . ')');
        return $this->db->affected_rows();
    }
    public function update_domain($domain, $ip, $id)
    {
        $this->db->query('UPDATE ' . UC_DBTABLEPRE . 'domains SET domain=\'' . $domain . '\', ip=\'' . $ip . '\' WHERE id=\'' . $id . '\'');
        return $this->db->affected_rows();
    }
}
!defined('IN_UC') && exit('Access Denied');