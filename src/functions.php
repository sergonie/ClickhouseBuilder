<?php

// @codeCoverageIgnoreStart
use Tinderbox\Clickhouse\Common\File;
use Tinderbox\Clickhouse\Common\FileFromString;
use Tinderbox\Clickhouse\Interfaces\FileInterface;
use Tinderbox\ClickhouseBuilder\Exceptions\BuilderException;

if (!function_exists('tp')) {
    /**
     * Call the given Closure with the given value then return the value.
     *
     * @param  mixed  $value
     *
     * @return mixed
     */
    function tp($value, callable $callback)
    {
        $callback($value);

        return $value;
    }
}
// @codeCoverageIgnoreEnd

// @codeCoverageIgnoreStart
if (!function_exists('array_flatten')) {
    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param  int  $depth
     */
    function array_flatten(array $array, $depth = INF): array
    {
        return array_reduce($array, function ($result, $item) use ($depth) {
            if (!is_array($item)) {
                return array_merge($result, [$item]);
            }

            if ($depth === 1) {
                return array_merge($result, array_values($item));
            }

            return array_merge($result, array_flatten($item, $depth - 1));
        }, []);
    }
}
// @codeCoverageIgnoreEnd

if (!function_exists('raw')) {
    /**
     * Wrap string into Expression object for inserting in sql query as is.
     */
    function raw(string $expr): Tinderbox\ClickhouseBuilder\Query\Expression
    {
        return new Tinderbox\ClickhouseBuilder\Query\Expression($expr);
    }
}

if (!function_exists('into_memory_table')) {
    /**
     * Creates temporary table if table does not exists and inserts provided data into query.
     *
     * @param  Tinderbox\ClickhouseBuilder\Query\Builder|Tinderbox\ClickhouseBuilder\Integrations\Laravel\Builder  $builder
     *
     * @throws BuilderException
     */
    function into_memory_table($builder, ?array $structure = null): bool
    {
        $tableName = null;
        $from = $builder->getFrom();

        if (!is_null($from)) {
            $tableName = $from->getTable();
        }

        $file = $builder->getValues();
        $format = $builder->getFormat();

        if (is_null($tableName) && $file instanceof Tinderbox\Clickhouse\Common\TempTable) {
            $tableName = $file->getName();
        }

        if (is_null($structure) && $file instanceof Tinderbox\Clickhouse\Common\TempTable) {
            $structure = $file->getStructure();
        }

        if (is_null($format) && $file instanceof Tinderbox\Clickhouse\Common\TempTable) {
            $format = $file->getFormat();
        }

        if (is_null($structure)) {
            throw BuilderException::noTableStructureProvided();
        }

        $builder->newQuery()->dropTableIfExists($tableName);
        $builder->newQuery()->createTableIfNotExists($tableName, 'Memory', $structure);

        return $builder->newQuery()->table($tableName)->insertFile(array_keys($structure), $file, $format);
    }
}

if (!function_exists('file_from')) {
    function file_from($file): FileInterface
    {
        if (is_string($file) && is_file($file)) {
            $file = new File($file);
        } elseif (is_scalar($file)) {
            $file = new FileFromString($file);
        }

        return $file;
    }
}
