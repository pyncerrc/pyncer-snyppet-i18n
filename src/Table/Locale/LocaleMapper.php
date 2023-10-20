<?php
namespace Pyncer\Snyppet\I18n\Table\I18n;

use Pyncer\Data\Mapper\AbstractMapper;
use Pyncer\Data\MapperQuery\MapperQueryInterface;
use Pyncer\Data\Model\ModelInterface;
use Pyncer\Snyppet\I18n\Table\Locale\LocaleMapperQuery;
use Pyncer\Snyppet\I18n\Table\Locale\LocaleModel;

class LocaleMapper extends AbstractMapper
{
    public function getTable(): string
    {
        return 'locale';
    }

    public function forgeModel(iterable $data = []): ModelInterface
    {
        return new LocaleModel($data);
    }

    public function isValidModel(ModelInterface $model): bool
    {
        return ($model instanceof LocaleModel);
    }

    public function isValidMapperQuery(MapperQueryInterface $mapperQuery): bool
    {
        return ($mapperQuery instanceof LocaleMapperQuery);
    }
}
