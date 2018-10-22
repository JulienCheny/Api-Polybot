<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="answer")
 * @ApiResource
 */
class Answer
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
     * @var string A text
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $text = '';

    /**
     * @var string A value
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $value = '';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Request", cascade={"persist"})
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id", nullable=false)
     */
    private $request;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department", cascade={"persist"})
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id", nullable=false)
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Year", cascade={"persist"})
     * @ORM\JoinColumn(name="year_id", referencedColumnName="id", nullable=false)
     */
    private $year;


    public function getId(): int
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

    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return Answer
     */
    public function setValue($value)
    {
        $this->value = $value;

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
