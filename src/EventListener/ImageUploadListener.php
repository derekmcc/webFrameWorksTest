<?php
/**
 * Image uploader class, used for uploading images.
 */

namespace App\EventListener;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Recipe;
use App\Entity\Review;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Start of the image upload class
 * Class ImageUploadListener
 * @package App\EventListener
 */
class ImageUploadListener
{
    /**
     * Variable to store uploaded file
     * @var FileUploader
     */
    private $uploader;

    /**
     * ImageUploadListener constructor
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Gets the entity uploading an image and assigns it to uploadFile
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Gets the entity uploading an image and assigns it to uploadFile
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Uploads the the file and sets the entities image to the uploaded file
     * @param $entity
     */
    private function uploadFile($entity)
    {
        // upload only works for Recipe entities
//        if (!$entity instanceof Recipe) {
//            return;
//        }

        $file = $entity->getImage();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setImage($fileName);
        }
    }
}