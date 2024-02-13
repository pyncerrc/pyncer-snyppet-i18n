<?php
namespace Pyncer\Snyppet\I18n\Component\Module\Locale;

use Pyncer\App\Identifier as ID;
use Pyncer\Component\Module\AbstractGetItemModule;
use Pyncer\Data\Mapper\MapperInterface;
use Pyncer\Data\MapperQuery\MapperQueryInterface;
use Pyncer\Snyppet\I18n\Table\Locale\LocaleMapper;
use Pyncer\Snyppet\I18n\Table\Locale\LocaleMapperQuery;

class GetLocaleItemModule extends AbstractGetItemModule
{
    protected function forgeMapper(): MapperInterface
    {
        $connection = $this->get(ID::DATABASE);
        return new LocaleMapper($connection);
    }

    protected function forgeMapperQuery(): MapperQueryInterface
    {
        $connection = $this->get(ID::DATABASE);
        return new LocaleMapperQuery($connection);
    }
}
