<?php
/**
 * File uploader class which moves upload files to the specified directory.
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Start of the file uploader class
 * Class FileUploader
 * @package App\Service
 */
class FileUploader
{
    /**
     * Variable to store the target directory
     * @var
     */
    private $targetDirectory;

    /**
     * FileUploader constructor
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Uploads the the image file to the pre-defined directory and
     * generates a unique name for it
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    /**
     * Gets the pre-defined directory to store the image file
     * @return mixed
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}