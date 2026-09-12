<?php

namespace App\Tests\Shared\Application\Security;

use App\Account\Member\Domain\MemberRoleList;
use App\Account\Member\Domain\MemberStatusList;
use App\Shared\Application\Security\AuthorizationContext;
use App\Shared\Application\Security\CurrentActor;
use App\Shared\Application\Security\Exception\CompanyOwnerRequired;
use App\Shared\Application\Security\Exception\AuthenticationRequired;
use App\Shared\Application\Security\Exception\CompanyRequired;
use App\Shared\Application\Security\Exception\MemberRequired;
use PHPUnit\Framework\TestCase;

final class AuthorizationContextTest extends TestCase
{
    public const string USER_ID = '018f7c54-5f88-7e04-8a90-7af68c932551';
    private const string COMPANY_ID = '018f7c54-5f88-7e04-8a90-7af68c932552';
    private const string MEMBER_ID = '018f7c54-5f88-7e04-8a90-7af68c932553';

    public function testAnyAuthenticatedUserIsAllowedWithoutACompany(): void
    {
        $authorization = new AuthorizationContext($this->actor());

        self::assertSame(self::USER_ID, $authorization->userId());
    }

    public function testAnAnonymousActorIsRejectedWhenAuthenticationIsRequired(): void
    {
        $this->expectException(AuthenticationRequired::class);

        $actor = new class implements CurrentActor {
            public function userId(): ?string { return null; }
            public function companyId(): ?string { return null; }
            public function memberId(): ?string { return null; }
            public function memberRole(): ?int { return null; }
            public function memberStatus(): ?int { return null; }
        };

        (new AuthorizationContext($actor))->userId();
    }

    public function testAnActiveAssociateIsACompanyMember(): void
    {
        $authorization = new AuthorizationContext($this->actor(
            self::COMPANY_ID,
            self::MEMBER_ID,
            MemberRoleList::ASSOCIATE->value,
            MemberStatusList::ACTIVE->value,
        ));

        self::assertSame(self::COMPANY_ID, $authorization->companyId());
    }

    public function testItReturnsTheCurrentActiveMemberId(): void
    {
        $authorization = new AuthorizationContext($this->actor(
            self::COMPANY_ID,
            self::MEMBER_ID,
            MemberRoleList::ASSOCIATE->value,
            MemberStatusList::ACTIVE->value,
        ));

        self::assertSame(self::MEMBER_ID, $authorization->memberId());
    }

    public function testACompanyIsRequiredForCompanyManagement(): void
    {
        $this->expectException(CompanyRequired::class);

        (new AuthorizationContext($this->actor()))->companyId();
    }

    public function testAnInactiveMembershipIsRejected(): void
    {
        $this->expectException(MemberRequired::class);

        (new AuthorizationContext($this->actor(
            self::COMPANY_ID,
            self::MEMBER_ID,
            MemberRoleList::ASSOCIATE->value,
            MemberStatusList::INACTIVE->value,
        )))->companyId();
    }

    public function testOnlyAnOwnerPassesTheOwnerPolicy(): void
    {
        $authorization = new AuthorizationContext($this->actor(
            self::COMPANY_ID,
            self::MEMBER_ID,
            MemberRoleList::OWNER->value,
            MemberStatusList::ACTIVE->value,
        ));

        self::assertSame(self::COMPANY_ID, $authorization->ownerCompanyId());
    }

    public function testAnAssociateDoesNotPassTheOwnerPolicy(): void
    {
        $this->expectException(CompanyOwnerRequired::class);

        (new AuthorizationContext($this->actor(
            self::COMPANY_ID,
            self::MEMBER_ID,
            MemberRoleList::ASSOCIATE->value,
            MemberStatusList::ACTIVE->value,
        )))->ownerCompanyId();
    }

    private function actor(
        ?string $companyId = null,
        ?string $memberId = null,
        ?int $memberRole = null,
        ?int $memberStatus = null,
    ): CurrentActor {
        return new class ($companyId, $memberId, $memberRole, $memberStatus) implements CurrentActor {
            public function __construct(
                private ?string $companyId,
                private ?string $memberId,
                private ?int $memberRole,
                private ?int $memberStatus,
            ) {
            }

            public function userId(): string
            {
                return AuthorizationContextTest::USER_ID;
            }

            public function companyId(): ?string
            {
                return $this->companyId;
            }

            public function memberId(): ?string
            {
                return $this->memberId;
            }

            public function memberRole(): ?int
            {
                return $this->memberRole;
            }

            public function memberStatus(): ?int
            {
                return $this->memberStatus;
            }
        };
    }
}
