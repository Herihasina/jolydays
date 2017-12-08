<?php

namespace JD\JolydaysBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="JD\JolydaysBundle\Repository\CategoryRepository")
 */
class Category
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @ORM\ManyToMany(targetEntity="JD\JolydaysBundle\Entity\Posts", mappedBy="categories")
     */
    private $posts;

    public function getPosts(){
        return $this->posts;
    }

    public function __construct() {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add post
     *
     * @param \JD\JolydaysBundle\Entity\Posts $post
     *
     * @return Category
     */
    public function addPost(\JD\JolydaysBundle\Entity\Posts $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \JD\JolydaysBundle\Entity\Posts $post
     */
    public function removePost(\JD\JolydaysBundle\Entity\Posts $post)
    {
        $this->posts->removeElement($post);
    }
}
