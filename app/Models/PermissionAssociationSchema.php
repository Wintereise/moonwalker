<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;

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
            ->default(['current_timestamp']);

        $this->column('updated_at')
            ->timestamp()
            ->onUpdate(['current_timestamp'])
            ->default(['current_timestamp']);
    }
}