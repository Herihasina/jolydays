<?php

namespace JD\JolydaysBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Illustrations
 *
 * @ORM\Table(name="illustrations")
 * @ORM\Entity(repositoryClass="JD\JolydaysBundle\Repository\IllustrationsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Illustrations
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
     * @ORM\Column(name="src", type="string", length=255)
     */
    private $src;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;


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
     * Set src
     *
     * @param string $src
     *
     * @return Illustrations
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Illustrations
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    private $file;

    private $saveName;

    public function setFile(UploadedFile $file)
    {
      $this->file = $file;

      // Check if a file has already been uploaded
      // => temporarily save file name and initialise attributes
      if (null !== $this->src) {
        $this->saveName = $this->src; 
        $this->src = null;
        $this->alt = null; 
      }
    }

    public function getFile() {
      return $this->file;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpload()
    {
      // if no uploaded file, return
      if (null === $this->file) {
        return;
      }
      
      // get file extension
      $this->src = $this->file->guessExtension();

      // same name as original
      $this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function upload()
    {
      // if no uploaded file, return
      if (null === $this->file) {
        return;
      }

      // if already has file, delete ancient file
      if (null !== $this->saveName) {
        $ancientFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->saveName;
        if (file_exists($ancientFile)) {
          unlink($ancientFile);
        }
      }
      // move new file to upload root directory as id.src
      $this->file->move( $this->getUploadRootDir(), $this->id.'.'.$this->src );
    }

    /**
     * @ORM\PreRemove
     */
    public function preRemoveUpload()
    {
      // save name (remove = remove id)
      $this->saveName = $this->getUploadRootDir().'/'.$this->id.'.'.$this->src;
    }

    /**
     * @ORM\PostRemove
     */
    public function removeUpload()
    {
      // delete file definitely by its name
      if (file_exists($this->saveName)) {
        unlink($this->saveName);
      }
    }

    public function getUploadDir()
    {
      return 'uploads/img';
    }

    public function getWebDir(){
      return $this->getUploadDir().'/'.$this->getId().'.'.$this->getSrc();
    }

    protected function getUploadRootDir()
    {
      return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

}

