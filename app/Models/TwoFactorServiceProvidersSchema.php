<?php

namespace Moonwalker\Models;

use Maghead\Schema\DeclareSchema;
use Magsql\Raw;

class TwoFactorServiceProvidersSchema extends DeclareSchema
{
    public function schema()
    {
        $this->column('id')
            ->integer()
            ->primary()
            ->autoIncrement();

        $this->column('name')
            ->varchar(32)
            ->notNull();

        $this->column('token')
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
    }
}