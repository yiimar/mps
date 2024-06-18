<?php

declare(strict_types=1);

namespace App\Core\Application\Mediator;

use App\Core\Application\Bus\Command\Command;
use App\Core\Application\Bus\Query\Query;
use App\Core\Application\Bus\Query\QueryResponse;

/**
 * The CommandQueryMediator interface defines a contract for a mediator between commands and queries.
 * It provides two methods: ask and dispatch.
 * The ask method is used to dispatch a query and return its response.
 * The dispatch method is used to dispatch a command.
 */
interface CommandQueryMediator
{
    /**
     * Dispatches the given query and returns its response.
     *
     * @param Query $query the query to be dispatched
     *
     * @return QueryResponse the response of the query execution
     */
    public function ask(Query $query): QueryResponse;

    /**
     * Dispatches the given command.
     * The method does not return a value.
     *
     * @param Command $command the command to be dispatched
     */
    public function dispatch(Command $command): void;
}