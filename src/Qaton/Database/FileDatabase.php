<?php

namespace VirX\Qaton\Database;

use VirX\Qaton\Error;
use VirX\Qaton\Request;

class FileDatabase
{

    public const CLASS_NAME         = "VirX Qaton FileDatabase";
    public const CLASS_VERSION      = "1.2.0";
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
    public const FILE_NAME_EXT      = '.file.data';
    public const FILE_META          = '.file.meta.json';
    public const FILE_SYM_TRACK     = '.sym_track.link';
    public const FILE_MASK          = '[FILE]';
    public const JSON_EXT           = '.json';
    public const LOG_EXT            = '.log';
    public const TEXT_VAL           = '~txt';
    public const MASKED_VAL         = '********';
    public const DATA_DIR_SUFFIX    = '-data';
    public const SEARCH_INDEX_DIR   = '_sindex';
    public const WILDCARD           = '*';
    public const HASH_CONFIG        = [
                                    'RAND_START' => 1000000000000000,
                                    'RAND_END' => 9999999999999999,
                                    'USE_UNIXTIME' => true,
                                    'COST' => 12,
                                    'ALGO' => 'PASSWORD_BCRYPT'
                                    ];
    public const SEARCH_FILTER      = '/[^ \w-]/';

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
    public const SINDEX_FILENAME    = 'keys' . self::JSON_EXT;
    public const NGET_TMP_FILENAME  = '/tmp/filedatabase_nget_';
    public const CACHE_TABLE        = 'filedatabase_cache';

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
    public const TYPE_HTML          = 'html';
    public const TYPE_MD            = 'markdown';
    public const TYPE_TIMESTAMP     = 'timestamp';
    public const TYPE_FOREIGN       = 'foreign';
    public const TYPE_MASKED        = 'masked';
    public const TYPE_HASHED        = 'hashed';
    public const TYPE_FILE          = 'file';

    public const PROP_TYPE          = 'type';
    public const PROP_KEY           = 'key';
    public const PROP_NULL          = 'null';
    public const PROP_FOREIGN       = 'foreign';
    public const PROP_DEFAULT       = 'default';
    public const PROP_UNIQUE        = 'unique';
    public const PROP_SEARCHABLE    = 'searchable';
    public const PROP_LABEL         = 'label';
    public const PROP_FILE_TYPES    = 'file_types';

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
                                    'UNIQUE_CONSTRAINT_VOLIATION' => 'violation of unique data constraint',
                                    'UNABLE_TO_UPLOAD_FILE' => 'unable to upload file',
                                    'SERIAL_INDEX_MISSING' => 'serial index is missing'
                                    ];

    public const DB_TIMESTAMP_FMT   = 'Y-m-d h:i:s';
    //public const DB_READ_BUFFER_ROWS = 100;

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
    private $search_index_dir;

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
    private $get_rows = array();
    private $serial_rows = array();
    private $order_by = false;
    private $order_desc = false;
    private $masked = true;
    private $verify_unhashed_columns = array();
    private $with_hashed = false;
    private $with_query_files = false;
    private $with_sym_files = false;
    private $with_real_files = false;
    private $with_files_meta = false;
    private $with_deleted = false;
    private $is_cacheable = false;
    private $clear_cache = false;
    private $query_signature = '';
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
    public $searchable_by_default = true;
    public $http_get_file_basepath = false;
    public $http_get_file_baseurl = false;
    public $http_get_file_table_key = 'filedatabase_resource';
    public $http_get_file_col_key = 'filedatabase_node';
    public $http_get_file_id_key = 'filedatabase_id';
    public $http_get_file_mask = 'filedatabase_mask';
    public $http_get_file_attachment_key = 'filedatabase_attachment';
    public $http_get_file_is_attachment = 0; //false
    public $http_get_file_ref_query_key = 'query';
    //public $http_get_file_ref_url_key = 'url';
    public $http_get_file_ref_meta_key = 'meta';
    public $http_get_file_ref_filename_key = 'real_file';
    public $http_get_file_ref_sym_url_key = 'url';
    public $http_get_file_ref_sym_file_key = 'file';
    public $http_get_file_ref_sym_basepath_key = 'basepath';
    public $http_get_file_ref_sym_baseurl_key = 'baseurl';
    public $http_get_file_ref_sym_basename_key = 'basename';
    public $db_read_buffer_rows = 100;

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
        $this->_update_query_signature('S', $column);

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
        $this->_update_query_signature('OF', $offset);
        
        $this->_log(__METHOD__, $offset);
        $this->offset = $offset;
        return $this;
    }

    public function limit(int $limit = 0)
    {
        $this->_update_query_signature('L', $limit);

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
        if ($value_with_operator === false) {
            $this->_update_query_signature('W', $column . $value_or_operator);
        } else {
            $this->_update_query_signature('W', $column . $value_or_operator . $value_with_operator);
        }
        
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

    public function withFiles($base_path, $is_attachment = false)
    {
        $this->withQueryFiles($is_attachment);
        $this->withRealFiles();
        $this->withSymFiles($base_path);
        $this->withFilesMeta();
        return $this;
    }

    public function withQueryFiles($is_attachment = false)
    {
        $this->with_query_files = true;
        $this->http_get_file_is_attachment = $is_attachment;
        return $this;
    }

    public function withRealFiles()
    {
        $this->with_real_files = true;
        return $this;
    }

    public function withSymFiles($base_path)
    {
        $this->with_sym_files = $base_path;
        return $this;
    }

    public function withFilesMeta()
    {
        $this->with_files_meta = true;
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

    public function cache()
    {
        $this->is_cacheable = true;
        return $this;
    }

    public function clearCache()
    {
        $this->clear_cache = true;
        return $this;
    }

    public function nget()
    {
        if ($this->build_and_save_cache($this->get_rows)) {
            return $this->get_rows;
        }
        
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

        /*
            TODO: in the new meta format, individual index keys will no longer
            be saved because it's redundant. For example, [1][2][3]..[999] is
            the same as writing 999. Then a separate approach to handling
            soft deleted and purged keys may be implemented separately and then
            this can even be used to prune and defragement the DB from time to time
            to improve perf. For example, if the range is 1-999 and 10 are purged,
            then at some point (or even during purging) all the keys may be
            updated and the index range updated to 1-989. This would however
            change the ID values for specific ID refs so it should only be done
            on data where the ID ref does not matter _OR_ ... the internal line
            id should be independent from the uinque ID key. Perhaps the internal
            line pointer should not be saved at all to save even more space and then
            aling with jsonlines each line number may be the internal pointer index.
            Needless to say, the new meta format would be far more efficient when
            it's implemented.
            /
            For example, eliminating the meta index for the Bible would save 2.11 mb
            of memory usage and improve speed significantly.
            /
            Also: the new format should separate col data into separate dirs instead of
            a single json file and use a jsonlines index to hold limited data which
            may be filtered or searched (requires some thinking)
            /
            Also: some col types such as bool, int, double, and new (word) should be
            usable in where comparisons while others will switch to another algo which
            will be slower and use a word index if text/html/md or something else for
            files using meta (perhaps by size, format, etc)
            /
            Also cache results. By remembering changes to tables based on inserts,
            and new values, queries can be cached maybe with cache() or maybe cache
            by defuault and noCache() to disable... so if no changes, then cached
            results will be returned plain (and maybe in a way that works well with
            Qatan views too. After major updates/inserts, the cache could also be
            pre-compiled/built so that it is ready at runtime... (think)
            /
            implment named/labled prepared queries so that they can easily be
            identified for caching without having to calculate a unique name based
            on clauses and query criteria. cacheKey('foo1') for example
            /
            Also look intp pack/unpack of arrays instead of json and gzip and other
            for as well as file streaming for better efficieny
        */
        $this->meta = $this->_get_table_meta();

        // TODO: implement with_deleted

        // TODO: if meta is already in a mem cache, then performance + mem usage will improve significantly

        // TODO: To prevent reading/looping large meta, see new format notes above for next version of FDB
        // eliminate on broad scope first using
        /* DISABLED because offset support
        $read_buffer_size = 0;
        foreach ($this->meta['index'] as $row_id => $row_is_alive) {
            if ($this->with_deleted === false && $row_is_alive == 0) {
                // discard deleted items
                unset($this->meta['index'][$row_id]); // for better memory efficiency
                continue;
            }
            //$this->get_rows[] = $row_id; // this will double memory usage even with &$row_id

            // process where clauses first before filtering out anything else

            $read_buffer_size++;
            if ($read_buffer_size == $this->db_read_buffer_rows) {
                $read_buffer_size = 0;
            }
        }
        */

        //_vd($this->meta);

        // This conforms with the existing FDB format but is not efficeint as it's loading everything into memory each time (it should already be in memory or read one line at a time)
        // process of elimination method // TODO: this will change on next version so entire thing does not needs to be read
        $this->serial_rows = $this->_get_table_serial_rows_by_json_files();

        $this->filter_get_where_scope();
        $this->filter_get_constraints();

        if ($this->order_desc === true && $this->order_by === false) {
            $this->filter_and_mutate_data($this->get_rows);
            return array_reverse($this->get_rows);
        }

        if ($this->order_by !== false) {
            //$col_arr = array_column($this->get_rows, $this->order_by);
            $col_arr = [];
            foreach ($this->get_rows as $row) {
                if (isset($row[$this->order_by])) {
                    $col_arr[$row[$this->order_by]] = $row;
                }
            }
            $this->filter_and_mutate_data($col_arr);
            if ($this->order_desc === true) {
                return array_reverse($col_arr);
            }
            return $col_arr;
        }

        $this->filter_and_mutate_data($this->get_rows);
        return $this->get_rows;
    }

    private function _get_cache_sig()
    {
        $wc = implode('_', $this->where_columns);
        $wo = implode('_', $this->where_operators);
        $wv = implode('_', $this->where_values);
        $sl = implode('_', $this->selects);
        $fr = implode('_', $this->foreigners);
        $pc = implode('_', $this->pivot_columns);
        $pt = implode('_', $this->pivot_tables);
        $od = (int)$this->order_desc;
        if (is_string($this->order_by)) {
            $ob = $this->order_by;
        } else {
            $ob = (int)$this->order_by;
        }
        
        $sig = urlencode("_{$this->table}_{$sl}_{$wc}_{$wo}_{$wv}_{$this->limit}_{$this->offset}_{$od}_{$ob}_{$fr}_{$pc}_{$pt}");

        return $sig;
    }

    private function build_and_save_cache(&$rows, $skip_create = true)
    {
        if ($this->is_cacheable === false) {
            return false;
        }
        
        //$sig = $this->_get_cache_sig();
        $sig = &$this->query_signature;
        
        $db = new FileDatabase();
        $db->load($this->database_dir);
        if (!$db->table_exists(self::CACHE_TABLE)) {
            if ($skip_create === false) {
                $db->table(self::CACHE_TABLE)->create([
                    'sig' => [
                        'type' => 'string',
                        'null' => false,
                        'default' => 'error'
                    ],
                    'data' => [
                        'type' => 'text',
                        'null' => true
                    ]
                ]);
            } else {
                return false;
            }
        }
        
        $data = $db->fastTextGet(self::CACHE_TABLE, 'data', 'sig', $sig);
        if (is_null($data)) {
            $db->table(self::CACHE_TABLE)->insert([
                'sig' => $sig,
                'data' => json_encode($rows)
            ]);
        } else {
            /*
            if ($this->clear_cache === true) {
                $db->table(self::CACHE_TABLE)->drop();
            }
            */
            $rows = json_decode($data, JSON_OBJECT_AS_ARRAY);
        }

        // $rows were passed by ref, so should be updated in memory

        return true;
    }

    public function fastTextGet($table, $select_col, $where_col, $where_val)
    {
        $this->table($table);
        $this->meta = $this->_get_table_meta();
        if (!isset($this->meta['index'])) {
            return null;
        }
        foreach ($this->meta['index'] as $row => $status) {
            $this->_set_row($row);
            $data = $this->_read_json_file($this->table_dir . DIRECTORY_SEPARATOR . $row . self::JSON_EXT);
            if (isset($data[$where_col]) && $data[$where_col] == $where_val) {
                if (isset($data[$select_col])) {
                    return $this->_read_file($this->row_data_dir
                    . DIRECTORY_SEPARATOR . $select_col . self::TEXT_EXT);
                }
            }
        }
        return null;
    }

    private function filter_and_mutate_data(&$rows)
    {
        if (empty($rows)) {
            return false;
        }
        
        foreach ($rows as $row_index => $row) {
            $rows[$row_index] = $this->filter_select_cols($row);
        }

        foreach ($rows as $row_index => $row) {
            if (!isset($row[self::COL_ID]) || (isset($row[self::COL_ID]) && $row[self::COL_ID] === null)) {
                continue;
            }
            $this->_set_row($row[self::COL_ID]);
            foreach ($this->table_schema as $col => $props) {
                switch ($props[self::PROP_TYPE]) {
                    case self::TYPE_TEXT:
                    case self::TYPE_HTML:
                    case self::TYPE_MD:
                        if (isset($rows[$row_index][$col])) {
                            $rows[$row_index][$col] = $this->_read_file($this->row_data_dir
                                                            . DIRECTORY_SEPARATOR . $col . self::TEXT_EXT);
                        }
                        break;
    
                    case self::TYPE_TIMESTAMP:
                        if (isset($rows[$row_index][$col])) {
                            if (is_null($rows[$row_index][$col])) {
                                $where_filtered[$col . self::SUFFIX_HUMAN] = null;
                            } else {
                                if ($this->human_friendly === true) {
                                    $where_filtered[$col . self::SUFFIX_HUMAN] = date(
                                        self::DB_TIMESTAMP_FMT,
                                        $rows[$row_index][$col]
                                    );
                                }
                            }
                        }
                        break;
    
                    case self::TYPE_FOREIGN:
                        if (isset($rows[$row_index][$col])) {
                            if (in_array($col, $this->foreigners)) {
                                $db = new FileDatabase();
                                $db->load($this->database_dir);
                                $rows = $db->table(
                                    $this->table_schema[$col][self::PROP_FOREIGN]
                                )->where(
                                    $this->table_schema[$col][self::PROP_KEY],
                                    $rows[$row_index][$col]
                                )->get();
                                if (isset($rows[0])) {
                                    $rows[$row_index][$col] = $rows[0];
                                } else {
                                    $rows[$row_index][$col] = [];
                                }
                            }
                        }
                        break;
    
                    case self::TYPE_MASKED:
                        if (isset($rows[$row_index][$col]) && $this->masked === true) {
                            $rows[$row_index][$col] = self::MASKED_VAL;
                        }
                        break;
    
                    case self::TYPE_HASHED:
                        if (isset($rows[$row_index][$col])) {
                            if (isset($this->verify_unhashed_columns[$col])) {
                                $rows[$row_index][$col] = $this->verifyEncodedHash(
                                    $this->verify_unhashed_columns[$col],
                                    $rows[$row_index][$col]
                                );
                            } else {
                                if ($this->with_hashed === false) {
                                    $rows[$row_index][$col] = null;
                                }
                            }
                        }
                        break;
    
                    case self::TYPE_FILE:
                        if (
                            isset($rows[$row_index][$col])
                            && ( $this->with_query_files === true
                                || $this->with_sym_files !== false
                                || $this->with_real_files === true
                                || $this->with_files_meta === true
                            )
                        ) {
                            $rows[$row_index][$col] = $this->_get_uploaded_file_ref($this->table, $col, $row[self::COL_ID]);
                        } elseif (isset($rows[$row_index][$col])) {
                            $where_filtered[$col] = self::FILE_MASK;
                        }
                        break;
                }
            }

            // Test if passing entire $rows insread of pass by ref is better since it's an array value
            $this->populate_foreign_data($row);
        }

        $this->build_and_save_cache($rows, false);
    }

    private function filter_select_cols(&$row)
    {
        foreach ($row as $col => &$val) {
            if (!in_array($col, $this->selects)) {
                unset($row[$col]);
            }
        }
        return $row;
    }

    private function populate_foreign_data(&$row)
    {
        if (!empty($this->pivot_tables)) {
            if (isset($row[self::COL_ID]) && is_integer($row[self::COL_ID])) {
                foreach ($this->pivot_tables as $index => $pivot_table) {
                    $pivot_schema = &$this->schema[self::SCHEMA_TABLES][$pivot_table];
                    foreach ($pivot_schema as $pivot_col => $col_props) {
                        if (isset($col_props[self::PROP_TYPE]) && $col_props[self::PROP_TYPE] == self::TYPE_FOREIGN) {
                            if (isset($col_props[self::PROP_FOREIGN]) && $col_props[self::PROP_FOREIGN] == $this->table && isset($col_props[self::PROP_KEY])) {
                                $primary_col = $pivot_col;
                                $db = new FileDatabase($this->database_dir);
                                $db->load($this->database_dir);
                                $pivot_res = $db->table($pivot_table)
                                                ->where($primary_col, $row[$col_props[self::PROP_KEY]])
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
                                            $row[$pivot_schema[$this->pivot_columns[$index]][self::PROP_FOREIGN]][] = $child[0];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function filter_get_where_scope()
    {
        if (empty($this->where_columns)) {
            return;
        }

        foreach ($this->serial_rows as $i => $id_json) {
            if (!$this->_set_serial_row($i)) {
                continue;
            }
            if (!file_exists($this->row_data_file)) {
                unset($this->serial_rows[$i]);
                unset($this->meta[self::META_INDEX][$i]);
                continue;
            }
            $item = $this->_read_json_file($this->row_data_file);
            if (!isset($item[self::COL_ID])) {
                unset($this->serial_rows[$i]);
                unset($this->meta[self::META_INDEX][$i]);
                continue;
            }
            foreach ($this->where_columns as $index => $col) {
                $keep = false;
                if (array_key_exists($col, $item)) {
                    switch ($this->where_operators[$index]) {
                        case '=':
                        case '==':
                            if ($this->where_values[$index] == $item[$col]) {
                                $keep = true;
                            }
                            break;

                        case '!=':
                            if ($this->where_values[$index] != $item[$col]) {
                                $keep = true;
                            }
                            break;

                        case '>':
                            if (!is_numeric($this->where_values[$index]) || !is_numeric($item[$col])) {
                                $this->_error_warn(self::ERRORS['NON_NUMERIC_COMPARISON'], [$this->where_values[$index], $item[$col]]);
                            } else {
                                if ($this->where_values[$index] < $item[$col]) {
                                    $keep = true;
                                }
                            }
                            break;

                        case '<':
                            if (!is_numeric($this->where_values[$index]) || !is_numeric($item[$col])) {
                                $this->_error_warn(self::ERRORS['NON_NUMERIC_COMPARISON'], [$this->where_values[$index], $item[$col]]);
                            } else {
                                if ($this->where_values[$index] > $item[$col]) {
                                    $keep = true;
                                }
                            }
                            break;

                        case '>=':
                            if (!is_numeric($this->where_values[$index]) || !is_numeric($item[$col])) {
                                $this->_error_warn(self::ERRORS['NON_NUMERIC_COMPARISON'], [$this->where_values[$index], $item[$col]]);
                            } else {
                                if ($this->where_values[$index] <= $item[$col]) {
                                    $keep = true;
                                }
                            }
                            break;

                        case '<=':
                            if (!is_numeric($this->where_values[$index]) || !is_numeric($item[$col])) {
                                $this->_error_warn(
                                    self::ERRORS['NON_NUMERIC_COMPARISON'],
                                    [$this->where_values[$index],
                                    $item[$col]]
                                );
                            } else {
                                if ($this->where_values[$index] >= $item[$col]) {
                                    $keep = true;
                                }
                            }
                            break;

                        case 'like':
                            if (!empty(preg_grep('/^' . $this->where_values[$index] . '$/i', explode(' ', $item[$col])))) {
                                $keep = true;
                            }
                            break;
                    }

                }
                if ($keep === false) {
                    unset($this->serial_rows[$i]);
                    unset($this->meta[self::META_INDEX][$i]);
                    continue;
                }
            }
        }
    }

    public function orderDesc()
    {
        $this->_update_query_signature('OD', 'true');
        
        $this->order_desc = true;
        return $this;
    }

    public function orderBy($col, $order)
    {
        $this->_update_query_signature('OB', $col . (string)$order);

        if ($order == 'desc') {
            $this->order_desc = true;
        }
        $this->order_by = $col;
        return $this;
    }

    private function filter_get_constraints()
    {
        $first_key = array_key_first($this->serial_rows);
        $last_key = array_key_last($this->serial_rows);
        
        // determine default 
        if ($this->limit === false || $this->limit > $last_key || $this->limit < 1) {
            $this->limit = $last_key;
        }

        if ($this->offset === false || $this->offset < 1) {
            $this->offset = $first_key;
        }

        $result_rows = 0;
        // TODO: in the new version, unique ids should be in some other optimized index so that all data does not need to be loaded to compare
        for ($i = $this->offset; $i <= (($this->offset + $this->limit) - 1); $i++) {
            if ($result_rows == $this->limit) {
                break;
            }
            if (!isset($this->meta[self::META_INDEX][$i])) {
                unset($this->serial_rows[$i]);
                continue;
            }
            $row_status = $this->meta[self::META_INDEX][$i];
            if ($this->with_deleted === false && (bool)$row_status === false) {
                // discard deleted items
                unset($this->serial_rows[$i]);
                unset($this->meta[self::META_INDEX][$i]); // for better memory efficiency
                continue;
            }
            if (!$this->_set_serial_row($i)) {
                continue;
            }
            //$this->_set_row($i);
            if (!file_exists($this->row_data_file)) {
                unset($this->serial_rows[$i]);
                unset($this->meta[self::META_INDEX][$i]);
                continue;
            }
            $item = $this->_read_json_file($this->row_data_file);
            if (!isset($item[self::COL_ID])) {
                unset($this->serial_rows[$i]);
                unset($this->meta[self::META_INDEX][$i]);
                continue;
            }
            

            $this->get_rows[$i] = $item;
            $result_rows++;
        }

        return $this->get_rows;
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

        if (isset($this->meta[self::META_INDEX])) {
            $serial_index = array_keys($this->meta[self::META_INDEX], 1);
            array_unshift($serial_index, null);
        }

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

                        ///*
                        //_vd($this->where_columns);
                        //_vd($this->where_operators);
                        //_vd($this->where_values);
                        //_vd($select_filtered);
                        //*/

                        foreach ($this->where_columns as $index => $col) {
                            //_vd("{$this->where_values[$index]} == {$select_filtered[$col]}");
                            //_vd("{$index} == {$col}");
                            //exit();
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

                    /*
                    _vd($this->where_columns);
                    _vd($this->where_operators);
                    _vd($this->where_values);

                    _vd($where_filtered);
                    exit();
                    */

                    foreach ($this->table_schema as $col => $props) {
                        switch ($props[self::PROP_TYPE]) {
                            case self::TYPE_TEXT:
                            case self::TYPE_HTML:
                            case self::TYPE_MD:
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

                            case self::TYPE_FILE:
                                if (
                                    isset($where_filtered[$col])
                                    && ( $this->with_query_files === true
                                        || $this->with_sym_files !== false
                                        || $this->with_real_files === true
                                        || $this->with_files_meta === true
                                    )
                                ) {
                                    $where_filtered[$col] = $this->_get_uploaded_file_ref($this->table, $col, $serial_index[$i]);
                                } elseif (isset($where_filtered[$col])) {
                                    $where_filtered[$col] = self::FILE_MASK;
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

        $result = $this->result; // TODO: this is a temp bug fix to prevent duplicate results when running multiple get()
        $this->table($this->table); // ... but this is not a great long term solution, find a better 
                                    // ... way to fix and do not copy the results like this to prevent memory doubling
        $this->_reset_state();

        return $result;
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

    public function alter(array $columns, array $options = array())
    {
        $this->_log(__METHOD__, ['columns' => $columns, 'options' => $options]);

        if (!isset($this->schema[self::SCHEMA_TABLES][$this->table])) {
            $this->_error_fatal(self::ERRORS['TABLE_NOT_EXISTS'], $this->table);
        }

        $current_schema = $this->schema[self::SCHEMA_TABLES][$this->table];

        unset($current_schema[self::COL_CREATED]);
        unset($current_schema[self::COL_UPDATED]);
        unset($current_schema[self::COL_DELETED]);

        $this->create($columns, $options, true);

        $new_schema = $this->schema[self::SCHEMA_TABLES][$this->table];

        $this->schema[self::SCHEMA_TABLES][$this->table] = array_merge($current_schema, $new_schema);

        $this->_update_schema();

        // TODO: improve this, will cause memory issues on large tables
        $rows = $this->select('id')->get();
        foreach ($rows as $row) {
            foreach ($columns as $column_name => $column) {
                $value = '';
                if (isset($column[self::PROP_DEFAULT])) {
                    $value = $column[self::PROP_DEFAULT];
                }
                $this->update([$column_name => $value]);
            }
        }
        
        return true;
    }

    public function create(array $columns, array $options = array(), bool $skip_create = false )
    {
        $this->_log(__METHOD__, ['columns' => $columns, 'options' => $options]);

        if ($skip_create === false) {
            if (array_key_exists($this->table, $this->schema[self::SCHEMA_TABLES]) && is_dir($this->table_dir)) {
                $this->_error_warn(self::ERRORS['TABLE_EXISTS'], $this->schema[self::SCHEMA_TABLES]);
                return false;
            }
        }
        
        $required_table_column_properties = [
            self::PROP_TYPE
        ];

        $optional_table_column_properties = [
            self::PROP_NULL,
            self::PROP_DEFAULT,
            self::PROP_FOREIGN,
            self::PROP_KEY,
            self::PROP_SEARCHABLE
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
                    case self::PROP_SEARCHABLE:
                        $this->_col_create_search_index($column_name, $column);
                        break;
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

        if ($skip_create === true) {
            return true;
        }

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

                    // TODO: improve this (redundant and messy) -- see also the same for _process_insert_value()
                    if ($this->table_schema[$col]['type'] != self::TYPE_TEXT
                        && $this->table_schema[$col]['type'] != self::TYPE_STRING
                        && $this->table_schema[$col]['type'] != self::TYPE_MD
                        && $this->table_schema[$col]['type'] != self::TYPE_HTML
                    ) {
                        $this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_SEARCHABLE] = false;
                    }

                    // TODO: improve this (redundant and messy) -- see also the same for _process_insert_value()
                    if (isset($records[$index][$col])) {
                        if (isset($this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_SEARCHABLE])) {
                            $this->_reset_search_index($col, $record[self::COL_ID], $records[$index][$col], $this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_SEARCHABLE]);
                        } else {
                            $this->_reset_search_index($col, $record[self::COL_ID], $records[$index][$col], null);
                        }
                    }

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
                            case self::TYPE_HTML:
                                $file = $this->row_data_dir . DIRECTORY_SEPARATOR . $col . self::TEXT_EXT;
                                $this->_write_file($file, $data[$col]);
                                break;

                            case self::TYPE_TIMESTAMP:
                                $records[$index][$col] = (int)$data[$col];
                                break;

                            case self::TYPE_FILE:
                                // TODO: optimize
                                if (isset($_FILES[$col]['error']) && $_FILES[$col]['error'] != 4) {
                                    $file = $this->row_data_dir . DIRECTORY_SEPARATOR . "{$col}"  . self::FILE_NAME_EXT;
                                    unlink($file);
                                    $this->_upload_file($col, $file);
                                    $meta = $this->row_data_dir . DIRECTORY_SEPARATOR . "{$col}" . self::FILE_META;
                                    unlink($meta);
                                    $this->_write_file($meta, json_encode($_FILES[$col]));
                                }
                                break;

                            // TODO: foreign intellignent type switch based on key > schema > type

                            default:
                                $records[$index][$col] = $data[$col];
                        }
                    }

                    // TODO: improve this (redundant and messy) -- see also the same for _process_insert_value()
                    if (isset($records[$index][$col])) {
                        if (isset($this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_SEARCHABLE])) {
                            $this->_update_search_index($col, $record[self::COL_ID], $records[$index][$col], $this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_SEARCHABLE]);
                        } else {
                            $this->_update_search_index($col, $record[self::COL_ID], $records[$index][$col], null);
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

    public function search(string $words, string $column_name)
    {
        $this->_set_search_index_dir($column_name);
        $words = preg_replace(self::SEARCH_FILTER, '', $words);
        $words = explode(' ', $words);
        $results = [];
        foreach ($words as $word) {
            $letters = str_split($word);
            $ls = $this->search_index_dir . DIRECTORY_SEPARATOR;
            $test = $ls . implode(DIRECTORY_SEPARATOR, $letters) 
                    . DIRECTORY_SEPARATOR 
                    . self::SINDEX_FILENAME;
            if (is_readable($test)) {
                $match = array_keys($this->_read_json_file($test));
                $results = array_merge($results, $match); // TODO: ignoring counts at the moment, add later
            }
        }

        // TODO: fix offset(), limit() and paginate() based issues
        if (empty($results)) {
            // TODO: this is somewhat of a hack, find a better way later
            //       ... but without this, it will return all results 
            $this->select(self::COL_ID)->where(self::COL_ID, -1);
        } else {
            $this->select(self::COL_ID)->where(self::COL_ID, $results);
        }
        
        return $this;
    }

    public function table(string $table)
    {
        $this->_update_query_signature('T', $table);

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

    private function _update_query_signature(string $key, $value)
    {
        $value = (string)$value;
        $this->query_signature .= ":{$key}.{$value}";
    }

    private function _set_search_index_dir($column_name)
    {
        $this->search_index_dir = $this->table_dir . DIRECTORY_SEPARATOR 
                                    . self::SEARCH_INDEX_DIR . DIRECTORY_SEPARATOR
                                    .$column_name;
    }

    private function _col_create_search_index(string $column_name, array $column)
    {
        if (($this->searchable_by_default === true && $column[self::PROP_SEARCHABLE] != false)
            || $column[self::PROP_SEARCHABLE] === true
        ) {
            $this->_set_search_index_dir($column_name);
            $this->_log(__METHOD__, $column_name);
            $this->_mkdir($this->search_index_dir);
        }
    }

    private function _get_table_serial_rows_by_json_files()
    {
        $rows = glob($this->table_dir . DIRECTORY_SEPARATOR . '[0-9]*' . self::JSON_EXT, GLOB_NOSORT);
        natsort($rows);
        $rows = array_values($rows);
        return $rows;
    }

    private function _set_serial_row($row_index)
    {
        //echo "{$row_index}<br>";
        if (isset($this->serial_rows[($row_index)])) {
            $this->row_data_file = $this->serial_rows[($row_index)];
            $row_path = pathinfo($this->row_data_file);
            $this->row_data_dir = $row_path['dirname'];
            return true;
        } //else {
        //    $this->_error_fatal(self::ERRORS['SERIAL_INDEX_MISSING'], $row_index);
        //}
        return false;
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

    private function _reset_search_index_keys(string $dir, int $id)
    {
        if (!$keys = $this->_read_json_file($dir . self::SINDEX_FILENAME)) {
            $keys = [];
        }
        if (isset($keys[$id])) {
            unset($keys[$id]);
        }
        $this->_write_json_file($dir . self::SINDEX_FILENAME, $keys);
    }

    private function _reset_search_index(string $column_name, int $id, $words, $searchable)
    {
        if (($this->searchable_by_default === true && $searchable != false)
            || $searchable === true
        ) {
            $this->_set_search_index_dir($column_name);
            $words = preg_replace(self::SEARCH_FILTER, '', $words);
            $words = explode(' ', $words);
            foreach ($words as $word) {
                $letters = str_split($word);
                $ls = $this->search_index_dir . DIRECTORY_SEPARATOR;
                $ls .= implode(DIRECTORY_SEPARATOR, $letters) . DIRECTORY_SEPARATOR;
                $this->_reset_search_index_keys($ls, $id);
            }
        }
    }

    private function _update_search_index_keys(string $dir, int $id, int $count)
    {
        if (!$keys = $this->_read_json_file($dir . self::SINDEX_FILENAME)) {
            $keys = [];
        }
        $keys[$id] = $count;
        
        $this->_write_json_file($dir . self::SINDEX_FILENAME, $keys);
    }

    private function _update_search_index(string $column_name, int $id, $words, $searchable)
    {
        if (($this->searchable_by_default === true && $searchable != false)
            || $searchable === true
        ) {
            $this->_set_search_index_dir($column_name);
            $words = preg_replace(self::SEARCH_FILTER, '', $words);
            $words = explode(' ', $words);
            $words = array_count_values($words);
            foreach ($words as $word => $count) {
                $letters = str_split($word);
                $ls = $this->search_index_dir . DIRECTORY_SEPARATOR;
                foreach($letters as $l) {
                    $ls .= $l . DIRECTORY_SEPARATOR;
                    $this->_mkdir($ls);
                }
                $this->_update_search_index_keys($ls, $id, $count);
            }
        }
    }

    private function _process_insert_value(string $col, int $id, $value)
    {
        $this->_log(__METHOD__, ['col' => $col, 'id' => $id, 'value' => $value]);

        $this->_set_row($id);

        if (!isset($this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_TYPE])) {
            $this->_error_fatal(self::ERRORS['UNDEFINED_DATA_TYPE'], $col);
        }
        
        // TODO: improve this (redundant and messy) -- see also the same for update()
        if ($this->table_schema[$col]['type'] != self::TYPE_TEXT
            && $this->table_schema[$col]['type'] != self::TYPE_STRING
            && $this->table_schema[$col]['type'] != self::TYPE_MD
            && $this->table_schema[$col]['type'] != self::TYPE_HTML
        ) {
            $this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_SEARCHABLE] = false;
        }

        // TODO: improve this (redundant and messy) - see also the same for update()
        if (isset($this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_SEARCHABLE])) {
            $this->_update_search_index($col, $id, $value, $this->schema[self::SCHEMA_TABLES][$this->table][$col][self::PROP_SEARCHABLE]);
        } else {
            $this->_update_search_index($col, $id, $value, null);
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
            case self::TYPE_HTML:
            case self::TYPE_MD:
                $this->_mkdir($this->row_data_dir);
                $file = $this->row_data_dir . DIRECTORY_SEPARATOR . "{$col}" . self::TEXT_EXT;
                $this->_write_file($file, $value);
                return self::TEXT_VAL;
            break;

            case self::TYPE_FILE:
                $this->_mkdir($this->row_data_dir);
                $this->_upload_file($col, $this->row_data_dir . DIRECTORY_SEPARATOR . "{$col}"  . self::FILE_NAME_EXT);
                $meta = $this->row_data_dir . DIRECTORY_SEPARATOR . "{$col}" . self::FILE_META;
                $this->_write_file($meta, json_encode($_FILES[$col]));
                return true;
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
        // TODO: implement memory cache in next version

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

        // TODO: test format if ['index'] exists and is valid plus other checks
        
        return $meta;
    }

    private function _create_table_dir()
    {
        $this->_log(__METHOD__, $this->table_dir);
        if (!is_dir($this->table_dir)) {
            $this->_mkdir($this->table_dir);
        } else {
            $this->_error(self::ERRORS['TABLE_DATA_EXISTS'], $this->table_dir);
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
        // TODO: implement memory cache in next version
        
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

    private function _get_uploaded_file_meta($record)
    {
        $file = realpath($record . self::FILE_META);
        if (file_exists($file)) {
            return $this->_read_json_file($file);
        }
        $this->_error(self::ERRORS['FILE_DOES_NOT_EXIST'], $file);
        return false;
    }

    private function _get_uploaded_file_ref($table, $col, $id)
    {
        $record = $this->row_data_dir . DIRECTORY_SEPARATOR . "{$col}";
        $file = realpath($record . self::FILE_NAME_EXT);
        $meta = $this->_get_uploaded_file_meta($record);
        if (!file_exists($file)) {
            $this->_error(self::ERRORS['FILE_DOES_NOT_EXIST'], $file);
            if ($this->with_query_files === true) {
                return '#';
            }
            return null;
        }
        if (isset($meta['name']) && $meta['name'] != "") {
            $file_info = pathinfo($meta['name']);
            $res = [];
            if ($this->with_real_files === true) {
                $res[$this->http_get_file_ref_filename_key] = $file;
            }
            if ($this->with_sym_files !== false) {
                $sym = $this->_get_or_create_file_sym($file, $this->with_sym_files, $table, $col, $id, $file_info['extension'], $record);
                if (is_array($sym)) {
                    $res = array_merge($res, $sym);
                } else {
                    // TODO: Error handler
                }
            }
            if ($this->with_query_files === true) {
                $ref = [
                    $this->http_get_file_table_key => $table,
                    $this->http_get_file_col_key => $col,
                    $this->http_get_file_id_key => $id,
                    $this->http_get_file_attachment_key => $this->http_get_file_is_attachment,
                    $this->http_get_file_mask => $col . '.' . $file_info['extension'],
                ];
                $res[$this->http_get_file_ref_query_key] = http_build_query($ref);
            }
            if ($this->with_files_meta === true) {
                $res[$this->http_get_file_ref_meta_key] = $meta;
            }
            return $res;
        }
        return null;
    }

    public function getFile($col, $id, $mask = null, $is_attachment = false)
    {
        $this->_set_row($id);
        $record = $this->row_data_dir . DIRECTORY_SEPARATOR . "{$col}";
        $file = $record . self::FILE_NAME_EXT;
        $meta = $this->_get_uploaded_file_meta($record);
        if (isset($meta['name']) && file_exists($file)) {
            if ($is_attachment === true) {
                header('Content-Description: File Transfer');
                if (!is_null($mask)) {
                    header('Content-Disposition: attachment; filename=' . $mask);
                } else {
                    header('Content-Disposition: attachment; filename=' . $meta['name']);
                }
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
            }
            if (isset($meta['type'])) {
                header('Content-Type: ' . $meta['type']);
            } else {
                header('Content-Type: application/octet-stream');
            }
            if (isset($meta['size'])) {
                header('Content-Length: ' . $meta['size']);
            } else {
                header('Content-Length: ' . filesize($file));
            }
            readfile($file);
            exit;
        }
        header("HTTP/1.1 404 Not Found");
        return null;
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

    private function _update_file_record_sym_tracker($record, $link)
    {
        $tracker = $record . self::FILE_SYM_TRACK;
        if (!file_exists($tracker)) {
            $this->_write_file($tracker, $link);
        }
    }

    private function _get_or_create_file_sym($file, $basepath, $table, $col, $id, $extension, $record)
    {
        $full_basepath = $this->http_get_file_basepath . DIRECTORY_SEPARATOR . $basepath;
        if (!realpath($full_basepath) || !is_dir($full_basepath)) {
            $this->_error_fatal(self::ERRORS['FILE_DOES_NOT_EXIST'], $full_basepath);
            return false;
        }
        if (!is_writable($full_basepath)) {
            $this->_error_fatal(self::ERRORS['FILE_NOT_WRITABLE'], $full_basepath);
            return false;
        }
        $path = $full_basepath . DIRECTORY_SEPARATOR . $table . DIRECTORY_SEPARATOR;
        if (!is_dir($path)) {
            $this->_mkdir($path);
        }
        $sym_filename = "{$table}-{$col}-{$id}.{$extension}";
        $sym_file = realpath($path) . DIRECTORY_SEPARATOR . $sym_filename;
        if (!file_exists($sym_file)) {
            symlink($file, $sym_file);
            @chmod($sym_file, octdec($this->chmod));
            @chown($sym_file, $this->chown_user);
            @chgrp($sym_file, $this->chown_group);
            $this->_update_file_record_sym_tracker($record, $sym_file);
        }
        return [
            $this->http_get_file_ref_sym_url_key => $this->http_get_file_baseurl . $basepath . $table . '/' . $sym_filename,
            $this->http_get_file_ref_sym_file_key => $sym_file,
            $this->http_get_file_ref_sym_baseurl_key => $this->http_get_file_baseurl . $basepath . $table . '/',
            $this->http_get_file_ref_sym_basepath_key => realpath($path),
            $this->http_get_file_ref_sym_basename_key => $sym_filename
        ];
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

    private function _upload_file(string $name, string $target_file)
    {
        $this->_log(__METHOD__, ['_FILE' => $_FILES, 'name' => $name, 'target_file' => $target_file]);
        if (
            isset($_FILES[$name])
            && isset($_FILES[$name]['tmp_name'])
            && isset($_FILES[$name]['error'])
            && isset($_FILES[$name]['size'])
            && $_FILES[$name] != ''
            && $_FILES[$name]['tmp_name'] != ''
            && $_FILES[$name]['error'] != 4
            && $_FILES[$name]['size'] != 0
        ) {
            if (move_uploaded_file($_FILES[$name]['tmp_name'], $target_file)) {
                @chmod($target_file, octdec($this->chmod));
                @chown($target_file, $this->chown_user);
                @chgrp($target_file, $this->chown_group);
                if (!file_exists($target_file)) {
                    $this->_error_fatal(self::ERRORS['UNABLE_TO_UPLOAD_FILE'], $_FILES);
                    return false;
                }
            } else {
                $this->_error_fatal(self::ERRORS['UNABLE_TO_UPLOAD_FILE'], $_FILES);
                return false;
            }
        } else {
            $this->_error(self::ERRORS['REQUIRED_DATA_MISSING'], $_FILES);
            return false;
        }
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
