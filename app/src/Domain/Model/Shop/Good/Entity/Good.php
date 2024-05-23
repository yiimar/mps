<?php declare(strict_types=1);

namespace App\Domain\Model\Shop\Good\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;

#[ORM\Entity(repositoryClass: GoodRepository::class)]
class Good
{
    /**
     * Идентификатор.
     *
     * @var \App\Domain\Model\Shop\Good\Entity\GoodId
     */
    #[ORM\Id]
    #[ORM\Column(type: GoodIdType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    private GoodId $id;

    /**
     * Имя сервиса
     *
     * @var \App\Domain\Model\Shop\Good\Entity\GoodName
     */
    #[ORM\Column(type: GoodNameType::NAME, length: 255)]
    private GoodName $name;

    public function getId(): GoodId
    {
        return $this->id;
    }

    public function getName(): GoodName
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = new GoodName($name);

        return $this;
    }
}
