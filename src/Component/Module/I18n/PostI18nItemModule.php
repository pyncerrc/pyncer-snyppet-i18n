<?php
namespace Pyncer\Snyppet\I18n\Component\Module\I18n;

use Pyncer\App\Identifier as ID;
use Pyncer\Component\Module\AbstractPostItemModule;
use Pyncer\Data\Mapper\MapperInterface;
use Pyncer\Data\Validation\ValidatorInterface;
use Pyncer\Snyppet\I18n\Table\I18n\I18nMapper;
use Pyncer\Snyppet\I18n\Table\I18n\I18nValidator;

class PostI18nItemModule extends AbstractPostItemModule
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
}
