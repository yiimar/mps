<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Collection;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Yiimar
 */
class BaseCollection extends ArrayCollection
{
    public function getValue(): array
    {
        return $this->getValues();
    }
}