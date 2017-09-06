<?php

namespace Moonwalker\Core;

use Moonwalker\Models\Permission;
use Moonwalker\Models\PermissionAssociationCollection;
use Moonwalker\Models\Role;
use Moonwalker\Models\RoleAssociation;

class PermissionManager
{
    private $userId;
    private $tenantId;

    public function __construct ($userId, $tenantId)
    {
        $this->userId = $userId;
        $this->tenantId = $tenantId;
    }

    public static function with ($userId, $tenantId = 1)
    {
        return new static($userId, $tenantId);
    }

    public function verify ($permissionName, $target = null)
    {
        /*
         * Pass 1, individual verification.
         * Failing this, we'll try to look it up via roles.
         */

        $init = new PermissionAssociationCollection();

        $init->join(new Permission(), 'INNER', 'p')
            ->on('m.permission_id', [ 'p.id' ]);

        $init->where()
            ->equal('m.user_id', $this->userId)
            ->equal('p.name', $permissionName);

        if ($target != null)
            $init->where()->in('m.target', [ $target, '*' ]);

        if ($init->first())
            return true;
        else
        {
            /*
             * Pass 2, role verification
             * Failing this, we have no further recourse.
             */
            $init = new PermissionAssociationCollection();

            $init->join(new Permission(), 'INNER', 'p')
                ->on('m.permission_id', [ 'p.id' ]);

            $init->join(new Role(), 'INNER', 'r')
                ->on('m.role_id', [ 'r.id' ]);

            $init->join(new RoleAssociation(), 'INNER', 'ra')
                ->on('r.id', [ 'ra.role_id' ]);

            $init->where()
                ->equal('ra.user_id', $this->userId)
                ->equal('r.tenant_id', $this->tenantId)
                ->equal('p.name', $permissionName);

            if ($target != null)
                $init->where()->in('m.target', [ $target, '*' ]);

            return $init->toSql();

            if ($init->first())
                return true;
        }

        return false;
    }

}