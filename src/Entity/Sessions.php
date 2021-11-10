<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sessions
 *
 * @ORM\Table(name="sessions", indexes={
 *     @ORM\Index(name="i_sess_id", columns={"sess_id"}),
 *     @ORM\Index(name="i_sess_time", columns={"sess_time"}),
 *     @ORM\Index(name="i_sess_lifetime", columns={"sess_lifetime"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 */
class Sessions
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(name="sess_id", type="string", length=128, nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sessId;

    /**
     * @var string
     *
     * @ORM\Column(name="sess_data", type="blob", nullable=false)
     */
    private $sessData;

    /**
     * @var int
     *
     * @ORM\Column(name="sess_time", type="bigint", nullable=false, options={"unsigned"=true})
     */
    private $sessTime;

    /**
     * @var int
     *
     * @ORM\Column(name="sess_lifetime", type="bigint", nullable=false, options={"unsigned"=true})
     */
    private $sessLifetime;

    public function getSessId(): ?string
    {
        return $this->sessId;
    }

    public function getSessTime(): ?string
    {
        return $this->sessTime;
    }

    public function setSessTime(string $sessTime): self
    {
        $this->sessTime = $sessTime;

        return $this;
    }

    public function getSessLifetime(): ?string
    {
        return $this->sessLifetime;
    }

    public function setSessLifetime(string $sessLifetime): self
    {
        $this->sessLifetime = $sessLifetime;

        return $this;
    }

    public function getSessData(): ?string
    {
        return $this->sessData;
    }

    public function setSessData(string $sessData): self
    {
        $this->sessData = $sessData;

        return $this;
    }

    public function setSessId(string $sessId): self
    {
        $this->sessId = $sessId;

        return $this;
    }
}
