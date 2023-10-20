<?php
namespace Pyncer\Snyppet\I18n\Install;

use Pyncer\Snyppet\AbstractInstall;

class Install extends AbstractInstall
{
    protected function safeInstall(): bool
    {
        $this->connection->createTable('locale')
            ->serial('id')
            ->string('code', 25)->index()
            ->string('short_code', 25)->null()->index()
            ->string('name', 50)->index()
            ->string('short_name', 50)->null()->index()
            ->bool('default')->default(false)->index()
            ->bool('enabled')->default(false)->index()
            ->execute();

        $this->connection->insert('locale')
            ->values([
                'code' => 'en',
                'short_code' => null,
                'name' => 'English',
                'short_name' => null,
                'default' => true,
                'enabled' => true,
            ])
            ->execute();

        $this->connection->insert('locale')
            ->values([
                'code' => 'en-US',
                'short_code' => 'en',
                'name' => 'English United States',
                'short_name' => 'English',
                'default' => false,
                'enabled' => false,
            ])
            ->execute();

        $this->connection->insert('locale')
            ->values([
                'code' => 'en-CA',
                'short_code' => 'en',
                'name' => 'English Canada',
                'short_name' => 'English',
                'default' => false,
                'enabled' => false,
            ])
            ->execute();

        $this->connection->insert('locale')
            ->values([
                'code' => 'en-AU',
                'short_code' => 'en',
                'name' => 'English Austrailia',
                'short_name' => 'English',
                'default' => false,
                'enabled' => false,
            ])
            ->execute();

        $this->connection->insert('locale')
            ->values([
                'code' => 'en-UK',
                'short_code' => 'en',
                'name' => 'English United Kingdom',
                'short_name' => 'English',
                'default' => false,
                'enabled' => false,
            ])
            ->execute();

        $this->connection->insert('locale')
            ->values([
                'code' => 'fr',
                'short_code' => null,
                'name' => 'Français',
                'short_name' => null,
                'default' => false,
                'enabled' => false,
            ])
            ->execute();

        $this->connection->insert('locale')
            ->values([
                'code' => 'fr-CA',
                'short_code' => 'fr',
                'name' => 'Français Canada',
                'short_name' => 'Français',
                'default' => false,
                'enabled' => false,
            ])
            ->execute();

        $this->connection->insert('locale')
            ->values([
                'code' => 'ja',
                'short_code' => null,
                'name' => '日本語',
                'short_name' => null,
                'default' => false,
                'enabled' => false,
            ])
            ->execute();

        $this->connection->insert('locale')
            ->values([
                'code' => 'ru',
                'short_code' => null,
                'name' => 'русский',
                'short_name' => null,
                'default' => false,
                'enabled' => false,
            ])
            ->execute();

        return true;
    }

    protected function safeUninstall(): bool
    {
        if ($this->connection->hasTable('locale')) {
            $this->connection->dropTable('locale');
        }

        return true;
    }
}
