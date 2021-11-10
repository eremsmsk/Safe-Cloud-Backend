<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;


class UploadedBase64File extends UploadedFile
{

    /**
     * @param string $base64String
     * @param string $originalName
     */
    public function __construct(string $base64String, string $originalName)
    {
        /** @var string $filePath */
        $filePath = tempnam(sys_get_temp_dir(), 'UploadedFile');
        /** @var string $data */
        $data = base64_decode($base64String);
        file_put_contents($filePath, $data);
        $error = null;
        $mimeType = null;
        $test = true;

        parent::__construct($filePath, $originalName, $mimeType, $error, $test);
    }

}
