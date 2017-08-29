<?php


namespace Moonwalker\Model;
use Maghead\Schema\DeclareSchema;


class User extends DeclareSchema
{
    public function schema()
    {
        $this->column('name')
            ->varchar(80)
            ->label('Name')
        ;

        $this->column('activated')
            ->boolean()
            ->label('Activated')
            ->default(false)
        ;
    }
}