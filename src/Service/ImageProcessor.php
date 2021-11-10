<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageProcessor
{
    /** @var ContainerInterface */
    private $container;
    /** @var string */
    private $uploadDirectory;
    /** @var string */
    private $binDirectory;
    /** @var string */
    private $assetUploadedDirectory;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        /** @var string $uploadDirectory */
        $uploadDirectory = $this->container->getParameter("uploadDirectory");
        $this->uploadDirectory = $uploadDirectory;
        /** @var string $binDirectory */
        $binDirectory = $this->container->getParameter("binDirectory");
        $this->binDirectory = $binDirectory;
        /** @var string $assetUploadedDirectory */
        $assetUploadedDirectory = $this->container->getParameter("assetUploadedDirectory");
        $this->assetUploadedDirectory = $assetUploadedDirectory;
    }

    /**
     * @param UploadedFile $file
     * @return string|null
     */
    public function saveImage(UploadedFile $file): ?string
    {
        try {
            $fileName = md5(uniqid() . (new \DateTime())->getTimestamp());
            $fileType = $file->guessExtension();

            if (!is_null($fileType)){
                $file->move($this->uploadDirectory, $fileName . "." . $fileType);
                if (array_search($fileType, ["jpg", "jpeg", "png"]) > -1) {
                    $aaa = exec("php " . $this->binDirectory . "/console liip:imagine:cache:resolve " . $this->assetUploadedDirectory . "/" . $fileName . "." . $fileType);
                    if ($aaa != "") {
                        exec("php " . $this->binDirectory . "/console liip:imagine:cache:remove " . $this->assetUploadedDirectory . "/" . $fileName . "." . $fileType);
                        unlink($this->uploadDirectory . "/" . $fileName);
                        return null;
                    }
                }
                return $fileName . "." . $fileType;
            }else{
                return null;
            }
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function deleteImage(string $fileName): bool
    {
        try {
            unlink($this->uploadDirectory . "/" . $fileName);
            $explode = explode(".", $fileName);
            if (is_array($explode)) {
                if (array_search($explode[array_key_last($explode)], ["jpg", "jpeg", "png"]) > -1) {
                    exec("php " . $this->binDirectory . "/console liip:imagine:cache:remove " . $this->assetUploadedDirectory . "/" . $fileName);
                }
            }
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}