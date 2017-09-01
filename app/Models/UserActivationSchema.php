<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;

class UserActivationSchema extends DeclareSchema
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

        $this->column('type')
            ->enum([ 'ACTIVATION', 'RESET' ])
            ->notNull();

        $this->column('token')
            ->varchar(32)
            ->notNull();

        $this->column('expires')
            ->timestamp()
            ->notNull();

        $this->column('created_at')
            ->timestamp()
            ->default(['current_timestamp']);
    }
}