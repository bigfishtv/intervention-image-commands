<?php

namespace Intervention\Image\Imagick\Commands;

use Imagick;
use ImagickPixel;
use ImagickDraw;
use DrQue\PolynomialRegression;

class TankCurvesCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        bcscale(20);
        $curves = $this->argument(0)->value();
        $numPoints = count($curves);

        $img = $image->getCore();

        $gradient = new Imagick();
        $gradient->newImage(255, 1, new ImagickPixel('white'));

        $regression = new PolynomialRegression($numPoints);
        foreach ($curves as $point) {
            $regression->addData($point[0], $point[1]);
        }
        $coefficients = $regression->getCoefficients();

        for ($x = 0; $x < 255; ++$x) {
            $y = (int) max(0, min(255, $regression->interpolate($coefficients, $x)));
            $r = $g = $b = $y;
            $pixel = new ImagickPixel('rgb(' . $r . ',' . $g . ',' . $b . ')');

            $draw = new ImagickDraw();
            $draw->setFillColor($pixel);
            $draw->rectangle($x, 0, $x + 1, 1);

            $gradient->drawImage($draw);
        }

        $gradient->setImageInterpolateMethod(Imagick::INTERPOLATE_BILINEAR);
        
        $img->clutImage($gradient);

        return $image;
    }
}
