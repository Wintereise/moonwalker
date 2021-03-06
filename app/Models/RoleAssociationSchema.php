<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;
use Magsql\Raw;

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
            ->isa('DateTime')
            ->default(new Raw('CURRENT_TIMESTAMP'));

        $this->column('updated_at')
            ->timestamp()
            ->isa('DateTime')
            ->default(new Raw('CURRENT_TIMESTAMP'))
            ->onUpdate(new Raw('CURRENT_TIMESTAMP'));

        $this->belongsTo('role',  'Moonwalker\Models\RoleSchema', 'id', 'role_id');
        $this->belongsTo('user',  'Moonwalker\Models\UserSchema', 'id', 'user_id');

    }
}