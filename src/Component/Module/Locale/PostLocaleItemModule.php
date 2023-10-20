<?php
namespace Pyncer\Snyppet\I18n\Component\Module\Locale;

use Pyncer\App\Identifier as ID;
use Pyncer\Component\Module\AbstractPostItemModule;
use Pyncer\Data\Mapper\MapperInterface;
use Pyncer\Data\Validation\ValidatorInterface;
use Pyncer\Snyppet\I18n\Table\Locale\LocaleMapper;
use Pyncer\Snyppet\I18n\Table\Locale\LocaleValidator;

class PostLocaleItemModule extends AbstractPostItemModule
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
}
