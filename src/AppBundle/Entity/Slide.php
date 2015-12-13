<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 13.12.15
 * Time: 17:47
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Traits\UploadableTrait;
use AppBundle\Interfaces\UploadableInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\SlideRepository")
 * @ORM\Table(name="slide")
 * @ORM\HasLifecycleCallbacks
 */
class Slide implements UploadableInterface
{
    use UploadableTrait;

    const UPLOAD_DIR = '/uploads/slide';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $visible = true;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $filename;

    /**
     * @ORM\Column(type="integer", name="`order`")
     */
    protected $order = 0;

    public function __toString()
    {
        return 'Слайд';
    }

    public function getFilenameField()
    {
        return 'filename';
    }

    public function getUploadDir()
    {
        return WEB_DIR . self::UPLOAD_DIR;
    }

    public function getPath()
    {
        if ($this->filename) {
            return $this->getUploadDir() . '/' . $this->filename;
        }
    }

    public function getWebPath()
    {
        if ($this->filename) {
            return self::UPLOAD_DIR . '/' . $this->filename;
        }
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Slide
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Slide
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return Slide
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }
}
