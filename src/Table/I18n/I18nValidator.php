<?php
namespace Pyncer\Snyppet\I18n\Table\I18n;

use Pyncer\Data\Validation\AbstractValidator;
use Pyncer\Database\ConnectionInterface;
use Pyncer\Validation\Rule\BoolRule;
use Pyncer\Validation\Rule\StringRule;

class I18nValidator extends AbstractValidator
{
    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct($connection);

        $this->addRules(
            'code',
            new StringRule(
                maxLength: 25,
            ),
        );

        $this->addRules(
            'short_code',
            new StringRule(
                maxLength: 25,
                allowNull: true,
            ),
        );

        $this->addRules(
            'name',
            new StringRule(
                maxLength: 50,
            ),
        );

        $this->addRules(
            'short_name',
            new StringRule(
                maxLength: 50,
                allowNull: true,
            ),
        );

        $this->addRules(
            'default',
            new BoolRule(),
        );

        $this->addRules(
            'enabled',
            new BoolRule(),
        );
    }
}
