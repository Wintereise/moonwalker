<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;
use Magsql\Raw;

class UserSchema extends DeclareSchema
{
    public function schema()
    {
        $this->column('id')
            ->integer()
            ->primary()
            ->autoIncrement();

        $this->column('tenant_id')
            ->integer()
            ->default(1)
            ->notNull();

        $this->column('first_name')
            ->varchar(128)
            ->notNull();

        $this->column('last_name')
            ->varchar(128)
            ->notNull();

        $this->column('email')
            ->varchar(254)
            ->notNull();

        $this->column('phone')
            ->varchar(32)
            ->null();

        $this->column('config')
            ->bigint(64)
            ->notNull()
            ->default(0);

        $this->column('language')
            ->varchar(32)
            ->null();

        $this->column('timezone')
            ->varchar(32)
            ->null();

        /*
         * If an ENUM column is declared to permit NULL, the NULL value is a valid value for the column, and the default value is NULL.
         * If an ENUM column is declared NOT NULL, its default value is the first element of the list of permitted values.
         * From https://dev.mysql.com/doc/refman/5.7/en/enum.html
         */
        $this->column('status')
            ->enum([ 'AWAITING_ACTIVATION', 'ACTIVATED', 'DEACTIVATED'])
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

        $this->many('permissions', 'Moonwalker\Models\PermissionAssociationSchema', 'user_id', 'id');
        $this->many('roles', 'Moonwalker\Models\RoleAssociationSchema', 'user_id', 'id');
    }
}