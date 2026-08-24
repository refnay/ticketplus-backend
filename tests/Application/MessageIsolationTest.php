<?php

namespace App\Tests\Application;

use App\Account\Company\Application\Create\CreateCompanyCommand;
use App\Catalog\Category\Application\Search\SearchCategoryQuery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class MessageIsolationTest extends TestCase
{
    public function testCommandsDoNotCarryTheCurrentActor(): void
    {
        $command = new ReflectionClass(CreateCompanyCommand::class);

        self::assertFalse($command->hasMethod('setSession'));
        self::assertFalse($command->hasMethod('session'));
    }

    public function testQueriesDoNotCarryTheCurrentActor(): void
    {
        $query = new ReflectionClass(SearchCategoryQuery::class);

        self::assertFalse($query->hasMethod('setSession'));
        self::assertFalse($query->hasMethod('session'));
    }
}
