<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 30.11.15
 * Time: 10:59
 */

namespace AppBundle\Interfaces;

interface UploadableInterface
{
    public function getFilenameField();

    public function getUploadDir();
}