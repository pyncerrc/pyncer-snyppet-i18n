<?php
namespace Pyncer\Snyppet\I18n\Table\I18n;

use Pyncer\Snyppet\I18n\Table\I18n\I18nModel;
use Pyncer\Data\MapperQuery\AbstractRequestMapperQuery;
use Pyncer\Data\Model\ModelInterface;
use Pyncer\Database\ConnectionInterface;
use Pyncer\Database\Record\SelectQueryInterface;

class I18nMapperQuery extends AbstractRequestMapperQuery
{
    public function overrideModel(
        ModelInterface $model,
        array $data
    ): ModelInterface
    {
        return $model;
    }

    protected function isValidFilter(
        string $left,
        mixed $right,
        string $operator
    ): bool
    {
        if ($left === 'default' && is_bool($right) && $operator === '=') {
            return true;
        }

        if ($left === 'enabled' && is_bool($right) && $operator === '=') {
            return true;
        }

        return parent::isValidFilter($left, $right, $operator);
    }

    protected function applyFilter(
        SelectQueryInterface $query,
        string $left,
        mixed $right,
        string $operator
    ): SelectQueryInterface
    {
        return parent::applyFilter($query, $left, $right, $operator);
    }

    protected function isValidOrderBy(string $key, string $direction): bool
    {
        switch ($key) {
            case 'code':
            case 'short_code':
            case 'name':
            case 'short_name':
            case 'default':
            case 'enabled':
                return true;
        }

       return parent::isValidOrderBy($key, $direction);
    }
}
