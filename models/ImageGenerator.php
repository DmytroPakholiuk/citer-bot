<?php

namespace app\models;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use yii\db\Exception;

class ImageGenerator extends \yii\base\Model
{

    /**
     * @param Professor $professor
     * @param string $content
     * @return void
     *
     * @throws \Exception
     */
    public function generateCiteImageGD(Professor $professor, string $content)
    {
        /**
         * @var ProfessorImage $imageRecord
         */
        $imageRecord = $professor->mainImage;

        try {
            $image = imagecreate(1600, 800);

            $profImage = imagecreatefromjpeg($imageRecord->getFilePath());

//            imageresolution($profImage, 600, 800);

            $red = imagecolorallocate($image, 230, 60,60);
            $redish = imagecolorallocate($image, 255, 200,200);

            imagetruecolortopalette($profImage, false, 4);
            $this->pixelateToSize($profImage, 60, 80);

//            $white = imagecolorallocate($image, 255, 255, 255);

            imagefilledrectangle($image, 600, 0, 1600, 600, $red);
            imagefilledrectangle($image, 600, 600, 1600, 800, $redish);
//            imagecopymerge($image, $profImage, 0, 0, 0, 0, 600, 800, 100);
            imagecopyresampled($image, $profImage, 0, 0, 0, 0, 600, 800, 112, 150);

//            imagettftext(
//                $image, 100, 0, 650, 750,
//                $red,
//                'web/fonts/ubuntu.regular.ttf', $professor->getInitials()
//            );
//
//            imagettftext(
//                $image, 80, 0, 650, 100,
//                $white,
//                'web/fonts/ubuntu.regular.ttf', $content
//            );

            var_dump(imagejpeg($image, 'web/images/output/outputGD.jpeg')); ;

            imagecolordeallocate($image, $red);
            imagecolordeallocate($image, $redish);
            imagedestroy($image);

        } catch (\Throwable $e) {
            var_dump($e);
//            throw new \Exception('could not create image');
        }
    }

    public function generateCiteImageImagick(Professor $professor, string $content)
    {
        /**
         * @var ProfessorImage $imageRecord
         */
        $imageRecord = $professor->mainImage;

        try {
            $draw = new ImagickDraw();
            $imagick = new Imagick('web/images/output/outputGD.jpeg');

//            $palette = $this->generatePalette();
//            $imagick->clutImage($palette);

            $draw->setTextAlignment(Imagick::ALIGN_CENTER);
            $draw->setFont('web/fonts/pureprog-mono-normal.ttf');
            $draw->setFontSize(150);
            $draw->annotation(1100, 750, $professor->getInitials());
            $draw->setFontSize(80);
            $draw->annotation(1100, 100, $content);



            $imagick->drawImage($draw);

            $imagick->writeImage('web/images/output/outputIM.jpeg');

//            $image = $imagick->newImage(
//                1600, 800, new ImagickPixel('white')
//            );
        } catch (\Throwable $e) {
            var_dump($e->getTrace());
        }




    }

    public function addBreaks(string $text, int $length = 30): string
    {
        $textArr = explode(' ', $text);
        $newTextArr = [[]];

        $lineLength = 0;
        $i = 0;

        foreach ($textArr as $word){
            if ($lineLength + mb_strlen($word) + 1 > $length && $lineLength != 0){
                $i++;
                $newTextArr[$i] = [];
                $lineLength = 0;
            }
            $newTextArr[$i][] = $word;
            $lineLength += mb_strlen($word) + 1;
        }

        $result = '';
        foreach ($newTextArr as $line){
            $result .= implode(' ', $line);
            $result .= "\n";
        }

        return $result;
    }

    /*
    * pixelate_x - the size of "pixelate" effect on X axis (default 10)
    * pixelate_y - the size of "pixelate" effect on Y axis (default 10)
    */
    function pixelate(\GdImage $image, $pixelate_x = 10, $pixelate_y = 10): \GdImage
    {
        // check if the input file exists
//        if(!file_exists($image))
//            echo 'File "'. $image .'" not found';

        // get the input file extension and create a GD resource from it
//        $ext = pathinfo($image, PATHINFO_EXTENSION);
//        if($ext == "jpg" || $ext == "jpeg")
//            $img = imagecreatefromjpeg($image);
//        elseif($ext == "png")
//            $img = imagecreatefrompng($image);
//        elseif($ext == "gif")
//            $img = imagecreatefromgif($image);
//        else
//            echo 'Unsupported file extension';

        // now we have the image loaded up and ready for the effect to be applied
        // get the image size
//        $size = getimagesize($image);
        $height = imagesy($image);
        $width = imagesx($image);

        // start from the top-left pixel and keep looping until we have the desired effect
        for($y = 0;$y < $height;$y += $pixelate_y+1)
        {

            for($x = 0;$x < $width;$x += $pixelate_x+1)
            {
                // get the color for current pixel
                $rgb = imagecolorsforindex($image, imagecolorat($image, $x, $y));

                // get the closest color from palette
                $color = imagecolorclosest($image, $rgb['red'], $rgb['green'], $rgb['blue']);
                imagefilledrectangle($image, $x, $y, $x+$pixelate_x, $y+$pixelate_y, $color);
            }
        }

        // save the image
//        $output_name = $output .'_'. time() .'.jpg';
//
//        imagejpeg($img, $output_name);
//        imagedestroy($img);
        return $image;
    }

    function pixelateToSize(\GdImage $image, $newX, $newY): \GdImage
    {
        $height = imagesy($image);
        $width = imagesx($image);

        return $this->pixelate($image, $height/$newY, $width/$newX);
    }


    public function generatePalette(): Imagick
    {
        $draw = new ImagickDraw();
        $palette = new Imagick();
        $palette->newImage(6, 1, new ImagickPixel('white'));

        $draw->setFillColor(new ImagickPixel('#d92929'));
        $draw->color(1, 0, Imagick::PAINT_POINT);

        $draw->setFIllColor(new ImagickPixel('#913939'));
        $draw->color(2, 0, Imagick::PAINT_POINT);

        $draw->setFillColor(new ImagickPixel('#c97979'));
        $draw->color(3, 0, Imagick::PAINT_POINT);

        $draw->setFillColor(new ImagickPixel('#7d6565'));
        $draw->color(4, 0, Imagick::PAINT_POINT);

        $draw->setFillColor(new ImagickPixel('#542525'));
        $draw->color(5, 0, Imagick::PAINT_POINT);

        $palette->drawImage($draw);
        $palette->writeImage('web/images/palettes/palette.jpeg');

        return $palette;

    }

}