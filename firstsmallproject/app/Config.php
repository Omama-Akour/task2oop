<?php

declare(strict_types = 1);

namespace App;

/**
 * @property-read ?array $db
 */
class Config
{
    protected array $config = [];

    public function __construct(array $env)
    {
        $this->config = [   
            'db' => [
                'host'     => $env['DB_HOST'],
                'user'     => $env['DB_USER'],
                'pass'     => $env['DB_PASS'],
                'database' => $env['DB_DATABASE'],
                'driver'   => $env['DB_DRIVER'] ?? 'mysql',
            ],
        ];
    }

    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }
}
$env=[
    'DB_HOST'=>'localhost',
    'DB_USER'=>'root',
    'DB_PASS'=>'',
    'DB_DATABASE'=>'task2',
    'DB_DRIVER'=>'mysql',
];

// Instantiate the Config class
$config = new \App\Config($env);
// Access the database configuration
$dbConfig = $config->db;

// Print the database configuration
print_r($dbConfig);