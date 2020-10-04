<?php


namespace App\Services\Images;


use Imagick;
use ImagickException;

class ImagickTrimmer
{
    /**
     * Remove edges from the image
     *
     * @param string $image
     * @return Imagick
     * @throws ImagickException
     */
    public function trimImage(string $image): Imagick
    {
        $imagick = new Imagick();

        if (!$imagick->readImageBlob($image)) {
            throw new ImagickException('Cannot read quote image blob.');
        }

        if (!$imagick->trimImage(0)) {
            throw new ImagickException('Cannot trim quote image.');
        }

        return $imagick;
    }
}