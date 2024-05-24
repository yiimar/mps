<?php declare(strict_types=1);

namespace App\Domain\Model\Shop\Category\Entity\Good\Entity;

use App\Domain\Model\Shop\Category\Entity\Category;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;

#[ORM\Entity(repositoryClass: GoodRepository::class)]
class Good
{
    /**
     * Идентификатор.
     *
     * @var \App\Domain\Model\Shop\Category\Entity\Good\Entity\GoodId
     */
    #[ORM\Id]
    #[ORM\Column(type: GoodIdType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    private GoodId $id;

    /**
     * Имя сервиса
     *
     * @var \App\Domain\Model\Shop\Category\Entity\Good\Entity\GoodName
     */
    #[ORM\Column(type: GoodNameType::NAME, length: 255)]
    private GoodName $name;

    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "id", nullable: false)]
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "goods")]
    private Category $category;

    /**
     * @param \App\Domain\Model\Shop\Category\Entity\Good\Entity\GoodName $name
     * @param \App\Domain\Model\Shop\Category\Entity\Category $category
     */
    public function __construct(GoodName $name, Category $category)
    {
        $this->name = $name;
        $this->category = $category;
    }

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

    /**
     * @return \App\Domain\Model\Shop\Category\Entity\Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }
}
