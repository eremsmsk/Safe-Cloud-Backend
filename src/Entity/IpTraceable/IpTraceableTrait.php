<?php

namespace App\Entity\IpTraceable;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait IpTraceableTrait
 */
trait IpTraceableTrait
{
    /**
     * @var string|null $createdFromIp
     *
     * @Gedmo\IpTraceable(on="create")
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Gedmo\Versioned()
     * @Groups({"ip_traceable:input", "ip_traceable:output"})
     */
    private $createdFromIp;

    /**
     * @var string|null $updatedFromIp
     *
     * @Gedmo\IpTraceable(on="update")
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Gedmo\Versioned()
     * @Groups({"ip_traceable:input", "ip_traceable:output"})
     */
    private $updatedFromIp;

    /**
     * @param string|null $createdFromIp
     * @return $this
     */
    public function setCreatedFromIp(?string $createdFromIp): self
    {
        $this->createdFromIp = $createdFromIp;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedFromIp(): ?string
    {
        return $this->createdFromIp;
    }

    /**
     * @param string|null $updatedFromIp
     * @return $this
     */
    public function setUpdatedFromIp(?string $updatedFromIp): self
    {
        $this->updatedFromIp = $updatedFromIp;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdatedFromIp(): ?string
    {
        return $this->updatedFromIp;
    }
}
