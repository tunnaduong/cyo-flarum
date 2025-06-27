<?php return array(
  'debug' => false,
  'database' =>
  array(
    'driver' => 'mysql',
    'host' => 'localhost',
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
  'url' => 'https://beta.chuyenbienhoa.com',
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
