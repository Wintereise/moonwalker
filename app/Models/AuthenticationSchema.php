<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;
use Magsql\Raw;

class AuthenticationSchema extends DeclareSchema
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

        $this->column('user_id')
            ->integer()
            ->notNull();

        $this->column('config')
            ->bigint(64)
            ->notNull()
            ->default(0);

        $this->column('password')
            ->varchar(128)
            ->null();

        $this->column('password_expiry')
            ->timestamp()
            ->null();

        $this->column('2fa_provider_id')
            ->integer()
            ->null();

        $this->column('2fa_secret')
            ->varchar(64)
            ->null();

        $this->column('last_login')
            ->timestamp()
            ->null();

        $this->column('created_at')
            ->timestamp()
            ->isa('DateTime')
            ->default(new Raw('CURRENT_TIMESTAMP'));

        $this->column('updated_at')
            ->timestamp()
            ->isa('DateTime')
            ->default(new Raw('CURRENT_TIMESTAMP'))
            ->onUpdate(new Raw('CURRENT_TIMESTAMP'));
    }
}