<?php

namespace Moonwalker\Models;


use Maghead\Runtime\Collection;

class TodoCollectionBase
    extends Collection
{

    const SCHEMA_PROXY_CLASS = 'Moonwalker\\Models\\TodoSchemaProxy';

    const MODEL_CLASS = 'Moonwalker\\Models\\Todo';

    const TABLE = 'todos';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    public static function createRepo($write, $read)
    {
        return new \Moonwalker\Models\TodoRepoBase($write, $read);
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \Moonwalker\Models\TodoSchemaProxy;
    }
}
