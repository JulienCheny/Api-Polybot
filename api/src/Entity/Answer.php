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
 * @ORM\Table(name="answer")
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={"granswer"}}
 *      }
 * )
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "request.uuid": "exact", "department.name": "exact", "year.name": "exact"})
 */
class Answer
{
    /**
     * @var int The entity Id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"granswer"})
     */
    private $id;

    /**
     * @var string A text
     *
     * @ORM\Column(type="string")
     * @Groups({"granswer"})
     * @Assert\NotBlank
     */
    private $text = '';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Request", cascade={"persist"})
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id", nullable=false)
     * @Groups({"granswer"})
     */
    private $request;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department", cascade={"persist"})
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id", nullable=true)
     * @Groups({"granswer"})
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Year", cascade={"persist"})
     * @ORM\JoinColumn(name="year_id", referencedColumnName="id", nullable=true)
     * @Groups({"granswer"})
     */
    private $year;


    public function getId()
    {
        return $this->id;
    }

    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text.
     *
     * @param string $text
     *
     * @return Answer
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Set request.
     *
     * @param \App\Entity\Request $request
     *
     * @return Answer
     */
    public function setRequest(\App\Entity\Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request.
     *
     * @return \App\Entity\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set department.
     *
     * @param \App\Entity\Department $department
     *
     * @return Answer
     */
    public function setDepartment(\App\Entity\Department $department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department.
     *
     * @return \App\Entity\Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set year.
     *
     * @param \App\Entity\Year $year
     *
     * @return Answer
     */
    public function setYear(\App\Entity\Year $year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year.
     *
     * @return \App\Entity\Year
     */
    public function getYear()
    {
        return $this->year;
    }
}
