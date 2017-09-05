<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;
use Magsql\Raw;

class RoleSchema extends DeclareSchema
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

        $this->column('config')
            ->bigint(64)
            ->notNull()
            ->default(0);

        $this->column('name')
            ->varchar(32)
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

        $this->many('associations', 'Moonwalker\Models\RoleAssociationSchema', 'role_id', 'id');

    }
}