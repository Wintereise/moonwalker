<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;
use Magsql\Raw;

class PermissionAssociationSchema extends DeclareSchema
{
    public function schema()
    {
        $this->column('id')
            ->integer()
            ->primary()
            ->autoIncrement();

        $this->column('permission_id')
            ->integer()
            ->notNull();

        $this->column('role_id')
            ->integer()
            ->null();

        $this->column('user_id')
            ->integer()
            ->null();

        $this->column('target')
            ->text()
            ->notNull();

        $this->column('created_at')
            ->timestamp()
            ->isa('DateTime')
            ->default(new Raw('CURRENT_TIMESTAMP'));

        $this->column('updated_at')
            ->timestamp()
            ->isa('DateTime')
            ->default(new Raw('CURRENT_TIMESTAMP'))
            ->onUpdate(new Raw('CURRENT_TIMESTAMP'));

        $this->belongsTo('permission',  'Moonwalker\Models\PermissionSchema', 'id', 'permission_id');
        $this->belongsTo('user',  'Moonwalker\Models\UserSchema', 'id', 'user_id');
        $this->belongsTo('role',  'Moonwalker\Models\RoleSchema', 'id', 'role_id');
    }
}