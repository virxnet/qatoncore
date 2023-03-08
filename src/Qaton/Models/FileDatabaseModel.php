<?php

namespace VirX\Qaton\Models;

use VirX\Qaton\Db;
use VirX\Qaton\HttpHeaders;

class FileDatabaseModel
{
    protected $config;
    public $db;
    protected $table;
    protected $query = array();

    public function __construct()
    {
        $this->config = $_ENV['QATON_CONFIG'];
        $this->db = Db::init($this->config);
        $this->table();
    }

    public function table()
    {
        if (!is_null($this->table)) {
            $this->db->table($this->table);
        } else {
            HttpHeaders::setByCode(500);
        }
        return $this;
    }

    public function select(string $column)
    {
        $this->db->select($column);
        return $this;
    }

    public function offset(int $offset = 1)
    {
        $this->db->offset($offset);
        return $this;
    }

    public function limit(int $limit = 0)
    {
        $this->db->limit($limit);
        return $this;
    }

    public function unmask()
    {
        $this->db->unmask();
        return $this;
    }

    public function withHashed()
    {
        $this->db->withHashed();
        return $this;
    }

    public function verifyHashed(string $column, string $value)
    {
        $this->db->verifyHashed($column, $value);
        return $this;
    }

    public function where(string $column, $value_or_operator, $value_with_operator = false)
    {
        $this->db->where($column, $value_or_operator, $value_with_operator);
        return $this;
    }

    public function withForeign(string $column)
    {
        $this->db->withForeign($column);
        return $this;
    }

    public function allForeign()
    {
        $this->db->allForeign();
        return $this;
    }

    public function getForeign()
    {
        return $this->db->getForeign();
    }

    public function withPivot(string $table, string $column)
    {
        $this->db->withPivot($table, $column);
        return $this;
    }

    public function withFiles($base_path, $is_attachment = false)
    {
        $this->db->withFiles($base_path, $is_attachment = false);
        return $this;
    }

    public function withQueryFiles($as_attachment = false)
    {
        $this->db->withQueryFiles($as_attachment);
        return $this;
    }

    public function withRealFiles()
    {
        $this->db->withRealFiles();
        return $this;
    }

    public function withSymFiles($base_path)
    {
        $this->db->withSymFiles($base_path);
        return $this;
    }

    public function withFilesMeta()
    {
        $this->db->withFilesMeta();
        return false;
    }

    public function getFile($col, $id, $mask, $is_attachment)
    {
        $this->db->getFile($col, $id, $mask, $is_attachment);
        exit;
    }

    public function explain()
    {
        return $this->db->explain();
    }

    public function first()
    {
        return $this->db->first();
    }

    public function get()
    {
        return $this->db->get();
    }

    public function clone()
    {
        return $this->db->clone();
    }

    public function insert(array $data)
    {
        return $this->db->insert($data);
    }

    public function update(array $data)
    {
        return $this->db->update($data);
    }

    public function delete(bool $timestamps = true)
    {
        return $this->db->delete($timestamps);
    }

    public function purge()
    {
        return $this->db->purge();
    }

    public function log()
    {
        return $this->db->log();
    }

    public function errors()
    {
        return $this->db->errors();
    }

    public static function all()
    {
        $instance = new static();
        return $instance->db->get();
    }

    public function getTable()
    {
        return $this->table;
    }

    public function table_exists(String $table)
    {
        return $this->table_exists($table);
    }

    public function table_is_empty(string $table)
    {
        return $this->table_is_empty($table);
    }

    public function count_rows()
    {
        return $this->db->count_rows();
    }

    public function count_all_rows()
    {
        return $this->db->count_all_rows();
    }

    public function paginate(bool $manage_request_offset)
    {
        return $this->db->paginate($manage_request_offset);
    }

    public function pages(int $pages)
    {
        $this->db->pages($pages);
        return $this;
    }

    public function search(string $words, string $column_name)
    {
        return $this->db->search($words, $column_name);
    }
}
