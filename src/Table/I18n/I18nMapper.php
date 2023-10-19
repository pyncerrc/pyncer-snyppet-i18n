<?php
namespace Pyncer\Snyppet\I18n\Table\I18n;

use Pyncer\Snyppet\I18n\Table\I18n\I18nMapperQuery;
use Pyncer\Snyppet\I18n\Table\I18n\I18nModel;
use Pyncer\Data\Mapper\AbstractMapper;
use Pyncer\Data\MapperQuery\MapperQueryInterface;
use Pyncer\Data\Model\ModelInterface;

class I18nMapper extends AbstractMapper
{
    public function getTable(): string
    {
        return 'i18n';
    }

    public function forgeModel(iterable $data = []): ModelInterface
    {
        return new I18nModel($data);
    }

    public function isValidModel(ModelInterface $model): bool
    {
        return ($model instanceof I18nModel);
    }

    public function isValidMapperQuery(MapperQueryInterface $mapperQuery): bool
    {
        return ($mapperQuery instanceof I18nMapperQuery);
    }
}
