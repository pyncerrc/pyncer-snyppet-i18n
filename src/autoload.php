<?php
use Pyncer\Snyppet\Snyppet;
use Pyncer\Snyppet\SnyppetManager;

SnyppetManager::register(new Snyppet(
    'i18n',
    dirname(__DIR__),
    [
        'initialize' => ['Initialize'],
        'access' => ['I18n'],
    ],
    ['access']
));
