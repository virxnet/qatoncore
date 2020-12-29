<?php

namespace VirX\Qaton;

use VirX\Qaton\Database\FileDatabase;

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
                        return $db;
                    }
                    break;
            }
        }

        return false;
    }
}
