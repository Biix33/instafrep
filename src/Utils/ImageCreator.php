<?php


namespace App\Utils;



class ImageCreator
{
    /**
     * Create a random star image
     * @param int $width
     * @param int $height
     * @return bool
     */
    public function getImage(int $width = 300, int $height = 300)
    {
        // Créé une ressource en mémoire
        $img = imagecreate($width, $height);
        imagecolorallocate($img, rand(0, 255),rand(0, 255),rand(0, 255));
        $randColor = imagecolorallocate($img, rand(0, 255),rand(0, 255),rand(0, 255));
        $randColor2 = imagecolorallocatealpha($img, rand(0, 255),rand(0, 255),rand(0, 255), rand(0,126));
        imagefilledrectangle($img,25,25,275,275, $randColor);
        imagefilledpolygon($img, array(
            150, 25,
            200, 100,
            275, 100,
            225, 175,
            275, 275,
            150, 225,
            25, 275,
            75, 175,
            25, 100,
            100, 100
        ), 10, $randColor2);

        // Créé un fichier jpeg à partir de la ressource
        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        return $png;
    }
}