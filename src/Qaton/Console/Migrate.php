<?php

// database migration management 

/** TODO: This needs to be cleaned up  */

namespace VirX\Qaton\Console;

use VirX\Qaton\Console;
use VirX\Qaton\Db;

class Migrate extends Console
{
    public $system;
    public $config;
    public $options;
    public $migrations_db;

    public function __construct($system, array $options = [])
    {
        $this->system = $system;
        $this->config = $this->system->config;
        $this->options = $options;
        $this->setHelp(__CLASS__);

        Console::output();
        Console::label(__CLASS__);
        Console::output();

        $this->initMigrations();
    }

    public function install()
    {
        switch ($this->config['APP_DATABASE_TYPE']) {
            case 'FileDatabase':
                $active_migrations = $this->getActiveMigrations();
                $migrations = $this->getAllMigrations();
                foreach ($migrations as $migration) {
                    require_once($migration);
                    $class = $this->getMigrationClassName(pathinfo($migration)['filename']);
                    $obj = new $class($this->config);
                    $migrated = false;
                    foreach ($active_migrations as $active_migration) {
                        if ($active_migration['migration'] == $class) {
                            $migrated = true;
                        }
                    }
                    if ($migrated === true) {
                        Console::outputWarn('Migration Already Installed ' . $class);
                    } else {
                        Console::outputNotice('Migrating ' . $migration);
                        if ($obj->up() !== false) {
                            $this->migrations_db->table('active')->insert([
                                ['migration' => $class]
                            ]);
                            $this->migrations_db->table('log')->insert([
                                [
                                    'migration' => $class,
                                    'action' => 'up'
                                ],
                            ]);
                            Console::outputSuccess('Migrated ' . $class);
                        } else {
                            Console::outputError('Migration Failed ' . $class);
                        }
                    }
                }
                break;
        }

        Console::outputSuccess('Migration Complete');
        self::output();
    }

    public function revert()
    {
        if (!isset($this->options['steps'])) {
            $this->options['steps'] = 1;
        }
        switch ($this->config['APP_DATABASE_TYPE']) {
            case 'FileDatabase':
                $active_migrations = $this->getActiveMigrations();
                $migrations = array_reverse($this->getAllMigrations());
                foreach ($migrations as $migration) {
                    require_once($migration);
                    $class = $this->getMigrationClassName(pathinfo($migration)['filename']);
                    $obj = new $class($this->config);
                    $migrated = false;
                    foreach ($active_migrations as $active_migration) {
                        if ($active_migration['migration'] == $class) {
                            $migrated = true;
                        }
                    }
                    if ($migrated === true) {
                        if ($this->options['steps'] < 1) {
                            break;
                        }
                        Console::outputNotice('Reverting Migration ' . $migration);
                        if ($obj->down() !== false) {
                            $this->migrations_db->table('active')
                                                ->where('migration', $class)
                                                ->purge();
                            $this->migrations_db->table('log')->insert([
                                [
                                    'migration' => $class,
                                    'action' => 'down'
                                ],
                            ]);
                            Console::outputSuccess('Migration Reverted ' . $class);
                        } else {
                            Console::outputError('Revert Failed ' . $class);
                        }
                        $this->options['steps']--;
                    }
                }
                break;
        }

        Console::outputSuccess('Revert Complete');
        self::output();
    }

    public function list()
    {
        switch ($this->config['APP_DATABASE_TYPE']) {
            case 'FileDatabase':
                $migrations = $this->getAllMigrations();
                $active_migrations = $this->getActiveMigrations();
                foreach ($migrations as $migration) {
                    $class = $this->getMigrationClassName(pathinfo($migration)['filename']);
                    $migrated = false;
                    foreach ($active_migrations as $active_migration) {
                        if ($active_migration['migration'] == $class) {
                            $migrated = true;
                        }
                    }
                    $this->printMigrationDetails($migration, $migrated);
                }
                break;
        }
        self::output();
    }

    private function getMigrationClassName(string $migration)
    {
        switch ($this->config['APP_DATABASE_TYPE']) {
            case 'FileDatabase':
                $migration = array_map('ucfirst', explode('_', $migration));
                return 'App\\Database\\' . $this->config['APP_DATABASE_TYPE']
                        . '\\Migrations\\' . $this->config['APP_DEFAULT_MIGRATION_CLASS']
                        . implode('', $migration);
        }
    }

    private function getAllMigrations()
    {
        switch ($this->config['APP_DATABASE_TYPE']) {
            case 'FileDatabase':
                return glob($this->config['APP_PATHS']['FILEDATABASE_MIGRATIONS']
                                . '*' . parent::PHP_EXT);
        }
    }

    private function getActiveMigrations()
    {
        switch ($this->config['APP_DATABASE_TYPE']) {
            case 'FileDatabase':
                $active_migrations = $this->migrations_db->table('active')->get();
                return $active_migrations;
        }
    }

    private function initMigrations()
    {
        switch ($this->config['APP_DATABASE_TYPE']) {
            case 'FileDatabase':
                $config = $this->config;
                $config['APP_DATABASE']['NAME'] = $this->config['APP_DATABASE']['MIGRATIONS'];
                $this->migrations_db = Db::init($config);
                $this->migrations_db->table('log')->create([
                    'migration' => ['type' => 'string'],
                    'action' => ['type' => 'string'],
                    'stamp' => ['type' => 'timestamp']
                ]);
                $this->migrations_db->table('active')->create([
                    'migration' => ['type' => 'string']
                ]);
                break;
        }
    }

    private function printMigrationDetails(string $migration, bool $migrated)
    {
        if ($migrated === true) {
            self::output(self::setColor('green') . " \xE2\x9C\x94 [OK] " . pathinfo($migration)['filename'] . " : ", 0, 0);
        } else {
            self::output(self::setColor('red') . " \xE2\x9C\x8C [NA] " . pathinfo($migration)['filename'] . " : ", 0, 0);
        }
        self::output(self::setColor('white')
                            . str_replace('// ', '', array_slice(file($migration), 2, 3)[0]), 0, 0);
    }

    public function __destruct()
    {
        Console::setColor('reset');
    }
}
