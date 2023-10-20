<?php
namespace Pyncer\Snyppet\I18n\Middleware;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;
use Pyncer\App\Identifier as ID;
use Pyncer\Data\Mapper\MapperAdaptorInterface;
use Pyncer\Database\ConnectionInterface;
use Pyncer\Exception\UnexpectedValueException;
use Pyncer\I18n\I18n;
use Pyncer\Http\Server\MiddlewareInterface;
use Pyncer\Http\Server\RequestHandlerInterface;
use Pyncer\Source\SourceMapInterface;

class I18nMiddleware implements MiddlewareInterface
{
    private string $sourceMapIdentifier;
    private string $mapperAdaptorIdentifier;

    public function __construct(
        ?string $sourceMapIdentifier = null,
        ?string $mapperAdaptorIdentifier = null,
    ) {
        $this->setSourceMapIdentifier(
            $sourceMapIdentifier ?? ID::sourceMap('i18n')
        );

        $this->setMapperAdaptorIdentifier(
            $mapperAdaptorIdentifier ?? ID::mapperAdaptor('locale')
        );
    }

    public function getSourceMapIdentifier(): ?string
    {
        return $this->sourceMapIdentifier;
    }
    public function setSourceMapIdentifier(?string $value): static
    {
        $this->sourceMapIdentifier = $value;
        return $this;
    }

    public function getMapperAdaptorIdentifier(): string
    {
        return $this->mapperAdaptorIdentifier;
    }
    public function setMapperAdaptorIdentifier(string $value): static
    {
        $this->mapperAdaptorIdentifier = $value;
        return $this;
    }

    public function __invoke(
        PsrServerRequestInterface $request,
        PsrResponseInterface $response,
        RequestHandlerInterface $handler
    ): PsrResponseInterface
    {
        // Database
        if (!$handler->has(ID::DATABASE)) {
            throw new UnexpectedValueException(
                'Database connection expected.'
            );
        }

        $connection = $handler->get(ID::DATABASE);
        if (!$connection instanceof ConnectionInterface) {
            throw new UnexpectedValueException('Invalid database connection.');
        }

        // Mapper adaptor
        if (!$handler->has($this->getMapperAdaptorIdentifier())) {
            throw new UnexpectedValueException('Mapper adaptor expected.');
        }

        $mapperAdaptor = $handler->get($this->getMapperAdaptorIdentifier());
        if (!$mapperAdaptor instanceof MapperAdaptorInterface) {
            throw new UnexpectedValueException(
                'Invalid mapper adaptor.'
            );
        }

        // Source map
        if (!$handler->has($this->getSourceMapIdentifier())) {
            throw new UnexpectedValueException('Source map expected.');
        }

        $sourceMap = $handler->get($this->getSourceMapIdentifier());
        if (!$sourceMap instanceof SourceMapInterface) {
            throw new UnexpectedValueException('Invalid source map.');
        }

        $defaultLocaleCode =  null;
        $userLocaleCode = null;
        $userLocaleId = null;

        if ($handler->has(ID::user())) {
            $userValues = $handler->get(ID::user('value'));
            $userLocaleId = $userValues->getInt('locale_id');
        }

        $i18n = new I18n($sourceMap);
        $localeCodes = [];

        $mapper = $mapperAdaptor->getMapper();
        $result = $mapper->selectAll($mapperAdaptor->getMapperQuery());

        foreach ($result as $model) {
            $data = $mapperAdaptor->forgeData($model);

            $localeCodes[] = $data['code'];

            if ($userLocaleId === $data['id']) {
                $userLocaleCode = $data['code'];
            }

            if ($defaultLocaleCode === null ||
                $data['default']
            ) {
                $defaultLocaleCode = $data['code'];
            }

            $i18n->addLocale($data['code']);
        }

        if (!$localeCodes) {
            throw new UnexpectedValueException('No enabled i18n locale codes.');
        }

        if ($defaultLocaleCode !== null) {
            $i18n->setFallbackLocaleCode($defaultLocaleCode);
        }

        $requestLocaleCode = $this->getRequestLocaleCode($request, $localeCodes);

        $i18n->setDefaultLocaleCode(
            $userLocaleCode ??
            $requestLocaleCode ??
            $defaultLocaleCode
        );

        $handler->set(ID::I18N, $i18n);

        return $handler->next($request, $response);
    }

    private function getRequestLocaleCode(
        PsrServerRequestInterface $request,
        array $localeCodes
    ): ?string
    {
        $header = $request->getHeader('Accept-Language');

        if (!$header) {
            return null;
        }

        $header = $header[0];

        if ($header === '') {
            return null;
        }

        $languages = explode(',', $header);
        foreach ($languages as $key => $language) {
            // Remove quality score
            // Ex "en-US,en;q=0.5"
            $language = explode(';', $language)[0];

            foreach ($localeCodes as $localeCode) {
                if ($localeCode === $language) {
                    return $language;
                }

                $language = explode('-', $language, 2);
                $language = $language[0];

                $localeShortCode = substr($localeCode, 0, strlen($language . '-'));

                if ($localeShortCode === $language . '-') {
                    return $language;
                }
            }
        }

        return null;
    }
}
