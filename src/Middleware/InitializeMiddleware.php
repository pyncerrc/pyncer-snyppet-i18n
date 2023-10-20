<?php
namespace Pyncer\Snyppet\I18n\Middleware;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;
use Pyncer\App\Identifier as ID;
use Pyncer\Data\Mapper\MapperAdaptor;
use Pyncer\Data\MapperQuery\FiltersQueryParam;
use Pyncer\Database\ConnectionInterface;
use Pyncer\Exception\UnexpectedValueException;
use Pyncer\Http\Server\MiddlewareInterface;
use Pyncer\Http\Server\RequestHandlerInterface;
use Pyncer\Snyppet\I18n\Table\I18n\I18nMapper;
use Pyncer\Snyppet\I18n\Table\I18n\I18nMapperQuery;

class InitializeMiddleware implements MiddlewareInterface
{
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
            throw new UnexpectedValueException(
                'Invalid database connection.'
            );
        }

        // I18n mapper adaptor
        if (!$handler->has(ID::mapperAdaptor('i18n'))) {
            $i18nMapperQuery = new I18nMapperQuery($connection);
            $i18nMapperQuery->setFilters(new FiltersQueryParam(
                'enabled eq true'
            ));
            $i18nMapperAdaptor = new MapperAdaptor(
                new I18nMapper($connection),
                $i18nMapperQuery
            );
            $handler->set(ID::mapperAdaptor('i18n'), $i18nMapperAdaptor);
        }

        return $handler->next($request, $response);
    }
}
