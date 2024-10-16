<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$grab_pattern = array(
    1 => array(
        'config' => array(
            'perm' => array('jpg', 'png', 'gif', 'jpeg'),
            'size' => array(8)
        ),
        'post_max_size'         => '8M',
        'upload_max_filesize'   => '8M',
        'memory_limit'          => '8M',
        'set_time_limit'        => 300,
        'block'                 => 'img'
    ),
    'img' => [
        'config' => [
            'perm'   => ['jpg', 'png', 'gif', 'jpeg'],
            'size'   => [8],
            'single' => true
        ],
        'post_max_size'         => '8M',
        'upload_max_filesize'   => '8M',
        'memory_limit'          => '8M',
        'set_time_limit'        => 300,
        'block'                 => 'only_img',
        'output'                => 'json'
    ],
    'img_gallery' => array(
        'config' => array(
            'perm' => array('jpg', 'png', 'gif'),
            'size' => array(8),
            'section' => 'gallery'
        ),
        'post_max_size' => '8M',
        'upload_max_filesize' => '8M',
        'memory_limit' => '8M',
        'set_time_limit'        => 300,
        'block' => 'img_gallery'
    ),
    'doc' => [
        'config' => [
            'perm'   => ['zip', 'rar', 'pdf', 'doc', 'docx'],
            'size'   => [12],
            'single' => true
        ],
        'post_max_size'         => '16M',
        'upload_max_filesize'   => '16M',
        'memory_limit'          => '16M',
        'set_time_limit'        => 600,
        'block'                 => 'file'
    ],
    'zip' => [
        'config' => [
            'perm'   => ['zip', 'rar'],
            'size'   => [8],
            'single' => true
        ],
        'post_max_size'         => '8M',
        'upload_max_filesize'   => '8M',
        'memory_limit'          => '8M',
        'set_time_limit'        => 600,
        'block'                 => 'link',
        'output'                => 'json'
    ],
    'mp3' => [
        'config' => [
            'perm'   => ['zip', 'rar', 'mp3', 'amr'],
            'size'   => [8],
            'single' => true
        ],
        'post_max_size'         => '8M',
        'upload_max_filesize'   => '8M',
        'memory_limit'          => '8M',
        'set_time_limit'        => 300,
        'block'                 => 'link',
        'output'                => 'json'
    ],
    'full' => [
        'config' => [
            'perm'   => ['jpg', 'png', 'gif', 'jpeg', 'zip', 'rar', 'pdf', 'doc', 'docx'],
            'size'   => [20]
        ],
        'post_max_size'         => '32M',
        'upload_max_filesize'   => '32M',
        'memory_limit'          => '32M',
        'set_time_limit'        => 600,
        'block'                 => 'files',
        'output'                => 'json'
    ],
    'full_size' => [
        'config' => [
            'perm'   => ['jpg', 'png', 'gif', 'jpeg', 'zip', 'rar', 'pdf', 'doc', 'docx'],
            'size'   => [20]
        ],
        'post_max_size'         => '20M',
        'upload_max_filesize'   => '20M',
        'memory_limit'          => '128M',
        'set_time_limit'        => 600,
        'block'                 => 'files',
        'output'                => 'json'
    ],
    'upload_center' => [
        'config' => [
            'perm'   => ['jpg', 'png', 'gif', 'jpeg', 'zip', 'rar', 'pdf', 'doc', 'docx'],
            'size'   => [8]
        ],
        'post_max_size'         => '8M',
        'upload_max_filesize'   => '8M',
        'memory_limit'          => '8M',
        'set_time_limit'        => 600,
        'block'                 => 'file'
    ],
    'document' => [
        'config' => [
            'perm'   => ['zip', 'rar', 'jpg', 'png', 'doc', 'docx', 'pdf'],
            'size'   => [12],
            'single' => true
        ],
        'post_max_size'         => '16M',
        'upload_max_filesize'   => '16M',
        'memory_limit'          => '64M',
        'set_time_limit'        => 600,
        'block'                 => 'link',
        'output'                => 'json'
    ]

);
