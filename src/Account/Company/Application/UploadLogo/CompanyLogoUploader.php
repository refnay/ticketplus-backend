<?php

namespace App\Account\Company\Application\UploadLogo;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyLogo;
use App\Account\Company\Domain\CompanyRepository;
use App\Account\Company\Domain\Exceptions\CompanyLogoNotUploaded;
use App\Account\Company\Domain\Services\CompanyFinder;
use App\Shared\Domain\Services\ImageUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

class CompanyLogoUploader
{
    public function __construct(
        private CompanyRepository $repository,
        private CompanyFinder $finder,
        private ImageUploader $uploader
    ) {
    }

    public function __invoke(CompanyId $id, ?UploadedFile $logo): void
    {
        $company = $this->finder->__invoke($id);

        if (!is_null($logo)) {
            try {
                $url = $this->uploader->upload($logo->getRealPath());
                $company->changeLogo(CompanyLogo::fromString($url));
            } catch (Throwable) {
                throw new CompanyLogoNotUploaded();
            }
        } else {
            $company->changeLogo(CompanyLogo::fromNull());
        }   
        
        $this->repository->update($company);
    }
}