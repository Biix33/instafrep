<?php


namespace App\Entity;


use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass()*/
abstract class Model implements ModelInterface
{
    /**
     * Get the model's unique identifier
     * @return int|null
     */
    public function getId(): ?int
    {
        // TODO: Implement getId() method.
    }

    /**
     * Get the model's creation date
     * This date should be be set only once and can't be updated
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        // TODO: Implement getCreatedAt() method.
    }

    /**
     * Get the model's last update date
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        // TODO: Implement getUpdatedAt() method.
    }

    /**
     * Define the model's last update date
     * @param DateTimeInterface $date
     * @return ModelInterface the model instance ($this)
     */
    public function setUpdatedAt(DateTimeInterface $date): ModelInterface
    {
        // TODO: Implement setUpdatedAt() method.
    }
}