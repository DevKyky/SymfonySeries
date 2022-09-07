<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Upload
{
    public function saveFile(UploadedFile $file, string $name = null, string $directory)
    {
        $newFilename = $name . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($directory, $newFilename);
        } catch (FileException $e) {
            dump($e->getMessage());
        }

        return $newFilename;
    }
}