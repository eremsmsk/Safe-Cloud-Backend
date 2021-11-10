<?php

namespace App\Entity\Blameable;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait BlameableTrait
 */
trait BlameableTrait
{
    /**
     * @var User|null $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned()
     * @Groups({"blameable:input", "blameable:output"})
     */
    private $createdBy;

    /**
     * @var User|null $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned()
     * @Groups({"blameable:input", "blameable:output"})
     */
    private $updatedBy;

    /**
     * @param User|null $createdBy
     * @return $this
     */
    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    /**
     * @param User|null $updatedBy
     * @return $this
     */
    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }
}
