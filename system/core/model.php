<?php

namespace system\core;


use system\base\commons;
use system\base\Crud;
use system\base\dbsetter;

abstract class Model
{
    protected $schema = array();
    protected $currentColumn = '';
    use dbsetter;
    use Crud;
    use commons;
    public function __construct()
    {
    }

    /**
     * @param $table
     * @return void
     */
    public function table($table = null)
    {
        if (!empty($table)) {
            $this->schema['table'] = $table;
        }
    }

    /**
     * @param $column
     * @return string
     */
    public function getTable($column = null): string
    {
        if (isset($this->schema['table'])) {
            if ($this->columnExist($column)) {
                return $this->schema['table'] . '.' . $column;
            } else {
                return $this->schema['table'];
            }
        }
        return '';
    }

    /**
     * @param $column
     * @return $this
     */
    public function column($column = null)
    {
        if (!empty($column)) {
            $this->schema[$column] = array('name' => $column);
            $this->currentColumn = $column;
        }
        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return void
     */
    public function columnValue($column = null, $value = null)
    {
        if ($this->columnExist($column)) {
            $this->schema[$column]['value'] = $value;
        }
    }

    /**
     * @param $column
     * @return mixed|string
     */
    public function getColumnValue($column = null)
    {
        if ($this->hasValue($column)) {
            return $this->schema[$column]['value'];
        }
        return '';
    }

    /**
     * @param null $column
     * 
     * @return [type]
     */
    public function isColumnEmpty($column = null)
    {
        return in_array($this->getColumnValue($column), [null, ""]);
    }

    /**
     * @param $column
     * @return mixed|string
     */
    public function getColumnSource($column = null)
    {
        if ($this->columnHasSource($column)) {
            return $this->schema[$column]['source'];
        }
        return '';
    }

    /**
     * @return array
     */
    public function allTables(): array
    {
        $foreignTables = $this->foreignTables();
        array_unshift($foreignTables, $this->getTable());
        return $foreignTables;
    }

    /**
     * @return string
     */
    public function allTablesString(): string
    {
        return implode(',', $this->allTables());
    }

    /**
     * @return array
     */
    public function foreignTables(): array
    {
        $foreignTables = array();

        foreach ($this->schema as $column => $value) {
            if ($this->is_foreign($column)) {
                $foreignTables[] = $this->getColumn($column)['foreign'];
            }
        }
        return $foreignTables;
    }
    public function foreignColumns(): array
    {
        $foreignColumns = array();

        foreach ($this->schema as $column => $value) {
            if ($this->is_foreign($column)) {
                $foreignColumns[] = $this->getColumn($column);
            }
        }
        return $foreignColumns;
    }

    /**
     * @param $key
     * @return array
     */
    public function foreignColumn($key): array
    {
        if (isset($this->foreignColumns()[$key])) {
            return $this->foreignColumns()[$key];
        }
        return array();
    }

    /**
     * @param $column
     * @return bool
     */
    public function columnHasSource($column = null): bool
    {
        if ($this->columnExist($column)) {
            return isset($this->getColumn($column)['source']);
        }
        return false;
    }

    /**
     * @param $key
     * @param $source
     * @return string|void
     */
    public function foreignColumnLiteral($key, $source = false)
    {
        if (isset($this->foreignColumns()[$key])) {
            $columnName = $this->foreignColumns()[$key]['name'];
            if ($source) {
                if ($this->columnHasSource($columnName)) {
                    return $this->foreignTable($key) . '.' . $this->getColumnSource($columnName);
                } else {
                    return $this->foreignTable($key) . '.' . $columnName;
                }
            } else {
                return $this->getTable($columnName);
            }
        }
    }

    /**
     * @return string
     */
    public function foreignTablesString(): string
    {

        return implode(',', $this->foreignTables());
    }

    /**
     * @param $key
     * @return string
     */
    public function foreignTable($key): string
    {
        if (isset($this->foreignTables()[$key])) {
            return $this->foreignTables()[$key];
        }
        return '';
    }

    /**
     * @param $column
     * @return bool
     */
    public function hasValue($column = null): bool
    {
        if ($this->columnExist($column)) {
            return isset($this->getColumn($column)['value']);
        }
        return false;
    }

    /**
     * @param $column
     * @return mixed|void
     */
    public function getColumn($column = null)
    {
        if ($this->columnExist($column)) {
            return $this->schema[$column];
        }
    }

    /**
     * @param $column
     * @return bool
     */
    public function columnExist($column = null): bool
    {
        if (!empty($column) && isset($this->schema[$column])) {
            return true;
        }
        return false;
    }

    /**
     * @param $type
     * @param $column
     * @return $this
     */
    public function type($type, $column = null)
    {
        return $this->attribute($column, 'type', $type);
    }

    /**
     * @param $column
     * @return $this
     */
    public function id($column = null)
    {
        return $this->attribute($column, 'id', true);
    }

    /**
     * @return int|string|void
     */
    public function getId()
    {
        foreach ($this->getSchema() as $column => $value) {
            if ($this->is_id($column)) {
                return $column;
            }
        }
    }
    public function setId($value)
    {
        $this->columnValue($this->getId(), $value);
    }

    /**
     * @return mixed|string
     */
    public function getIdValue()
    {
        return $this->getColumnValue($this->getId());
    }

    /**
     * @param $source
     * @param $column
     * @return $this
     */
    public function foreign($source, $column = null)
    {
        return $this->attribute($column, 'foreign', $source);
    }

    /**
     * @param $source
     * @param $column
     * @return $this
     */
    public function source($source, $column = null)
    {
        return $this->attribute($column, 'source', $source);
    }

    /**
     * @param $column
     * @return bool
     */
    public function is_id($column = null): bool
    {
        return $this->is_($column, 'id');
    }

    /**
     * @param $column
     * @return bool
     */
    public function is_foreign($column = null): bool
    {
        return $this->is_($column, 'foreign');
    }

    /**
     * @param $column
     * @param $type
     * @return bool
     */
    public function is_($column = null, $type): bool
    {
        if ($this->columnExist($column)) {
            return isset($this->getColumn($column)[$type]);
        }
        return false;
    }

    /**
     * @param $column
     * @param $attribute
     * @param $value
     * @return $this
     */
    public function attribute($column = null, $attribute = null, $value): Model
    {
        //
        if (empty($this->currentColumn)) {
            //
            if ($this->columnExist($column)) {
                $this->schema[$column][$attribute] = $value;
            }
        } else {
            //
            $this->schema[$this->currentColumn][$attribute] = $value;
        }
        return $this;
    }
    /**
     * @return array
     */
    public function getSchema(): array
    {
        return $this->schema;
    }
}
