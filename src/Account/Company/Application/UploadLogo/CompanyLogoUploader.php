<?php

namespace App\Account\Company\Application\UploadLogo;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyLogo;
use App\Account\Company\Domain\CompanyRepository;
use App\Account\Company\Domain\Exceptions\CompanyLogoNotUploaded;
use App\Account\Company\Domain\Services\CompanyFinder;
use App\Shared\Application\Port\Image\ImageUploader;
use App\Shared\Application\Input\FileUpload;
use Throwable;

class CompanyLogoUploader
{
    public function __construct(
        private CompanyRepository $repository,
        private CompanyFinder $finder,
        private ImageUploader $uploader
    ) {
    }

    public function __invoke(CompanyId $id, ?FileUpload $logo): void
    {
        $company = $this->finder->__invoke($id);

        if (!is_null($logo)) {
            try {
                $url = $this->uploader->upload($logo->path());
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
