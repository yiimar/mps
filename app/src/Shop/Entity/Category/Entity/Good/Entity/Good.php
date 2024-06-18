<?php declare(strict_types=1);

namespace App\Shop\Entity\Category\Entity\Good\Entity;

use App\Shop\Entity\Category\Entity\Category;
use App\Shop\Entity\Order\Entity\Order;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;

#[ORM\Entity(repositoryClass: GoodRepository::class)]
#[ORM\Table(name: 'good')]
class Good
{
    /**
     * Идентификатор.
     *
     * @var GoodId
     */
    #[ORM\Id]
    #[ORM\Column(type: GoodIdType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    private GoodId $id;

    /**
     * Имя сервиса
     *
     * @var GoodName
     */
    #[ORM\Column(type: GoodNameType::NAME, length: 255)]
    private GoodName $name;

    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "id", nullable: false)]
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "goods")]
    private Category $category;

    #[ORM\JoinColumn(name: "order_id", referencedColumnName: "id", nullable: false)]
    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "goods")]
    private ?Order $order;

    /**
     * @param GoodName $name
     * @param \App\Shop\Entity\Category\Entity\Category $category
     */
    public function __construct(GoodName $name, Category $category, ?Order $order)
    {
        $this->name = $name;
        $this->category = $category;
        $this->order = $order ?? null;
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
     * @return \App\Shop\Entity\Category\Entity\Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }
}
