<?php
namespace Pyncer\Snyppet\I18n\Component\Module\I18n;

use Pyncer\App\Identifier as ID;
use Pyncer\Component\Module\AbstractPatchItemModule;
use Pyncer\Data\Mapper\MapperInterface;
use Pyncer\Data\MapperQuery\MapperQueryInterface;
use Pyncer\Data\Validation\ValidatorInterface;
use Pyncer\Snyppet\I18n\Table\I18n\I18nMapper;
use Pyncer\Snyppet\I18n\Table\I18n\I18nMapperQuery;
use Pyncer\Snyppet\I18n\Table\I18n\I18nValidator;

class PatchI18nItemModule extends AbstractPatchItemModule
{
    protected function forgeValidator(): ?ValidatorInterface
    {
        $connection = $this->get(ID::DATABASE);
        return new I18nValidator($connection);
    }

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
