<?php

namespace Moonwalker\Seeders;

use Maghead\Runtime\BaseSeed;
use Moonwalker\Models\Permission;
use Moonwalker\Models\PermissionAssociation;
use Moonwalker\Models\Role;
use Moonwalker\Models\RoleAssociation;
use Moonwalker\Models\User;

class DefaultSeed extends BaseSeed
{
    public static function seed ()
    {
        $permissions = [ 'users.create', 'users.edit', 'users.view', 'users.delete' ];
        $permissionObjects = [];

        foreach ($permissions as $permission)
        {
            $permissionObjects[] = Permission::createAndLoad([
                'tenant_id' => 1,
                'name' => $permission
            ]);;
        }

        $roles = [ 'Administrator', 'User' ];
        $roleObj = [];

        foreach ($roles as $role)
        {
            $roleObj[] = Role::create([
                'tenant_id' => 1,
                'name' => $role,
                'config' => 110
            ]);
        }

        for ($i = 1; $i <= 100; $i++)
        {
            $user = User::createAndLoad([
                'first_name' => 'Test',
                'last_name' => 'User',
                'tenant_id' => 1,
                'email' => 'moonwalker-api@levarion.com',
                'config' => 111,
                'status' => 'ACTIVATED'
            ]);

            foreach ($permissionObjects as $permissionObject)
            {
                PermissionAssociation::create ([
                    'permission_id' => $permissionObject->id,
                    'user_id' => $user->id,
                    'target' => '*'
                ]);
            }

            foreach ($roleObj as $item)
            {
                RoleAssociation::create([
                    'role_id' => $item->id,
                    'user_id' => $user->id
                ]);
            }
        }
    }
}