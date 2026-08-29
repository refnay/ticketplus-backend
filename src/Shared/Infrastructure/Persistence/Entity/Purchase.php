<?php

namespace App\Shared\Infrastructure\Persistence\Entity;

use App\Shared\Infrastructure\Persistence\Repository\PurchaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
#[ORM\Table(name: '`purchase`')]
#[ORM\Index(name: 'IDX_PURCHASE_APPROVED_SALES', columns: ['event_id', 'status', 'paid_at'])]
#[ORM\HasLifecycleCallbacks]
class Purchase
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $subTotal = null;

    #[ORM\Column]
    private ?float $tax = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(length: 3)]
    private ?string $currency = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column]
    private ?int $paymentMethod = null;

    #[ORM\Column(type: 'json')]
    private array $details = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $paidAt = null;

    #[ORM\Column(nullable: true)]
    private ?float $exchangeRate = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $attendee = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    /**
     * @var Collection<int, Ticket>
     */
    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'purchase', orphanRemoval: true)]
    private Collection $tickets;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'purchase', orphanRemoval: true)]
    private Collection $payments;

    #[ORM\ManyToOne]
    private ?Discount $discount = null;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }
    
    public function getSubTotal(): ?float
    {
        return $this->subTotal;
    }

    public function setSubTotal(float $subTotal): static
    {
        $this->subTotal = $subTotal;

        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(float $tax): static
    {
        $this->tax = $tax;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentMethod(): ?int
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(int $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @param array<mixed> $details
     */
    public function setDetails(array $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresdAt(\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTimeImmutable $paidAt): static
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getExchangeRate(): ?float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(?float $exchangeRate): static
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function getAttendee(): ?User
    {
        return $this->attendee;
    }

    public function setAttendee(?User $attendee): static
    {
        $this->attendee = $attendee;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setPurchase($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getPurchase() === $this) {
                $ticket->setPurchase(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setPurchase($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getPurchase() === $this) {
                $payment->setPurchase(null);
            }
        }

        return $this;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): static
    {
        $this->discount = $discount;

        return $this;
    }
    
    #[ORM\PrePersist]
    public function createTimestamps(): void
    {
        $now = new \DateTimeImmutable();

        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
    
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }
}
