<?php

namespace App\Account\User\Application\Company\Switch;

use App\Account\User\Domain\UserCurrentCompany;
use App\Account\User\Domain\UserId;

class SwitchUserCompanyCommandHandler
{
    public function __construct(private UserCompanySwitcher $switcher)
    {
    }

    public function __invoke(SwitchUserCompanyCommand $command): void
    {
        $this->switcher->__invoke(
            UserId::fromString($command->session()->user()),
            UserCurrentCompany::fromString($command->company()),
        );
    }
}