<?php
namespace Pyncer\Snyppet\I18n\Middleware;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;
use Pyncer\App\Identifier as ID;
use Pyncer\Exception\UnexpectedValueException;
use Pyncer\I18n\I18n;
use Pyncer\Http\Server\MiddlewareInterface;
use Pyncer\Http\Server\RequestHandlerInterface;
use Pyncer\Snyppet\I18n\Table\I18n\I18nMapper;
use Pyncer\Source\SourceMap;

class I18nMiddleware implements MiddlewareInterface
{
    private string $sourceMapIdentifier;

    public function __construct(
        string $sourceMapIdentifier,
    ) {
        $this->setSourceMapIdentifier($sourceMapIdentifier);
    }

    public function getSourceMapIdentifier(): string
    {
        return $this->sourceMapIdentifier;
    }
    public function setSourceMapIdentifier(string $value): static
    {
        $this->sourceMapIdentifier = $value;
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

        // Source map
        if (!$handler->has($this->getSourceMapIdentifier())) {
            throw new UnexpectedValueException('Source map expected.');
        }

        $sourceMap = $handler->get($this->getSourceMapIdentifier());
        if (!$sourceMap instanceof SourceMap) {
            throw new UnexpectedValueException('Invalid source map.');
        }

        $i18n = new I18n($sourceMap);

        $defaultLocaleCode =  null;
        $userLocaleCode = null;
        $userI18nId = null;

        if ($handler->has(ID::user())) {
            $userValues = $handler->get(ID::user('value'));
            $userI18nId = $userValues->getInt('i18n_id');
        }

        $i18nMapper = new I18nMapper($connection);
        $result = $i18nMapper->selectAllByColumns([
            'enabled' => true
        ]);

        $localeCodes = [];

        foreach ($result as $i18nModel) {
            $localeCodes[] = $i18nModel->getCode();

            if ($userI18nId === $i18nModel->getId()) {
                $userLocaleCode = $i18nModel->getCode();
            }

            if ($defaultLocaleCode === null ||
                $i18nModel->getDefault()
            ) {
                $defaultLocaleCode = $i18nModel->getCode();
            }

            $i18n->addLocale($i18nModel->getCode());
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
