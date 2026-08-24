<?php

namespace App\Tests\Application\Security;

use App\Shared\Application\Security\AuthorizationContext;
use App\Shared\Application\Security\CurrentActor;
use App\Shared\Application\Security\Exception\CompanyRequired;
use App\Shared\Application\Security\Exception\MemberNotAllowed;
use App\Shared\Application\Security\Exception\MemberRequired;
use App\Shared\Application\Security\Exception\UserNotAllowed;
use App\Shared\Domain\Enums\MemberStatusList;
use App\Shared\Domain\Enums\UserStatusList;
use App\Shared\Domain\Enums\UserTypeList;
use PHPUnit\Framework\TestCase;

final class AuthorizationContextTest extends TestCase
{
    public function testItExposesTheCurrentScope(): void
    {
        $authorization = new AuthorizationContext($this->actor());

        self::assertSame('user-id', $authorization->userId());
        self::assertSame('company-id', $authorization->requireCompanyId());
        self::assertSame('member-id', $authorization->requireMemberId());
    }

    public function testItAllowsAnActiveWorkerAndMember(): void
    {
        $authorization = new AuthorizationContext($this->actor());

        $authorization->requireAllPermissions();

        self::addToAssertionCount(1);
    }

    public function testItRequiresACompany(): void
    {
        $authorization = new AuthorizationContext($this->actor(companyId: null));

        $this->expectException(CompanyRequired::class);
        $authorization->requireAllPermissions();
    }

    public function testItRequiresAMember(): void
    {
        $authorization = new AuthorizationContext($this->actor(memberId: null));

        $this->expectException(MemberRequired::class);
        $authorization->requireAllPermissions();
    }

    public function testItRejectsANonWorker(): void
    {
        $authorization = new AuthorizationContext($this->actor(userType: UserTypeList::SIMPLE->value));

        $this->expectException(UserNotAllowed::class);
        $authorization->requireAllPermissions();
    }

    public function testItRejectsABlockedUser(): void
    {
        $authorization = new AuthorizationContext($this->actor(userStatus: UserStatusList::INACTIVE->value));

        $this->expectException(UserNotAllowed::class);
        $authorization->requireAllPermissions();
    }

    public function testItRejectsAnInactiveMember(): void
    {
        $authorization = new AuthorizationContext($this->actor(memberStatus: MemberStatusList::INACTIVE->value));

        $this->expectException(MemberNotAllowed::class);
        $authorization->requireAllPermissions();
    }

    private function actor(
        ?string $companyId = 'company-id',
        ?string $memberId = 'member-id',
        int $userType = UserTypeList::WORKER->value,
        int $userStatus = UserStatusList::ACTIVE->value,
        ?int $memberStatus = MemberStatusList::ACTIVE->value,
    ): CurrentActor {
        return new readonly class ($companyId, $memberId, $userType, $userStatus, $memberStatus) implements CurrentActor {
            public function __construct(
                private ?string $companyId,
                private ?string $memberId,
                private int $userType,
                private int $userStatus,
                private ?int $memberStatus,
            ) {
            }

            public function userId(): string
            {
                return 'user-id';
            }

            public function companyId(): ?string
            {
                return $this->companyId;
            }

            public function memberId(): ?string
            {
                return $this->memberId;
            }

            public function memberStatus(): ?int
            {
                return $this->memberStatus;
            }

            public function userType(): int
            {
                return $this->userType;
            }

            public function userStatus(): int
            {
                return $this->userStatus;
            }
        };
    }
}
