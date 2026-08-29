<?php

namespace App\Catalog\Event\Domain;

use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Domain\Audit\Audit;

class Event
{
    use Audit;
    private EventId $id;
    private EventName $name;
    private EventSlug $slug;
    private EventDescription $description;
    private ?EventCoverImage $coverImage = null;
    private ?EventBannerImage $bannerImage = null;
    private ?EventLogo $logo = null;
    private ?EventThumbnail $thumbnail = null;
    private ?EventCanvas $canvas = null;
    private EventVenue $venue;
    private EventCoordinates $coordinates;
    private EventLocation $location;
    private EventOrderLimit $orderLimit;
    private EventCountry $country;
    private EventCity $city;
    private EventCurrency $currency;
    private EventTaxRate $taxRate;
    private EventStatus $status;
    private CategoryId $categoryId;
    private CompanyId $companyId;
    /** @var EventDay[] $days */
    private array $days = [];

    public function __construct(
        EventId $id,
        EventName $name,
        EventSlug $slug,
        EventDescription $description,
        EventCoverImage $coverImage,
        EventBannerImage $bannerImage,
        EventLogo $logo,
        EventThumbnail $thumbnail,
        EventVenue $venue,
        EventCoordinates $coordinates,
        EventLocation $location,
        EventOrderLimit $orderLimit,
        EventCountry $country,
        EventCity $city,
        EventCurrency $currency,
        EventTaxRate $taxRate,
        EventStatus $status,
        EventCanvas $canvas,
        CategoryId $categoryId,
        CompanyId $companyId,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->coverImage = $coverImage;
        $this->bannerImage = $bannerImage;
        $this->logo = $logo;
        $this->thumbnail = $thumbnail;
        $this->venue = $venue;
        $this->coordinates = $coordinates;
        $this->location = $location;
        $this->orderLimit = $orderLimit;
        $this->country = $country;
        $this->city = $city;
        $this->currency = $currency;
        $this->taxRate = $taxRate;
        $this->status = $status;
        $this->canvas = $canvas;
        $this->categoryId = $categoryId;
        $this->companyId = $companyId;
    }

    public static function create(
        EventName $name,
        EventSlug $slug,
        EventDescription $description,
        EventVenue $venue,
        EventCoordinates $coordinates,
        EventLocation $location,
        EventOrderLimit $orderLimit,
        EventCountry $country,
        EventCity $city,
        EventCurrency $currency,
        EventTaxRate $taxRate,
        CategoryId $categoryId,
        CompanyId $companyId,
    ): self {
        return new self(
            EventId::generate(),
            $name,
            $slug,
            $description,
            EventCoverImage::fromNull(),
            EventBannerImage::fromNull(),
            EventLogo::fromNull(),
            EventThumbnail::fromNull(),
            $venue,
            $coordinates,
            $location,
            $orderLimit,
            $country,
            $city,
            $currency,
            $taxRate,
            EventStatus::draft(),
            EventCanvas::fromNull(),
            $categoryId,
            $companyId,
        );
    }

    public function id(): EventId
    {
        return $this->id;
    }

    public function name(): EventName
    {
        return $this->name;
    }

    public function slug(): EventSlug
    {
        return $this->slug;
    }

    public function description(): EventDescription
    {
        return $this->description;
    }

    public function coverImage(): EventCoverImage
    {
        return $this->coverImage ?? EventCoverImage::fromNull();
    }

    public function canvas(): EventCanvas
    {
        return $this->canvas ?? EventCanvas::fromNull();
    }

    public function bannerImage(): EventBannerImage
    {
        return $this->bannerImage ?? EventBannerImage::fromNull();
    }

    public function logo(): EventLogo
    {
        return $this->logo ?? EventLogo::fromNull();
    }

    public function thumbnail(): EventThumbnail
    {
        return $this->thumbnail ?? EventThumbnail::fromNull();
    }

    public function venue(): EventVenue
    {
        return $this->venue;
    }

    public function coordinates(): EventCoordinates
    {
        return $this->coordinates;
    }

    public function location(): EventLocation
    {
        return $this->location;
    }

    public function orderLimit(): EventOrderLimit
    {
        return $this->orderLimit;
    }

    public function country(): EventCountry
    {
        return $this->country;
    }

    public function city(): EventCity
    {
        return $this->city;
    }

    public function currency(): EventCurrency
    {
        return $this->currency;
    }

    public function taxRate(): EventTaxRate
    {
        return $this->taxRate;
    }

    public function status(): EventStatus
    {
        return $this->status;
    }

    public function categoryId(): CategoryId
    {
        return $this->categoryId;
    }

    public function companyId(): CompanyId
    {
        return $this->companyId;
    }

    /** @return EventDay[] */
    public function days()
    {
        return $this->days;
    }

    public function changeName(EventName $name): void
    {
        $this->name = $name;
    }

    public function changeSlug(EventSlug $slug): void
    {
        $this->slug = $slug;
    }

    public function changeDescription(EventDescription $description): void
    {
        $this->description = $description;
    }

    public function changeCoverImage(EventCoverImage $coverImage): void
    {
        $this->coverImage = $coverImage;
    }

    public function changeBannerImage(EventBannerImage $bannerImage): void
    {
        $this->bannerImage = $bannerImage;
    }

    public function changeLogo(EventLogo $logo): void
    {
        $this->logo = $logo;
    }

    public function changeThumbnail(EventThumbnail $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function changeVenue(EventVenue $venue): void
    {
        $this->venue = $venue;
    }

    public function changeCoordinates(EventCoordinates $coordinates): void
    {
        $this->coordinates = $coordinates;
    }

    public function changeLocation(EventLocation $location): void
    {
        $this->location = $location;
    }

    public function changeOrderLimit(EventOrderLimit $orderLimit): void
    {
        $this->orderLimit = $orderLimit;
    }

    public function changeCountry(EventCountry $country): void
    {
        $this->country = $country;
    }

    public function changeCity(EventCity $city): void
    {
        $this->city = $city;
    }

    public function changeCurrency(EventCurrency $currency): void
    {
        $this->currency = $currency;
    }

    public function changeTaxRate(EventTaxRate $taxRate): void
    {
        $this->taxRate = $taxRate;
    }

    public function changeStatus(EventStatus $status): void
    {
        $this->status = $status;
    }

    public function changeCategoryId(CategoryId $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function changeCanvas(EventCanvas $canvas): void
    {
        $this->canvas = $canvas;
    }

    public function addDay(EventDay $day): void
    {
        $this->days[] = $day;
    }

    public function firstDay(): ?EventDay
    {
        $firstDay = null;

        foreach ($this->days() as $day) {
            if (is_null($firstDay) || $day->date()->before($firstDay->date())) {
                $firstDay = $day;
            }
        }

        return $firstDay;
    }

    public function findDayById(EventDayId $id): ?EventDay
    {
        foreach ($this->days() as $day) {
            if ($day->id()->equals($id)) {
                return $day;
            }
        }

        return null;
    }

    public function removeDayById(EventDayId $id): bool
    {
        foreach ($this->days() as $index => $day) {
            if ($day->id()->equals($id)) {
                unset($this->days[$index]);
                
                return true;
            }
        }

        return false;
    }
}
