<?php

namespace App\Entity;

use App\Repository\MovementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovementRepository::class)]
class Movement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $player_id = null;

    #[ORM\Column]
    private ?int $movement = null;

    #[ORM\Column(nullable: true)]
    private array $oldmovments = [];

    #[ORM\Column(nullable: true)]
    private ?int $game_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $remarks = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerId(): ?int
    {
        return $this->player_id;
    }

    public function setPlayerId(?int $player_id): self
    {
        $this->player_id = $player_id;

        return $this;
    }

    public function getMovement(): ?int
    {
        return $this->movement;
    }

    public function setMovement(int $movement): self
    {
        $this->movement = $movement;

        return $this;
    }

    public function getOldmovments(): array
    {
        return $this->oldmovments;
    }

    public function setOldmovments(?array $oldmovments): self
    {
        $this->oldmovments = $oldmovments;

        return $this;
    }

    public function getGameId(): ?int
    {
        return $this->game_id;
    }

    public function setGameId(?int $game_id): self
    {
        $this->game_id = $game_id;

        return $this;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function setRemarks(?string $remarks): self
    {
        $this->remarks = $remarks;

        return $this;
    }
}
