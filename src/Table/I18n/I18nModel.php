<?php
namespace Pyncer\Snyppet\I18n\Table\I18n;

use Pyncer\Data\Model\AbstractModel;

class I18nModel extends AbstractModel
{
    public function getCode(): string
    {
        return $this->get('code');
    }
    public function setCode(string $value): static
    {
        $this->set('code', $value);
        return $this;
    }

    public function getShortCode(): ?string
    {
        return $this->get('short_code');
    }
    public function setShortCode(?string $value): static
    {
        $this->set('short_code', $this->nullify($value));
        return $this;
    }

    public function getName(): string
    {
        return $this->get('name');
    }
    public function setName(string $value): static
    {
        $this->set('name', $value);
        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->get('short_name');
    }
    public function setShortName(?string $value): static
    {
        $this->set('short_name', $this->nullify($value));
        return $this;
    }

    public function getDefault(): bool
    {
        return $this->get('default');
    }
    public function setDefault(bool $value): static
    {
        $this->set('default', $value);
        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->get('enabled');
    }
    public function setEnabled(bool $value): static
    {
        $this->set('enabled', $value);
        return $this;
    }

    public static function getDefaultData(): array
    {
        return [
            'id' => 0,
            'code' => '',
            'short_code' => null,
            'name' => '',
            'short_name' => null,
            'default' => false,
            'enabled' => false,
        ];
    }
}
