<?php return array(
  'debug' => false,
  'database' =>
  array(
    'driver' => 'mysql',
    'host' => '103.81.85.224',
    'port' => 3306,
    'database' => 'cbh-forum',
    'username' => 'cbh-forum',
    'password' => 'tunganh2003',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => 'cyo_',
    'strict' => false,
    'engine' => 'InnoDB',
    'prefix_indexes' => true,
  ),
  'url' => 'https://cyo-flarum.net',
  'paths' =>
  array(
    'api' => 'api',
    'admin' => 'admin',
  ),
  'headers' =>
  array(
    'poweredByHeader' => true,
    'referrerPolicy' => 'same-origin',
  ),
);
