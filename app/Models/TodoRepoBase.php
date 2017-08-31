<?php

namespace Moonwalker\Models;


use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Model;
use Maghead\Runtime\Inflator;
use Magsql\Bind;
use Magsql\ArgumentArray;
use PDO;
use Magsql\Universal\Query\InsertQuery;
use Maghead\Runtime\Repo;

class TodoRepoBase
    extends Repo
{

    const SCHEMA_CLASS = 'Moonwalker\\Models\\TodoSchema';

    const SCHEMA_PROXY_CLASS = 'Moonwalker\\Models\\TodoSchemaProxy';

    const COLLECTION_CLASS = 'Moonwalker\\Models\\TodoCollection';

    const MODEL_CLASS = 'Moonwalker\\Models\\Todo';

    const TABLE = 'todos';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const PRIMARY_KEY = 'id';

    const TABLE_ALIAS = 'm';

    const FIND_BY_PRIMARY_KEY_SQL = 'SELECT * FROM todos WHERE id = ? LIMIT 1';

    const DELETE_BY_PRIMARY_KEY_SQL = 'DELETE FROM todos WHERE id = ?';

    public static $columnNames = array (
      0 => 'id',
      1 => 'title',
      2 => 'done',
    );

    public static $columnHash = array (
      'id' => 1,
      'title' => 1,
      'done' => 1,
    );

    public static $mixinClasses = array (
    );

    protected $table = 'todos';

    protected $loadStm;

    public function free()
    {
        $this->loadStm = null;
        $this->deleteStm = null;
    }

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \Moonwalker\Models\TodoSchemaProxy;
    }

    public function findByPrimaryKey($pkId)
    {
        if (!$this->loadStm) {
           $this->loadStm = $this->read->prepare(self::FIND_BY_PRIMARY_KEY_SQL);
           $this->loadStm->setFetchMode(PDO::FETCH_CLASS, 'Moonwalker\Models\Todo', [$this]);
        }
        $this->loadStm->execute([ $pkId ]);
        $obj = $this->loadStm->fetch();
        $this->loadStm->closeCursor();
        return $obj;
    }

    public function collection()
    {
        return new TodoCollection($this);
    }

    protected static function unsetImmutableArgs($args)
    {
        return $args;
    }

    public function deleteByPrimaryKey($pkId)
    {
        if (!$this->deleteStm) {
           $this->deleteStm = $this->write->prepare(self::DELETE_BY_PRIMARY_KEY_SQL);
        }
        return $this->deleteStm->execute([$pkId]);
    }
}
