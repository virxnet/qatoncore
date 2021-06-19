<?php

namespace VirX\Qaton\Database;

use VirX\Qaton\Error;
use VirX\Qaton\Request;

class FileDatabase
{

    public const CLASS_NAME         = "VirX Qaton FileDatabase";
    public const CLASS_VERSION      = "1.1.0";
    public const AUTHOR             = "Antony Shan Peiris <asp@virx.net>";
    public const WEBSITE            = "http://qaton.virx.net";

    public const SCHEMA_IDENTITY    = 'identity';
    public const SCHEMA_AUTHOR      = 'author';
    public const SCHEMA_WEBSITE     = 'website';
    public const SCHEMA_CREATED     = 'created_on';
    public const SCHEMA_UPDATED     = 'updated_on';
    public const SCHEMA_VERSION     = 'version';
    public const SCHEMA_TABLES      = 'tables';
    public const SUFFIX_HUMAN       = '_text';
    public const SCHEMA_HEADER      =   [
                                    self::SCHEMA_IDENTITY => self::CLASS_NAME,
                                    self::SCHEMA_AUTHOR => self::AUTHOR,
                                    self::SCHEMA_WEBSITE => self::WEBSITE,
                                    self::SCHEMA_VERSION => self::CLASS_VERSION,
                                    self::SCHEMA_CREATED => null,
                                    self::SCHEMA_CREATED . self::SUFFIX_HUMAN => null,
                                    self::SCHEMA_UPDATED => null,
                                    self::SCHEMA_UPDATED . self::SUFFIX_HUMAN => null,
                                    self::SCHEMA_TABLES => []
                                    ];

    public const META_TABLE         = 'table';
    public const META_INDEX         = 'index';
    public const AUTO_INCREMENT     = 'auto_increment';

    public const TEXT_EXT           = '.txt';
    public const JSON_EXT           = '.json';
    public const LOG_EXT            = '.log';
    public const TEXT_VAL           = '~txt';
    public const MASKED_VAL         = '********';
    public const DATA_DIR_SUFFIX    = '-data';
    public const WILDCARD           = '*';
    public const HASH_CONFIG        = [
                                    'RAND_START' => 1000000000000000,
                                    'RAND_END' => 9999999999999999,
                                    'USE_UNIXTIME' => true,
                                    'COST' => 12,
                                    'ALGO' => 'PASSWORD_BCRYPT'
                                    ];

    public const WHERE_OPERATORS    =   [
                                    '=',
                                    '==',
                                    '!=',
                                    '>',
                                    '<',
                                    '>=',
                                    '<=',
                                    'like'
                                    ];

    public const META_FILENAME      = 'meta' . self::JSON_EXT;
    public const SCHEMA_FILENAME    = 'schema' . self::JSON_EXT;
    public const LOG_FILENAME       = 'log' . self::LOG_EXT;

    public const COL_ID             = 'id';
    public const COL_CREATED        = 'created_on';
    public const COL_UPDATED        = 'updated_on';
    public const COL_DELETED        = 'deleted_on';

    public const TYPE_STRING        = 'string';
    public const TYPE_INTEGER       = 'integer';
    public const TYPE_INT           = 'int';
    public const TYPE_FLOAT         = 'float';
    public const TYPE_DOUBLE        = 'double';
    public const TYPE_TEXT          = 'text';
    public const TYPE_TIMESTAMP     = 'timestamp';
    public const TYPE_FOREIGN       = 'foreign';
    public const TYPE_MASKED        = 'masked';
    public const TYPE_HASHED        = 'hashed';

    public const PROP_TYPE          = 'type';
    public const PROP_KEY           = 'key';
    public const PROP_NULL          = 'null';
    public const PROP_FOREIGN       = 'foreign';
    public const PROP_DEFAULT       = 'default';
    public const PROP_UNIQUE        = 'unique';

    public const OPTION_TIMESTAMPS  = 'timestamps';

    public const PAGINATE_TABLE     = 'table';
    public const PAGINATE_KEY       = 'key';
    public const PAGINATE_LIMIT     = 'limit';
    public const PAGINATE_OFFSET    = 'offset';
    public const PAGINATE_PAGES     = 'pages';
    public const PAGINATE_COUNT     = 'count';
    public const PAGINATE_PREV      = 'prev';
    public const PAGINATE_NEXT      = 'next';
    public const PAGINATE_TOTAL     = 'total_pages';
    public const PAGINATE_CURRENT   = 'current_page';
    public const PAGINATE_BACKWARD  = 'pages_backward';
    public const PAGINATE_FORWARD   = 'pages_forward';
    public const PAGINATE_REQ_FIRST = 'request_first';
    public const PAGINATE_REQ_PREV  = 'request_prev';
    public const PAGINATE_REQ_NEXT  = 'request_next';
    public const PAGINATE_REQ_LAST  = 'request_last';
    public const PAGINATE_REQ_PAGES = 'request_pages';
    public const PAGINATE_DATA      = 'data';
    

    public const ERRORS             =   [
                                    'DB_NOT_WRITABLE' => 'database does not exist and not writable',
                                    'FILE_NOT_WRITABLE' => 'unable to write file',
                                    'DIR_NOT_CREATABLE' => 'unable to create directory',
                                    'FILE_DOES_NOT_EXIST' => 'file does not exist',
                                    'FILE_NOT_READABLE' => 'file is not readable',
                                    'JSON_CORRUPT' => 'data invalid or corrupted',
                                    'SCHEMA_CORRUPT' => 'database seems to be corrupted',
                                    'TABLE_EXISTS' => 'table already exists',
                                    'TABLE_NOT_EXISTS' => 'table does not exist',
                                    'TABLE_DATA_NOT_EXISTS' => 'table data directory does not exist',
                                    'TABLE_DATA_EXISTS' => 'table data directory already exists, you may have a corrupted schema',
                                    'TABLE_META_NOT_EXISTS' => 'table meta file does not exist',
                                    'TABLE_META_MISSING' => 'table meta data missing',
                                    'USER_REQUIRED_PROP' => 'table column property is required',
                                    'FOREIGN_PROP_MISSING_KEY' => 'table column foreign property requires a key',
                                    'FOREIGN_PROP_MISSING_TABLE_REF' => 'table column foreign property requires a foreigh table reference',
                                    'UNDEFINED_DATA_TYPE' => 'a data type has not been defined for this column, schema may be corrupt?',
                                    'INVALID_VALUE_FOR_DATA_TYPE' => 'invalid insert data value supplied for data type in column',
                                    'UNDEFINED_PROPERTY_FOR_DATA_TYPE' => 'required property is missing in schema for data type in table column',
                                    'UNKNOWN_DATA_TYPE' => 'unknown data type defined for table column',
                                    'UNKNOWN_COLUMN' => 'unknown column requested for table',
                                    'INVALID_UPDATE_DATA' => 'invalid update data',
                                    'UNSUPPORTED_WHERE_OPERATOR' => 'unsupported where clause condition operator',
                                    'NON_NUMERIC_COMPARISON' => 'where clause tryting to compare non numeric values',
                                    'REQUIRED_DATA_MISSING' => 'required column missing data, null not allowed',
                                    'UNIQUE_CONSTRAINT_VOLIATION' => 'violation of unique data constraint'
                                    ];

    public const DB_TIMESTAMP_FMT   = 'Y-m-d h:i:s';

    private $database_dir;
    private $database_schema_file;
    private $schema;
    private $table;
    private $table_schema;
    private $table_dir;
    private $row_data_file;
    private $row_data_dir;
    private $meta;
    private $meta_file;

    private $limit;
    private $offset;
    private $pages;
    private $selects = array();
    private $foreigners = array();
    private $pivot_tables = array();
    private $pivot_columns = array();
    private $where_columns = array();
    private $where_operators = array();
    private $where_values = array();
    private $masked = true;
    private $verify_unhashed_columns = array();
    private $with_hashed = false;
    private $result = array();
    private $log = array();
    private $errors = array();

    public $insert_ids = array();
    public $last_insert_id = false;

    public $debug_print_html = false;
    public $debug_print_text = false;
    public $debug_print_qaton = false;
    public $debug_print_fatals_text = true;
    public $logging = false;
    public $chmod = 777;
    public $chown_user;
    public $chown_group;
    public $human_friendly = true;
    public $auto_create_schema_tables = true;
    public $paginate_default_limit = 10;
    public $paginate_default_pages = 4;
    public $manage_from_offset_prefix = 'page';
    public $manage_from_offset_suffix = 'from';
    

    public function __construct()
    {
        //
    }

    public function load(string $database)
    {
        $this->_init();
        $this->_set_database($database);
        $this->_set_schema();
    }

    public function select(string $column)
    {
        if (is_null($column)) {
            return $this;
        }

        $this->_log(__METHOD__, $column);
        if ($column != self::WILDCARD) {
            $this->selects[] = $column;
        }
        return $this;
    }

    public function offset(int $offset = 1)
    {
        $this->_log(__METHOD__, $offset);
        $this->offset = $offset;
        return $this;
    }

    public function limit(int $limit = 0)
    {
        $this->_log(__METHOD__, $limit);
        $this->limit = $limit;
        return $this;
    }

    public function pages(int $pages)
    {
        $this->_log(__METHOD__, $pages);
        $this->pages = $pages;
        return $this;
    }

    public function unmask() 
    {
        $this->masked = false;
        return $this;
    }

    public function withHashed()
    {
        $this->with_hashed = true;
        return $this;
    }

    public function verifyHashed(string $column, string $value)
    {
        $this->verify_unhashed_columns[$column] = $value;
        return $this;
    }

    public function where(string $column, $value_or_operator, $value_with_operator = false)
    {
        if (is_null($column)) {
            return $this;
        }

        if ($value_with_operator !== false) {
            // operator mode
            if (!in_array($value_or_operator, self::WHERE_OPERATORS)) {
                $this->_error_fatal(self::ERRORS['UNSUPPORTED_WHERE_OPERATOR'], $value_or_operator);
            }
            $value = $value_with_operator;
            $operator = $value_or_operator;
        } else {
            $value = $value_or_operator;
            $operator = self::WHERE_OPERATORS[0]; // Default is index 0 for =
        }

        $this->_log(__METHOD__, ['column' => $column, 'operator' => $operator, 'value' => $value]);

        if (is_array($value)) {
            foreach ($value as $sub) {
                $this->where($column, $operator, $sub);
            }
        } else {
            $this->where_columns[] = $column;
            $this->where_operators[] = $operator;
            $this->where_values[] = $value;
        }

        return $this;
    }

    public function withForeign(string $column)
    {
        if (is_null($column)) {
            return $this;
        }
 
        $this->_log(__METHOD__, $column);
        $this->foreigners[] = $column;
        return $this;
    }

    public function allForeign()
    {
        if (!empty($this->table_schema)) {
            foreach ($this->table_schema as $column => $props) {
                if (
                    isset($props['type'])
                    && isset($props['key'])
                    && isset($props['foreign'])
                    && $props['type'] === 'foreign'
                ) {
                    $this->withForeign($column);
                }
            }
        }
        return $this;
    }

    public function getForeign()
    {
        $foreigners = [];
        if (!empty($this->table_schema)) {
            foreach ($this->table_schema as $column => $props) {
                if (
                    isset($props['type'])
                    && isset($props['key'])
                    && isset($props['foreign'])
                    && $props['type'] === 'foreign'
                ) {
                    $db = new FileDatabase();
                    $db->load($this->database_dir);
                    $foreigners[$column] = $db->table($props['foreign'])->get();
                }
            }
        }
        return $foreigners;
    }

    public function withPivot(string $table, string $column)
    {
        if (is_null($table) || is_null($column)) {
            return $this;
        }

        $this->_log(__METHOD__, ['table' => $table, 'column' => $column]);
        $this->pivot_tables[] = $table;
        $this->pivot_columns[] = $column;
        return $this;
    }


    public function explain()
    {
        return $this->table_schema;
    }

    public function first()
    {
        $rows = $this->get();
        if (isset($rows[0])) {
            return $rows[0];
        } else {
            return [];
        }
    }

    public function get()
    {

        $this->_log(__METHOD__, [
            'limit' => $this->limit,
            'offset' => $this->offset,
            'selects' => $this->selects,
            'where_columns' => $this->where_columns,
            'where_values' => $this->where_values,
            'foreigners' => $this->foreigners,
            'pivot_tables' => $this->pivot_tables,
            'pivot_columns' => $this->pivot_columns,
        ]);

        $this->meta = $this->_get_table_meta();

        if ($this->limit === false || $this->limit > $this->meta[self::AUTO_INCREMENT] || $this->limit < 1) {
            $this->limit = $this->meta[self::AUTO_INCREMENT];
        }

        if ($this->offset === false || $this->offset < 1) {
            $this->offset = 1;
        }

        $serial_index = array_keys($this->meta[self::META_INDEX], 1);
        array_unshift($serial_index, null);

        for ($i = $this->offset; $i <= ($this->offset + $this->limit) - 1; $i++) {
            
            if (isset($serial_index[$i])) {
                $this->_set_row($serial_index[$i]);
            } else {
                continue;
            }
            

            // TODO: this is inefficient and needs to be optimized, it's getting all rows even with where clauses
            if (file_exists($this->row_data_file)) {
                $item = $this->_read_json_file($this->row_data_file);
            } else {
                continue;
            }

            if (isset($item[self::COL_ID])) {
                if (isset($this->meta[self::META_INDEX][$item[self::COL_ID]])) {
                    if ($this->meta[self::META_INDEX][$item[self::COL_ID]] !== 1) {
                        continue;
                    }
                    if (!empty($this->selects)) {
                        $select_filtered = array();

                        foreach ($this->selects as $col) {
                            if (array_key_exists($col, $item)) {
                                $select_filtered[$col] = &$item[$col];
                            }
                        }
                    } else {
                        $select_filtered = $item;
                    }

                    if (!empty($this->where_columns)) {
                        $where_filtered = array();

                        foreach ($this->where_columns as $index => $col) {
                            if (array_key_exists($col, $select_filtered)) {
                                switch ($this->where_operators[$index]) {
                                    case '=':
                                    case '==':
                                        if ($this->where_values[$index] == $select_filtered[$col]) {
                                            $where_filtered = $select_filtered;
                                        }
                                        break;

                                    case '!=':
                                        if ($this->where_values[$index] != $select_filtered[$col]) {
                                            $where_filtered = $select_filtered;
                                        }
                                        break;

                                    case '>':
                                        if (!is_numeric($this->where_values[$index]) || !is_numeric($select_filtered[$col])) {
                                            $this->_error_warn(self::ERRORS['NON_NUMERIC_COMPARISON'], [$this->where_values[$index], $select_filtered[$col]]);
                                        } else {
                                            if ($this->where_values[$index] < $select_filtered[$col]) {
                                                $where_filtered = $select_filtered;
                                            }
                                        }
                                        break;

                                    case '<':
                                        if (!is_numeric($this->where_values[$index]) || !is_numeric($select_filtered[$col])) {
                                            $this->_error_warn(self::ERRORS['NON_NUMERIC_COMPARISON'], [$this->where_values[$index], $select_filtered[$col]]);
                                        } else {
                                            if ($this->where_values[$index] > $select_filtered[$col]) {
                                                $where_filtered = $select_filtered;
                                            }
                                        }
                                        break;

                                    case '>=':
                                        if (!is_numeric($this->where_values[$index]) || !is_numeric($select_filtered[$col])) {
                                            $this->_error_warn(self::ERRORS['NON_NUMERIC_COMPARISON'], [$this->where_values[$index], $select_filtered[$col]]);
                                        } else {
                                            if ($this->where_values[$index] <= $select_filtered[$col]) {
                                                $where_filtered = $select_filtered;
                                            }
                                        }
                                        break;

                                    case '<=':
                                        if (!is_numeric($this->where_values[$index]) || !is_numeric($select_filtered[$col])) {
                                            $this->_error_warn(
                                                self::ERRORS['NON_NUMERIC_COMPARISON'],
                                                [$this->where_values[$index],
                                                $select_filtered[$col]]
                                            );
                                        } else {
                                            if ($this->where_values[$index] >= $select_filtered[$col]) {
                                                $where_filtered = $select_filtered;
                                            }
                                        }
                                        break;

                                    case 'like':
                                        if (!empty(preg_grep('/^' . $this->where_values[$index] . '$/i', explode(' ', $select_filtered[$col])))) {
                                            $where_filtered = $select_filtered;
                                        }
                                        break;
                                }

                            }
                        }
                    } else {
                        $where_filtered = $select_filtered;
                    }

                    foreach ($this->table_schema as $col => $props) {
                        switch ($props[self::PROP_TYPE]) {
                            case self::TYPE_TEXT:
                                if (isset($where_filtered[$col])) {
                                    $where_filtered[$col] = $this->_read_file($this->row_data_dir
                                                                    . DIRECTORY_SEPARATOR . $col . self::TEXT_EXT);
                                }
                                break;

                            case self::TYPE_TIMESTAMP:
                                if (isset($where_filtered[$col])) {
                                    if (is_null($where_filtered[$col])) {
                                        $where_filtered[$col . self::SUFFIX_HUMAN] = null;
                                    } else {
                                        if ($this->human_friendly === true) {
                                            $where_filtered[$col . self::SUFFIX_HUMAN] = date(
                                                self::DB_TIMESTAMP_FMT,
                                                $where_filtered[$col]
                                            );
                                        }
                                    }
                                }
                                break;

                            case self::TYPE_FOREIGN:
                                if (isset($where_filtered[$col])) {
                                    if (in_array($col, $this->foreigners)) {
                                        $db = new FileDatabase();
                                        $db->load($this->database_dir);
                                        $rows = $db->table(
                                            $this->table_schema[$col][self::PROP_FOREIGN]
                                        )->where(
                                            $this->table_schema[$col][self::PROP_KEY],
                                            $where_filtered[$col]
                                        )->get();
                                        if (isset($rows[0])) {
                                            $where_filtered[$col] = $rows[0];
                                        } else {
                                            $where_filtered[$col] = [];
                                        }
                                    }
                                }
                                break;

                            case self::TYPE_MASKED:
                                if (isset($where_filtered[$col]) && $this->masked === true) {
                                    $where_filtered[$col] = self::MASKED_VAL;
                                }
                                break;

                            case self::TYPE_HASHED:
                                    if (isset($where_filtered[$col])) {
                                        if (isset($this->verify_unhashed_columns[$col])) {
                                            $where_filtered[$col] = $this->verifyEncodedHash(
                                                $this->verify_unhashed_columns[$col],
                                                $where_filtered[$col]
                                            );
                                        } else {
                                            if ($this->with_hashed === false) {
                                                $where_filtered[$col] = null;
                                            }
                                        }
                                    }
                                    break;
                        }
                    }

                    if (!empty($this->pivot_tables)) {
                        if (isset($where_filtered[self::COL_ID]) && is_integer($where_filtered[self::COL_ID])) {
                            foreach ($this->pivot_tables as $index => $pivot_table) {
                                $pivot_schema = &$this->schema[self::SCHEMA_TABLES][$pivot_table];
                                foreach ($pivot_schema as $pivot_col => $col_props) {
                                    if (isset($col_props[self::PROP_TYPE]) && $col_props[self::PROP_TYPE] == self::TYPE_FOREIGN) {
                                        if (isset($col_props[self::PROP_FOREIGN]) && $col_props[self::PROP_FOREIGN] == $this->table && isset($col_props[self::PROP_KEY])) {
                                            $primary_col = $pivot_col;
                                            $db = new FileDatabase($this->database_dir);
                                            $db->load($this->database_dir);
                                            $pivot_res = $db->table($pivot_table)
                                                            ->where($primary_col, $where_filtered[$col_props[self::PROP_KEY]])
                                                            ->get();
                                            foreach ($pivot_res as $sub_res)
                                            {
                                                if (isset($pivot_schema[$this->pivot_columns[$index]])) {
                                                    if (isset($pivot_schema[$this->pivot_columns[$index]][self::PROP_FOREIGN]) && $pivot_schema[$this->pivot_columns[$index]][self::PROP_KEY]) {
                                                        $db = new FileDatabase($this->database_dir);
                                                        $db->load($this->database_dir);
                                                        $child = $db->table($pivot_schema[$this->pivot_columns[$index]][self::PROP_FOREIGN])
                                                                    ->where($pivot_schema[$this->pivot_columns[$index]][self::PROP_KEY], $sub_res[$this->pivot_columns[$index]])
                                                                    ->get();
                                                        $where_filtered[$pivot_schema[$this->pivot_columns[$index]][self::PROP_FOREIGN]][] = $child[0];
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (empty($where_filtered)) {
                        continue;
                    }

                    $this->result[] = $where_filtered;

                }
            }

        }

        $this->_reset_state();

        return $this->result;
    }

    public function clone()
    {
        $records = $this->_get_clean_records();
        $ids = [];
        foreach ($records as $record) {
            unset($record[self::COL_ID]);
            // TODO: make it possible to detect 'unique' prop fields and suffix them with an iterator 
            //       so that cloning fields that have the unique prop are possible
            $ids[] = $this->insert($record);
        }
        return $ids;
    }

    public function insert(array $data)
    {

        $this->_log(__METHOD__, $data);

        $this->meta = $this->_get_table_meta();

        if (!array_key_exists($this->table, $this->schema[self::SCHEMA_TABLES])) {
            $this->_error_fatal(self::ERRORS['TABLE_NOT_EXISTS'], $this->schema[self::SCHEMA_TABLES]);
        }

        if ($this->_is_assoc_array($data)) {
            $id = $this->_auto_increment();
            $this->last_insert_id = false;

            foreach ($data as $col => $value) {
                if (array_key_exists($col, $this->schema[self::SCHEMA_TABLES][$this->table])) {
                    $data[$col] = $this->_process_insert_value($col, $id, $value);
                } else {
                    $this->_error(self::ERRORS['UNKNOWN_COLUMN'], ['table' => $this->table, 'column' => $col, 'value' => $value]);
                }
            }

            // Insert Rules (e.g. Not Null and Defaults)
            // TODO: This should be moved so the update method can also use it like the _process_insert_values needs to be used with updates
            foreach ($this->schema[self::SCHEMA_TABLES][$this->table] as $col => $col_props) {
                foreach ($col_props as $col_prop => $col_prop_val) {
                    switch ($col_prop) {
                        case self::PROP_NULL:
                            if ($col_prop_val == false) {
                                if (!isset($data[$col]) || $data[$col] == '' || $data[$col] === null) {
                                    if (isset($this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_DEFAULT])) {
                                        $data[$col] = $this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_DEFAULT];
                                    } else {
                                        $this->_error_fatal(self::ERRORS['REQUIRED_DATA_MISSING'], ['column' => $col]);
                                    }
                                }
                            }
                            break;
                        case self::PROP_UNIQUE:
                            if ($col_prop_val == true) {
                                $db = new FileDatabase();
                                $db->load($this->database_dir);
                                $rows = $db->table($this->table)->where($col, $data[$col])->get();
                                if (!empty($rows)) {
                                    $this->_error_fatal(self::ERRORS['UNIQUE_CONSTRAINT_VOLIATION'], ['column' => $col]);
                                }
                            }
                            break;
                    }
                }
            }

            

            // Options
            if (array_key_exists(self::COL_CREATED, $this->schema[self::SCHEMA_TABLES][$this->table])) {
                $data[self::COL_CREATED] = time();
            }
            if (array_key_exists(self::COL_UPDATED, $this->schema[self::SCHEMA_TABLES][$this->table])) {
                $data[self::COL_UPDATED] = time();
            }
            if (array_key_exists(self::COL_DELETED, $this->schema[self::SCHEMA_TABLES][$this->table])) {
                $data[self::COL_DELETED] = null;
            }

            $data['id'] = $id;

            $this->_set_row($id);
            $this->_write_json_file($this->row_data_file, $data);
            $this->last_insert_id = $id;
            $this->_update_table_meta();

            return $id;

        } else {
            foreach ($data as $sub) {
                if (is_array($sub) && !empty($sub)) {
                    $this->insert_ids[] = $this->insert($sub);
                }
            }
        }

    }


    public function drop()
    {
        $this->_log(__METHOD__, ['table' => $this->table]);

        if (array_key_exists($this->table, $this->schema[self::SCHEMA_TABLES]) && is_dir($this->table_dir)) {
            $this->_rmdir($this->table_dir);
            unset($this->schema['tables'][$this->table]);
            $this->_update_schema();
        }
    }

    public function create(array $columns, array $options = array())
    {
        $this->_log(__METHOD__, ['columns' => $columns, 'options' => $options]);

        if (array_key_exists($this->table, $this->schema[self::SCHEMA_TABLES]) && is_dir($this->table_dir)) {
            $this->_error_warn(self::ERRORS['TABLE_EXISTS'], $this->schema[self::SCHEMA_TABLES]);
            return false;
        }

        $required_table_column_properties = [
            self::PROP_TYPE
        ];

        $optional_table_column_properties = [
            self::PROP_NULL,
            self::PROP_DEFAULT,
            self::PROP_FOREIGN,
            self::PROP_KEY
        ];

        foreach ($columns as $column_name => $column) {
            foreach ($required_table_column_properties as $required_table_column_property) {
                if (!array_key_exists($required_table_column_property, $column)) {
                    $this->_error_fatal(self::ERRORS['USER_REQUIRED_PROP'], "`{$required_table_column_property}` in column `{$column_name}`");
                }
            }

            switch ($column[self::PROP_TYPE]) {
                case self::TYPE_FOREIGN:
                    if (!array_key_exists(self::PROP_KEY, $column)) {
                        $this->_error_fatal(self::ERRORS['FOREIGN_PROP_MISSING_KEY'], $column_name);
                    }
                    if (!array_key_exists(self::PROP_FOREIGN, $column)) {
                        $this->_error_fatal(self::ERRORS['FOREIGN_PROP_MISSING_TABLE_REF'], $column_name);
                    }
                    break;
            }

            foreach ($optional_table_column_properties as $optional_table_column_property)
            {
                if (!isset($column[$optional_table_column_property])) {
                    continue;
                }

                switch ($optional_table_column_property) {
                    // TODO: Process optional properties that require processing
                }
            }
        }

        foreach ($options as $option => $prop) {
            switch ($option) {
                case self::OPTION_TIMESTAMPS:
                    if ($prop === true) {
                        $columns[self::COL_CREATED] = [self::PROP_TYPE => self::TYPE_TIMESTAMP];
                        $columns[self::COL_UPDATED] = [self::PROP_TYPE => self::TYPE_TIMESTAMP];
                        $columns[self::COL_DELETED] = [self::PROP_TYPE => self::TYPE_TIMESTAMP];
                    }
                    break;
            }
        }

        $this->schema[self::SCHEMA_TABLES][$this->table] = $columns;

        $this->_update_schema();
        $this->_create_table_dir();
        $this->_create_table_meta();

        return true;

    }

    public function update(array $data)
    {

        $this->_log(__METHOD__, $data);

        $this->meta = $this->_get_table_meta();

        if ($this->_is_assoc_array($data)) {

            $records = $this->_get_clean_records(); //TODO: fix this, it seems very inefficient

            foreach ($records as $index => $record) {
                if (isset($record[self::COL_ID])) {
                    $this->_set_row($record[self::COL_ID]);
                }
                // TODO: use _process_insert_value after renaming it so there isn't duplication and
                //       the logic is consistent?
                foreach ($this->table_schema as $col => $props) {
                    if (isset($data[$col])) {
                        switch ($this->table_schema[$col]['type']) {
                            case self::TYPE_INTEGER:
                            case self::TYPE_INT:
                                $records[$index][$col] = (int)$data[$col];
                                break;

                            case self::TYPE_HASHED:
                                if (!is_null($data[$col]) && $data[$col] !== '') {
                                    $records[$index][$col] = $this->makeEncodedHash($data[$col]);
                                }
                                break;

                            case self::TYPE_FLOAT:
                            case self::TYPE_DOUBLE:
                                $records[$index][$col] = (float)$data[$col];
                                break;

                            case self::TYPE_TEXT:
                                $file = $this->row_data_dir . DIRECTORY_SEPARATOR . $col . self::TEXT_EXT;
                                $this->_write_file($file, $data[$col]);
                                break;

                            case self::TYPE_TIMESTAMP:
                                $records[$index][$col] = (int)$data[$col];
                                break;

                            // TODO: foreign intellignent type switch based on key > schema > type

                            default:
                                $records[$index][$col] = $data[$col];
                        }
                    }
                }

                if (isset($records[$index][self::SCHEMA_UPDATED])) {
                    $records[$index][self::SCHEMA_UPDATED] = time();
                }
            }

            foreach ($records as $index => $record) {
                if (isset($record[self::COL_ID])) {
                    $this->_set_row($record[self::COL_ID]);
                    $this->_write_json_file($this->row_data_file, $record);
                }
            }

            return true;

        } else {
            $this->_error_fatal(self::ERRORS['INVALID_UPDATE_DATA'], $data);
            return false;
        }

    }

    public function delete(bool $timestamps = true)
    {

        $deletes = array();
        $records = $this->_get_clean_records();

        $this->meta = $this->_get_table_meta();

        foreach ($records as $index => $record) {
            if (isset($this->meta[self::META_INDEX][$record[self::COL_ID]])) {
                $this->meta[self::META_INDEX][$record[self::COL_ID]] = 0;
                $deletes[] = $record[self::COL_ID];

                if ($timestamps === true)
                {
                    $record[self::COL_DELETED] = time();
                    $this->_set_row($record[self::COL_ID]);
                    $this->_write_json_file($this->row_data_file, $record);
                }
            }
        }

        $this->_write_json_file($this->meta_file, $this->meta);

        $this->_log(__METHOD__, $deletes);

        return $deletes;

    }

    public function purge()
    {

        $deletes = $this->delete(false);
        $this->_log(__METHOD__, $deletes);

        $this->meta = $this->_get_table_meta();

        foreach ($deletes as $purge_id) {
            $this->_set_row($purge_id);

            if (is_file($this->row_data_file)) {
                unlink($this->row_data_file);
            }
            if (realpath($this->row_data_dir)) {
                foreach (scandir($this->row_data_dir) as $item) {
                    if ($item == '.' || $item == '..') {
                        continue;
                    }
                    unlink($this->row_data_dir.DIRECTORY_SEPARATOR.$item);
                }
                rmdir($this->row_data_dir);
            }
        }

        return $deletes;

    }

    public function table_exists(string $table)
    {
        $table = $this->table($table);
        if (file_exists($table->meta_file)) {
            return true;
        } else {
            return false;
        }
    }

    public function table_is_empty(string $table)
    {
        if ($this->table_exists($table)) {
            if (empty($this->get())) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function count_rows()
    {
        return $this->count_all_rows()[1];
    }

    public function count_all_rows()
    {
        $this->meta = $this->_get_table_meta();
        if (isset($this->meta[self::META_INDEX]) 
            && is_array($this->meta[self::META_INDEX]) 
            && !empty($this->meta[self::META_INDEX])
        ) {
            return array_count_values($this->meta[self::META_INDEX]);
        } else {
            return 0;
        }
    }

    public function paginate(bool $manage_request_offset=false)
    {
        $data = [];
        $data[self::PAGINATE_TABLE] = $this->table;
        if ($manage_request_offset === true) {
            $request = new Request;
            $request_key = $this->manage_from_offset_prefix . 
                            '_' .  $this->table . 
                            '_' .  $this->manage_from_offset_suffix;
            $data[self::PAGINATE_KEY] = $request_key;
            if (isset($request->get[$request_key])) {
                $this->offset = $request->get[$request_key];
            }
        }
        if (!is_numeric($this->limit) || (is_numeric($this->limit) && $this->limit <= 0)) {
            $this->limit = $this->paginate_default_limit;
        }
        if (!is_numeric($this->offset) || (is_numeric($this->offset) && $this->offset < 0)) {
            $this->offset = 1;
        }
        if (!is_numeric($this->pages) || (is_numeric($this->pages) && $this->pages < 2)) {
            $this->pages = $this->paginate_default_pages;
        }
        $data[self::PAGINATE_LIMIT] = $this->limit;
        $data[self::PAGINATE_OFFSET] = $this->offset;
        $data[self::PAGINATE_PAGES] = $this->pages;
        $data[self::PAGINATE_COUNT] = $this->count_rows();
        $data[self::PAGINATE_PREV] = $this->offset - $this->limit;
        if ($data[self::PAGINATE_PREV] < 0) {
            $data[self::PAGINATE_PREV] = 1;
        }
        $data[self::PAGINATE_NEXT] = $this->offset + $this->limit;
        if ($data[self::PAGINATE_NEXT] > $data[self::PAGINATE_COUNT]) {
            $data[self::PAGINATE_NEXT] = $this->offset;
        }
        $data[self::PAGINATE_PAGES] = [];
        $data[self::PAGINATE_TOTAL] = (int)ceil($data[self::PAGINATE_COUNT] / $this->limit);
        $data[self::PAGINATE_CURRENT] = (int)ceil($this->offset / $this->limit);
        if ($data[self::PAGINATE_CURRENT] >= 1 && $data[self::PAGINATE_CURRENT] <= $data[self::PAGINATE_TOTAL]) {
            $data[self::PAGINATE_BACKWARD] = (int)floor($this->offset / $this->limit);
            $data[self::PAGINATE_FORWARD] = (int)floor($data[self::PAGINATE_TOTAL] - $data[self::PAGINATE_BACKWARD] - 1);
            $data[self::PAGINATE_PAGES][$data[self::PAGINATE_CURRENT]] = $this->offset - $this->limit;
            
            for (
                $i=$data[self::PAGINATE_CURRENT]-1; 
                (
                    ($i < $this->pages 
                    && $i < $data[self::PAGINATE_TOTAL])
                    || 
                    ($i < ($data[self::PAGINATE_CURRENT] + $this->pages - 1)
                    && $i < $data[self::PAGINATE_TOTAL])
                ); 
                $i++
            ) {
                $data[self::PAGINATE_PAGES][$i+1] = end($data[self::PAGINATE_PAGES]) + $this->limit;
            }
            $shift = $this->limit;
            for (
                $i=($data[self::PAGINATE_CURRENT]-1);
                (
                    ($i > 0
                    && count($data[self::PAGINATE_PAGES]) < $this->pages)
                );
                $i--
            ) {
                $data[self::PAGINATE_PAGES][$i] = $data[self::PAGINATE_PAGES][$data[self::PAGINATE_CURRENT]] - $shift;
                $shift += $this->limit;
            }
            ksort($data[self::PAGINATE_PAGES]);
        }
        if ($manage_request_offset === true) {
            $data[self::PAGINATE_REQ_FIRST] = "?{$request_key}=1";
            $data[self::PAGINATE_REQ_PREV] = "?{$request_key}=" . $data[self::PAGINATE_PREV];
            $data[self::PAGINATE_REQ_NEXT] = "?{$request_key}=" . $data[self::PAGINATE_NEXT];
            $data[self::PAGINATE_REQ_LAST] = "?{$request_key}=" . $data[self::PAGINATE_COUNT];
            $data[self::PAGINATE_REQ_PAGES] = [];
            foreach ($data[self::PAGINATE_PAGES] as $page_number => $offset) {
                $data[self::PAGINATE_REQ_PAGES][$page_number] = "?{$request_key}={$offset}";
            }
        }
        $data[self::PAGINATE_DATA] = $this->get();
        return $data;
    }

    public function table(string $table)
    {
        $this->_log(__METHOD__, $table);
        $this->result = array();
        $this->table = $table;
        $this->table_schema = &$this->schema[self::SCHEMA_TABLES][$table];
        $this->table_dir = $this->database_dir . DIRECTORY_SEPARATOR . $this->table;
        $this->meta_file = $this->table_dir . DIRECTORY_SEPARATOR . self::META_FILENAME;
        $this->insert_ids = array();
        return $this;
    }

    public function log()
    {
        return $this->log;
    }

    public function errors()
    {
        return $this->errors;
    }

    private function _set_row(int $id)
    {
        $this->row_data_dir = $this->table_dir . DIRECTORY_SEPARATOR . $id . self::DATA_DIR_SUFFIX;
        $this->row_data_file = $this->table_dir . DIRECTORY_SEPARATOR . $id . self::JSON_EXT;
    }

    private function _get_clean_records()
    {
        // TODO: This seems inefficient as it's used even on updates, see optimization possibilities
        $this->selects = false;
        $human_friendly = $this->human_friendly;
        $this->human_friendly = false;
        $records = $this->table($this->table)->withHashed()->unmask()->get(); // TODO: apply active where clauses?
        $this->human_friendly = $human_friendly;
        return $records;
    }

    private function _process_insert_value(string $col, int $id, $value)
    {
        $this->_log(__METHOD__, ['col' => $col, 'id' => $id, 'value' => $value]);

        $this->_set_row($id);

        if (!isset($this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_TYPE])) {
            $this->_error_fatal(self::ERRORS['UNDEFINED_DATA_TYPE'], $col);
        }
 
        switch ($this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_TYPE]) {
            case self::TYPE_STRING:
            case self::TYPE_MASKED:
                return (string)$value;
            break;

            case self::TYPE_HASHED:
                return $this->makeEncodedHash($value);
            break;

            case self::TYPE_INTEGER:
            case self::TYPE_INT: 
                if (!is_integer($value)) {
                    $this->_error_fatal(self::ERRORS['INVALID_VALUE_FOR_DATA_TYPE'], ['expected' => [self::TYPE_INTEGER,self::TYPE_INT], 'table' => $this->table, 'column' => $col, 'value' => $value]);
                }
                return (int)$value;
            break;

            case self::TYPE_FLOAT:
            case self::TYPE_DOUBLE:
                if (!is_float($value)) {
                    $this->_error_fatal(self::ERRORS['INVALID_VALUE_FOR_DATA_TYPE'], ['expected' => [self::TYPE_FLOAT,self::TYPE_DOUBLE], 'table' => $this->table, 'column' => $col, 'value' => $value]);
                }
                return (float)$value;
            break;

            case self::TYPE_TEXT:
                $this->_mkdir($this->row_data_dir);
                $file = $this->row_data_dir . DIRECTORY_SEPARATOR . "{$col}" . self::TEXT_EXT;
                $this->_write_file($file, $value);
                return self::TEXT_VAL;
            break;

            case self::TYPE_FOREIGN:
                if (!isset($this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_FOREIGN])) {
                    $this->_error_fatal(self::ERRORS['UNDEFINED_PROPERTY_FOR_DATA_TYPE'], ['expected' => self::PROP_FOREIGN, 'table' => $this->table, 'column' => $col, 'value' => $value, 'schema' => $this->schema[self::SCHEMA_TABLES][$this->table][$col]]);
                }
                if (!isset($this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_KEY])) {
                    $this->_error_fatal(self::ERRORS['UNDEFINED_PROPERTY_FOR_DATA_TYPE'], ['expected' => self::PROP_KEY, 'table' => $this->table, 'column' => $col, 'value' => $value, 'schema' => $this->schema[self::SCHEMA_TABLES][$this->table][$col]]);
                }

                return $value;
            break;

            case self::TYPE_TIMESTAMP:
                if (is_null($value) || $value == '') {
                    return time();
                } else {
                    try {
                        new \DateTime('@' . $value);
                    } catch (\Exception $e) {
                        $this->_error_fatal(self::ERRORS['INVALID_VALUE_FOR_DATA_TYPE'], ['expected' => [self::TYPE_TIMESTAMP], 'table' => $this->table, 'column' => $col, 'value' => $value]);
                    }
                    return $value;
                }

            default:
                $this->_error_fatal(self::ERRORS['UNKNOWN_DATA_TYPE'], ['table' => $this->table, 'column' => $col, 'value' => $value, 'schema' => $this->schema[self::SCHEMA_TABLES][$this->table][$col]]);
        }
    }

    private function _reset_state()
    {
        $this->limit = false;
        $this->offset = false;
        $this->selects = array();
        $this->where_columns = array();
        $this->where_values = array();
        $this->foreigners = array();
        $this->pivot_tables = array();
        $this->pivot_columns = array();
    }

    private function _auto_increment(bool $commit = false)
    {
        $this->meta[self::AUTO_INCREMENT] = $this->meta[self::AUTO_INCREMENT] + 1;
        $this->meta[self::META_INDEX][$this->meta[self::AUTO_INCREMENT]] = 1;
        if ($commit !== false) {
            $this->_write_json_file($this->meta_file, $this->meta);
        }
        $this->_log(__METHOD__, $this->meta[self::AUTO_INCREMENT]);
        return $this->meta[self::AUTO_INCREMENT];
    }

    private function _get_table_meta()
    {
        $this->_log(__METHOD__, $this->meta_file);
        if (!isset($this->schema[self::SCHEMA_TABLES][$this->table])) {
            if ($this->auto_create_schema_tables === true) {
                $this->_error(self::ERRORS['TABLE_NOT_EXISTS'], $this->table);
                $this->_create_table_meta();
            } else {
                $this->_error_fatal(self::ERRORS['TABLE_NOT_EXISTS'], $this->table);
            }
            
        }
        if (!file_exists($this->meta_file)) {
            if ($this->auto_create_schema_tables === true) {
                $this->_error(self::ERRORS['TABLE_META_NOT_EXISTS'], $this->meta_file);
                $this->_create_table_meta();
            } else {
                $this->_error_fatal(self::ERRORS['TABLE_NOT_EXISTS'], $this->table);
            }
        }
        $meta = $this->_read_json_file($this->meta_file);
        if ($meta === false) {
            if ($this->auto_create_schema_tables === true) {
                $this->_error(self::ERRORS['TABLE_META_MISSING'], $this->meta_file);
                $this->create($this->schema[self::SCHEMA_TABLES][$this->table]);
                $meta = $this->_read_json_file($this->meta_file);
            } else {
                $this->_error_fatal(self::ERRORS['TABLE_META_MISSING'], $this->meta_file);
            }
        }
        
        return $meta;
    }

    private function _create_table_dir()
    {
        $this->_log(__METHOD__, $this->table_dir);
        if (!is_dir($this->table_dir)) {
            $this->_mkdir($this->table_dir);
        } else {
            $this->_error_fatal(self::ERRORS['TABLE_DATA_EXISTS'], $this->table_dir);
        }
    }

    private function _create_table_meta()
    {
        $this->_log(__METHOD__, $this->meta_file);
        $meta = [
            self::META_TABLE => $this->table,
            self::AUTO_INCREMENT => 0,
        ];
        $this->_write_json_file($this->meta_file, $meta);
        return $meta;
    }

    private function _update_table_meta()
    {
        $this->_log(__METHOD__, $this->meta_file);
        $this->_write_json_file($this->meta_file, $this->meta);
    }

    private function _update_schema()
    {
        $this->_log(__METHOD__, $this->database_schema_file);
        $this->_write_database($this->schema);
    }

    private function _set_schema()
    {
        $this->_log(__METHOD__, $this->database_schema_file);
        $this->schema = $this->_read_json_file($this->database_schema_file);
        $this->_log('SCHEMA', $this->schema);

        if (!isset($this->schema[self::SCHEMA_TABLES])) {
            $this->_error_fatal(self::ERRORS['SCHEMA_CORRUPT'], $this->schema);
        }

        return true;
    }

    private function _set_database(String $database)
    {
        $this->_log(__METHOD__, $database);
        $this->database_dir = $database;
        $this->database_schema_file = $this->database_dir . DIRECTORY_SEPARATOR . self::SCHEMA_FILENAME;
        if (!is_writable(dirname($this->database_dir))) {
            $this->_error_fatal(self::ERRORS['DB_NOT_WRITABLE'], $this->database_dir);
        }
        if (!is_dir($this->database_dir)) {
            $this->_create_database($this->database_dir);
        }
        return true;
    }

    private function _create_database()
    {
        $this->_log(__METHOD__, $this->database_dir);
        if (!is_dir($this->database_dir)) {
            $this->_mkdir($this->database_dir);
        }

        if (!is_writable($this->database_dir)) {
            $this->_error_fatal(self::ERRORS['DB_NOT_WRITABLE'], $this->database_dir);
        } else {
            $this->schema = self::SCHEMA_HEADER;
            $this->schema[self::SCHEMA_CREATED] = time();
            $this->schema[self::SCHEMA_CREATED . self::SUFFIX_HUMAN] = date(self::DB_TIMESTAMP_FMT, time());
            $this->schema[self::SCHEMA_UPDATED] = time();
            $this->schema[self::SCHEMA_UPDATED . self::SUFFIX_HUMAN] = date(self::DB_TIMESTAMP_FMT, time());
            $this->_write_database($this->schema);
        }
        return true;
    }

    private function _write_database(array $data)
    {
        $this->_log(__METHOD__, ['file' => $this->database_schema_file, 'data' => $data]);
        $this->_write_json_file($this->database_schema_file, $data);
        return true;
    }

    private function makeEncodedHash(string $string, $salt = false)
    {
        if ($salt === false) {
            if (self::HASH_CONFIG['USE_UNIXTIME'] === true) {
                $time = time();
            } else {
                $time = null;
            }
            $salt = $time . uniqid(rand(
                                        self::HASH_CONFIG['RAND_START'],
                                        self::HASH_CONFIG['RAND_END']
                                    ));
        }
        $options = [
            'cost' => self::HASH_CONFIG['COST'],
        ];
        switch (self::HASH_CONFIG['ALGO']) {
            case 'PASSWORD_BCRYPT':
                $hash = password_hash($string . $salt, PASSWORD_BCRYPT, $options);
                break;
            case 'PASSWORD_ARGON2I':
                $hash = password_hash($string . $salt, PASSWORD_ARGON2I, $options);
                break;
            case 'PASSWORD_ARGON2ID':
                    $hash = password_hash($string . $salt, PASSWORD_ARGON2ID, $options);
                    break;
            default:
                $hash = password_hash($string . $salt, PASSWORD_DEFAULT, $options);
        }
        return base64_encode(json_encode([
            'hash' => $hash,
            'salt' => $salt
        ]));
    }

    private function verifyEncodedHash(string $string, string $base64_json_encoded_hash_salt_array)
    {
        if ($arr = json_decode(base64_decode($base64_json_encoded_hash_salt_array), null, 512, JSON_OBJECT_AS_ARRAY)) {
            return password_verify($string . $arr['salt'], $arr['hash']);
        }

        return false;
    }

    private function _rmdir(string $directory)
    {
        $this->_log(__METHOD__, $directory);

        if (!realpath($directory)) {
            return false;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }

        rmdir($directory);
    }

    private function _mkdir(string $directory)
    {
        $this->_log(__METHOD__, $directory);
        if (!is_dir($directory)) {
            if (!@mkdir($directory, octdec($this->chmod), true))
            {
                $this->_error_fatal(self::ERRORS['DIR_NOT_CREATABLE'], $directory);
            }
        } 
        @chmod($directory, octdec($this->chmod));
        @chown($directory, $this->chown_user);
        @chgrp($directory, $this->chown_group);
        return true;
    }

    private function _read_json_file(string $file)
    {
        $this->_log(__METHOD__, $file);
        if ($json = $this->_read_file($file))
        {
            if ($data = json_decode($json, JSON_OBJECT_AS_ARRAY))
            {
                return $data;
            }
            else
            {
                $this->_error(self::ERRORS['JSON_CORRUPT'], $file);
                return false;
            }
        }
        else
        {
            $this->_error(self::ERRORS['FILE_NOT_READABLE'], $file);
            return false;
        }
    }

    private function _write_json_file(string $file, array $data)
    {
        $this->_log(__METHOD__, ['file' => $file, 'data' => $data]);
        if (!$this->_write_file($file, json_encode($data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT))) {
            $this->_error(self::ERRORS['FILE_NOT_WRITABLE'], $file);
            return false;
        }
        return true;
    }

    private function _write_file(string $file, string $data)
    {
        $this->_log(__METHOD__, ['file' => $file, 'data' => $data]);
        if (!@file_put_contents($file, $data)) {
            $this->_error(self::ERRORS['FILE_NOT_WRITABLE'], $file);
            return false;
        }
        @chmod($file, octdec($this->chmod));
        @chown($file, $this->chown_user);
        @chgrp($file, $this->chown_group);
        return true;
    }

    private function _read_file(string $file)
    {
        $this->_log(__METHOD__, $file);
        if (!realpath($file)) {
            $this->_error(self::ERRORS['FILE_DOES_NOT_EXIST'], $file);
            return false;
        }
        if (!is_readable($file)) {
            $this->_error(self::ERRORS['FILE_NOT_READABLE'], $file);
            return false;
        }
        return file_get_contents($file);
    }

    private function _log($message = false, $data = false)
    {
        if ($this->logging === false) {
            return false;
        }

        if ($this->debug_print_qaton === true && php_sapi_name() != 'cli') {
            _vdc($data, 'FILEDATABASE @ ' . str_replace('\\', '/', $message));
        }

        if ($message) {
            $this->log[] = [
                'log' => $message,
                'data' => $data
            ];
        }
    }

    private function _error($message = false, $data = false, $severity = 'NOTICE')
    {
        if ($message) {
            $this->errors[] = [
                'severity' => $severity,
                'error' => $message,
                'data' => $data
            ];
        }
    }

    private function _error_warn($message = false, $data = false)
    {
        $this->_error($message, $data, 'WARNING');
    }

    private function _error_fatal($message = false, $data = false) {
        $this->_error($message, $data, 'FATAL ERROR');
        if ($this->debug_print_fatals_text === true) {
            throw new Error($message, ['data' => $data], 1003);
        }
        exit();
    }

    private function _is_assoc_array(array $arr) {
        if (!is_array($arr) || empty($arr)) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    private function _init()
    {
        $this->chown_user = posix_getpwuid(posix_geteuid())['name'];
        $this->chown_group = posix_getgrgid(posix_getegid())['name'];
    }

    public function __destruct()
    {
        if ($this->debug_print_html === true) {
            foreach ($this->log as $log) {
                echo "LOG::";
                print_r($log);
                echo "\n<hr>\n";
            }
            foreach ($this->errors as $error) {
                echo "ERROR::";
                print_r($error);
                echo "\n<hr>\n";
            }
        }

        if ($this->debug_print_text === true) {
            foreach ($this->log as $log) {
                echo "LOG::";
                print_r($log);
                echo "\n\n";
            }
            foreach ($this->errors as $error) {
                echo "ERROR::";
                print_r($error);
                echo "\n\n";
            }
        }
    }
}
