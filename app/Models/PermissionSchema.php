<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;

class PermissionSchema extends DeclareSchema
{
    public function schema()
    {
        $this->column('id')
            ->integer()
            ->primary()
            ->autoIncrement();

        $this->column('tenant_id')
            ->integer()
            ->notNull();

        $this->column('name')
            ->varchar(32)
            ->notNull();

        $this->column('created_at')
            ->timestamp()
            ->default(['current_timestamp']);

        $this->column('updated_at')
            ->timestamp()
            ->onUpdate(['current_timestamp'])
            ->default(['current_timestamp']);
        ;
    }
}