<?php
namespace Pyncer\Snyppet\I18n\Component\Module\I18n;

use Pyncer\App\Identifier as ID;
use Pyncer\Component\Module\AbstractGetItemModule;
use Pyncer\Data\Mapper\MapperInterface;
use Pyncer\Data\MapperQuery\MapperQueryInterface;
use Pyncer\Snyppet\I18n\Table\I18n\I18nMapper;
use Pyncer\Snyppet\I18n\Table\I18n\I18nMapperQuery;

class GetI18nItemModule extends AbstractGetItemModule
{
    protected function forgeMapper(): MapperInterface
    {
        $connection = $this->get(ID::DATABASE);
        return new I18nMapper($connection);
    }

    protected function forgeMapperQuery(): MapperQueryInterface
    {
        $connection = $this->get(ID::DATABASE);
        return new I18nMapperQuery($connection, $this->request);
    }
}
