<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;

class TwoFactorTransientAuthenticationSchema extends DeclareSchema
{
    public function schema()
    {
        $this->column('id')
            ->integer()
            ->primary()
            ->autoIncrement();

        $this->column('user_id')
            ->integer()
            ->notNull();

        $this->column('token')
            ->varchar(32)
            ->notNull();

        $this->column('expires')
            ->timestamp()
            ->notNull();

        $this->column('ip_address')
            ->varchar(140)
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