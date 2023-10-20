<?php
namespace Pyncer\Snyppet\I18n\Component\Module\Locale;

use Pyncer\App\Identifier as ID;
use Pyncer\Component\Module\AbstractPatchItemModule;
use Pyncer\Data\Mapper\MapperInterface;
use Pyncer\Data\MapperQuery\MapperQueryInterface;
use Pyncer\Data\Validation\ValidatorInterface;
use Pyncer\Snyppet\I18n\Table\Locale\LocaleMapper;
use Pyncer\Snyppet\I18n\Table\Locale\LocaleMapperQuery;
use Pyncer\Snyppet\I18n\Table\Locale\LocaleValidator;

class PatchLocaleItemModule extends AbstractPatchItemModule
{
    protected function forgeValidator(): ?ValidatorInterface
    {
        $connection = $this->get(ID::DATABASE);
        return new LocaleValidator($connection);
    }

    protected function forgeMapper(): MapperInterface
    {
        $connection = $this->get(ID::DATABASE);
        return new LocaleMapper($connection);
    }

    protected function forgeMapperQuery(): MapperQueryInterface
    {
        $connection = $this->get(ID::DATABASE);
        return new LocaleMapperQuery($connection, $this->request);
    }
}
