<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;

class RoleAssociationSchema extends DeclareSchema
{
    public function schema()
    {
        $this->column('id')
            ->integer()
            ->primary()
            ->autoIncrement();

        $this->column('role_id')
            ->integer()
            ->notNull();

        $this->column('user_id')
            ->integer()
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