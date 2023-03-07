<?php

namespace VirX\Qaton;

use VirX\Qaton\Database\FileDatabase;
use VirX\Qaton\Request;

final class Db
{
    public static function init(array $config)
    {
        if (isset($config['APP_DATABASE_TYPE'])) {
            switch ($config['APP_DATABASE_TYPE']) {
                case 'FileDatabase':
                    if (isset($config['APP_DATABASE']['NAME'])) {
                        $db = new FileDatabase();
                        if (isset($config['APP_DATABASE_OPTIONS'])) {
                            foreach ($config['APP_DATABASE_OPTIONS'] as $key => $value) {
                                $db->$key = $value;
                            }
                        }
                        $db->load($config['APP_PATHS']['FILEDATABASE'] . $config['APP_DATABASE']['NAME']);
                        if (self::serveFiles($db)) {
                            exit;
                        }
                        return $db;
                    }
                    break;
            }
        }

        return false;
    }

    public static function serveFiles($db)
    {
        $request = new Request;
        if (isset($request->get[$db->http_get_file_table_key])
            && isset($request->get[$db->http_get_file_col_key])
            && isset($request->get[$db->http_get_file_id_key])
            && isset($request->get[$db->http_get_file_mask])
            && isset($request->get[$db->http_get_file_attachment_key])
        ) {
            $db->table($request->get[$db->http_get_file_table_key])
            ->getFile(
                $request->get[$db->http_get_file_col_key],
                $request->get[$db->http_get_file_id_key],
                $request->get[$db->http_get_file_mask],
                (bool)$request->get[$db->http_get_file_attachment_key]
            );
            return true;
        }
        return false;
    }
}
