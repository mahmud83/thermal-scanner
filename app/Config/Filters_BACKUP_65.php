<?php namespace Config;

use App\Filters\ResourceFilter;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;

class Filters extends BaseConfig
{
<<<<<<< HEAD
    // Makes reading things below nicer,
    // and simpler to change out script that's used.
    public $aliases = [
        'csrf' => CSRF::class,
        'toolbar' => DebugToolbar::class,
        'honeypot' => Honeypot::class,
    ];

    // Always applied before every request
    public $globals = [
        'before' => [],
        'after' => [
            'toolbar',
        ],
    ];
=======
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
		'csrf'     => CSRF::class,
		'toolbar'  => DebugToolbar::class,
		'honeypot' => Honeypot::class,
        'resource' => ResourceFilter::class,
	];

	// Always applied before every request
	public $globals = [
		'before' => [],
		'after'  => [
			'toolbar',
		],
	];
>>>>>>> f04f387... Update features

    // Works on all of a particular HTTP method
    // (GET, POST, etc) as BEFORE filters only
    //     like: 'post' => ['CSRF', 'throttle'],
    public $methods = [];

<<<<<<< HEAD
    // List filter aliases and any before/after uri patterns
    // that they should run on, like:
    //    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
    public $filters = [];
=======
	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	public $filters = [
        'resource' => [
            'before' => [
                'rest/users',
            ],
        ],
    ];
>>>>>>> f04f387... Update features
}
