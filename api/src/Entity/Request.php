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
 * @ORM\Table(name="request")
 * @ApiResource
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "uuid": "exact", "name": "exact"})
 */

/************
 * @ApiResource(
 *     attributes={"access_control"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"},
 *     collectionOperations={
 *         "post"={"access_control"="is_granted('ROLE_USER')"},
           "get"={"access_control"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"}
 *     },
 *     itemOperations={
           "get"={"access_control"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"},
 *         "put"={"access_control"="is_granted('ROLE_USER')"},
 *         "delete"={"access_control"="is_granted('ROLE_USER')"}
 *     }
 */
class Request
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
     * @var string A unique identifier
     *
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"granswer"})
     * @Assert\NotBlank
     */
    private $uuid = '';

    /**
     * @var string A Request name
     *
     * @ORM\Column(type="string")
     * @Groups({"granswer"})
     * @Assert\NotBlank
     */
    private $name = '';

    /**
     * @var string A Request name
     *
     * @ORM\Column(type="string", nullable = true)
     * @Groups({"granswer"})
     */
    private $textResponse = '';

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set name.
     *
     * @param string $uuid
     *
     * @return Request
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
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
     * @return Request
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getTextResponse()
    {
        return $this->textResponse;
    }

    /**
     * Set textResponse.
     *
     * @param string $textResponse
     *
     * @return Request
     */
    public function setTextResponse($textResponse)
    {
        $this->textResponse = $textResponse;

        return $this;
    }
}
