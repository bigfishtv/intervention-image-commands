<?php

namespace Intervention\Image\Imagick\Commands;

use \Imagick;
use \ImagickPixel;
use \ImagickDraw;

use \DrQue\PolynomialRegression;

class CamanCurvesCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
		bcscale(20);
        $curves = $this->argument(0)->value();
        // $channel = $this->argument(1)->value() ?: Imagick::CHANNEL_ALL;
        $numPoints = count($curves);

		$img = $image->getCore();

		$gradient = new Imagick();
		$gradient->newImage(255, 1, new ImagickPixel('white'));



		$regression = new PolynomialRegression($numPoints);
		foreach ($curves as $point) {
			$regression->addData($point[0], $point[1]);
		}
		$coefficients = $regression->getCoefficients();



		for ($x = 0; $x < 255; $x++) {
			$y = (int) max(0, min(255, $regression->interpolate($coefficients, $x)));
			// $y = (int) max(0, min(255, getCubicBezierY($curves, $x)));
			$r = $g = $b = $y;
			// if($channel == Imagick::CHANNEL_RED) $g = $b = $x;
			// if($channel == Imagick::CHANNEL_GREEN) $r = $b = $x;
			// if($channel == Imagick::CHANNEL_BLUE) $g = $b = $x;

			$pixel = new ImagickPixel('rgb('.$r.','.$g.','.$b.')');
			$draw = new ImagickDraw();
			$draw->setFillColor($pixel);
			$draw->rectangle($x, 0, $x + 1, 1);
			$gradient->drawImage($draw);
		}

		$img->setImageAlphaChannel(Imagick::ALPHACHANNEL_DEACTIVATE);
		$gradient->setImageInterpolateMethod(Imagick::INTERPOLATE_BILINEAR);

		$img->clutImage($gradient);

        return $image;
    }
}

// http://stackoverflow.com/questions/8217346/cubic-bezier-curves-get-y-for-given-x
function getCubicBezierY($points, $x) {
	$x = $x/255;
	$value = pow((1 - $x), 3) * $points[0][0]/255 + 3*pow((1 - $x), 2) * $x * $points[1][0]/255 + 3*(1 - $x) * pow($x, 2) * $points[2][0]/255 + pow($x, 3) * $points[3][0]/255;
	return $value * 255;
}