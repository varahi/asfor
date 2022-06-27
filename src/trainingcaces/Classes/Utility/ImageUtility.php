<?php
namespace T3Dev\Trainingcaces\Utility;

class ImageUtility
{

    /**
     * @param string $imageFile
     *
     * @return array
     */
    public static function getImageSize($imageFile)
    {
        $size = @getimagesize($imageFile);

        if ($size === false) {
            return false;
        }

        $exif = @exif_read_data($imageFile);

        $width = $size[ 0 ];
        $height = $size[ 1 ];

        // see: http://sylvana.net/jpegcrop/exif_orientation.html
        if (isset($exif[ 'Orientation' ]) && $exif[ 'Orientation' ] >= 5 && $exif[ 'Orientation' ] <= 8) {
            return [$height, $width];
        }

        return [$width, $height];
    }

    /**
     * @param $rotateFilename
     * @param $fileName
     * @param $degrees
     * @return void
     */
    public static function rotateImage($rotateFilename, $fileName, $degrees)
    {

        //$degrees = 90;
        $fileType = strtolower(substr($fileName, strrpos($fileName, '.') + 1));

        if ($fileType == 'png') {
            header('Content-type: image/png');
            $source = \imagecreatefrompng($rotateFilename);
            $bgColor = \imagecolorallocatealpha($source, 255, 255, 255, 127);
            // Rotate
            $rotate = \imagerotate($source, $degrees, $bgColor);
            \imagesavealpha($rotate, true);
            \imagepng($rotate, $rotateFilename);
        }

        if ($fileType == 'jpg' || $fileType == 'jpeg') {
            header('Content-type: image/jpeg');
            $source = \imagecreatefromjpeg($rotateFilename);
            // Rotate
            $rotate = \imagerotate($source, $degrees, 0);
            \imagejpeg($rotate, $rotateFilename);
        }

        // Free the memory
        \imagedestroy($source);
        \imagedestroy($rotate);
    }
}
