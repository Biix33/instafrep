<?php


namespace App\Entity;


use DateTimeInterface;

interface ModelInterface
{
    /**
     * Get the model's unique identifier
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Get the model's creation date
     * This date should be be set only once and can't be updated
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface;

    /**
     * Get the model's last update date
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface;

    /**
     * Define the model's last update date
     * @param DateTimeInterface $date
     * @return Model the model instance ($this)
     */
    public function setUpdatedAt(DateTimeInterface $date): self;
}