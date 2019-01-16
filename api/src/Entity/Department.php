<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="department")
 * @ApiResource
 * @ApiFilter(SearchFilter::class, properties={"name": "exact"})
 */
class Department
{
    /**
     * @var int The entity Id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string A dept name
     *
     * @ORM\Column(type="string")
     * @Groups({"granswer"})
     * @Assert\NotBlank
     */
    public $name = '';

    public function getId(): int
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Department
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
