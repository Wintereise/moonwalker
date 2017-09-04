<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;
use Magsql\Raw;

class AuthenticationAttemptsSchema extends DeclareSchema
{
    public function schema()
    {
        $this->column('id')
            ->integer()
            ->primary()
            ->autoIncrement();

        $this->column('ip_address')
            ->varchar(140)
            ->notNull();

        $this->column('user_agent')
            ->varchar(512)
            ->notNull();

        $this->column('tenant_id')
            ->integer()
            ->null();

        $this->column('user_id')
            ->integer()
            ->null();

        $this->column('created_at')
            ->timestamp()
            ->isa('DateTime')
            ->default(new Raw('CURRENT_TIMESTAMP'));

    }
}