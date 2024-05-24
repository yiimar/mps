<?php declare(strict_types=1);

namespace App\Domain\Model\Shop\Category\Entity;

use App\Domain\Model\Shop\Category\Entity\Good\Entity\Good;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    /**
     * Идентификатор.
     *
     * @var \App\Domain\Model\Shop\Category\Entity\CategoryId
     */
    #[ORM\Id]
    #[ORM\Column(type: CategoryIdType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    private CategoryId $id;

    /**
     * Имя категории
     *
     * @var \App\Domain\Model\Shop\Category\Entity\CategoryName
     */
    #[ORM\Column(type: CategoryNameType::NAME, length: 255)]
    private CategoryName $name;

    /**
     * Товары в категории
     *
     * @var \Doctrine\Common\Collections\Collection|null
     */
    #[ORM\OneToMany(targetEntity:Good::class, mappedBy: "category")]
    private ?Collection $goods;

    /**
     * @param \App\Domain\Model\Shop\Category\Entity\CategoryName $name
     */
    public function __construct(CategoryName $name)
    {
        $this->name = $name;
        $this->goods = new ArrayCollection();
    }

    public function getId(): CategoryId
    {
        return $this->id;
    }

    public function getName(): CategoryName
    {
        return $this->name;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|null
     */
    public function getGoods(): ?Collection
    {
        return $this->goods;
    }

    /**
     * @param \App\Domain\Model\Shop\Category\Entity\Good\Entity\Good $good
     * @return $this
     */
    public function addGood(Good $good): self
    {
        if (!$this->goods->contains($good)) {
            $this->goods->add($good);
        }
        return $this;
    }

    /**
     * @param \App\Domain\Model\Shop\Category\Entity\Good\Entity\Good $good
     * @return bool
     */
    public function removeGood(Good $good): bool
    {
        return $this->goods->removeElement($good);
    }
}
