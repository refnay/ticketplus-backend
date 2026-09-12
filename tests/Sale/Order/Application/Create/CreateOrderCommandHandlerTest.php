<?php

namespace App\Tests\Sale\Order\Application\Create;

use App\Sale\Order\Application\Create\CreateOrderCommand;
use App\Sale\Order\Application\Create\CreateOrderCommandHandler;
use App\Sale\Order\Application\Create\OrderCreator;
use App\Shared\Application\Security\AuthorizationContext;
use App\Shared\Application\Security\CurrentActor;
use PHPUnit\Framework\TestCase;

final class CreateOrderCommandHandlerTest extends TestCase
{
    public function testAnAuthenticatedUserWithoutACompanyCanCreateAnOrder(): void
    {
        $actor = new class implements CurrentActor {
            public function userId(): string
            {
                return '018f7c54-5f88-7e04-8a90-7af68c932551';
            }

            public function companyId(): ?string
            {
                return null;
            }

            public function memberId(): ?string
            {
                return null;
            }

            public function memberRole(): ?int
            {
                return null;
            }

            public function memberStatus(): ?int
            {
                return null;
            }
        };
        $creator = $this->createMock(OrderCreator::class);
        $creator->expects(self::once())
            ->method('__invoke')
            ->willReturn('018f7c54-5f88-7e04-8a90-7af68c932555');
        $handler = new CreateOrderCommandHandler(new AuthorizationContext($actor), $creator);
        $command = CreateOrderCommand::create([
            'day' => '018f7c54-5f88-7e04-8a90-7af68c932553',
            'items' => [[
                'zone' => '018f7c54-5f88-7e04-8a90-7af68c932554',
                'quantity' => 1,
                'seats' => [],
            ]],
        ]);

        self::assertSame('018f7c54-5f88-7e04-8a90-7af68c932555', $handler->__invoke($command));
    }
}
