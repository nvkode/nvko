<?php

/**
 * IdTrait adds createdAt and updatedAt fields. Entity class should use HasLifecycleCallbacks.
 *
 * @author  Mykyta Melnyk <liswelus@gmail.com>
 * @license MIT <https://github.com/nvkode/nvdoc-bundle/blob/development/LICENSE>
 */

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait TimestampTrait
{

    /**
     * @var DateTimeImmutable $createdAt
     */
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    /**
     * @var DateTime $updatedAt
     */
    #[ORM\Column(type: 'datetime')]
    private DateTime $updatedAt;


    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;

    }


    /**
     * @param DateTimeImmutable $createdAt
     *
     * @return self
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;

    }


    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;

    }


    /**
     * @param DateTime $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;

    }


    /**
     * Set up createdAt and updatedAt on entity persist
     *
     * @return void
     */
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTime();

    }


    /**
     * Set up new DateTime in updatedAt field on entity update
     *
     * @return void
     */
    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTime();

    }


}
