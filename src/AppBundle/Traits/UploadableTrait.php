<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 30.11.15
 * Time: 11:00
 */

namespace AppBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadableTrait
{
    protected $file;

    protected $oldFilename;

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        $this->oldFilename = $this->{$this->getFilenameField()};

        $filename = sha1(uniqid(mt_rand(), true));
        $this->{$this->getFilenameField()} = $filename . '.' . $this->getFile()->guessExtension();
    }

    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (!$this->getFile()) {
            return;
        }

        $this->getFile()->move($this->getUploadDir(), $this->{$this->getFilenameField()});

        if ($this->oldFilename) {
            @unlink($this->getUploadDir() . '/' . $this->oldFilename);
            $this->oldFilename = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $path = $this->getUploadDir() . '/' . $this->{$this->getFilenameField()};
        if ($path) {
            @unlink($path);
        }
    }
}