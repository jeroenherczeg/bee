<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Jeroenherczeg\Bee\Generators\MultiGenerator;

/**
 * Class BaseGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class BaseGenerator extends MultiGenerator
{
    /**
     * @return array
     */
    protected function getStubs()
    {
        return [
            'laravel/base/controller.stub'                    => 'app/Http/Controllers/Controller.php',
            'laravel/base/test.stub'                          => 'tests/TestCase.php',
            'laravel/base/user_migration.stub'                => 'database/migrations/{{timestamp}}_create_users_table.php',
            'laravel/base/user_migration_reset_password.stub' => 'database/migrations/{{timestamp}}_create_password_resets_table.php',
            'laravel/base/user_model.stub'                    => 'app/Models/Eloquent/User.php'
        ];
    }
}