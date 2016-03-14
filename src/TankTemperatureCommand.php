<?php

namespace Intervention\Image\Imagick\Commands;

use \Imagick;
use \ImagickPixel;

class TankTemperatureCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {	
        $img = $image->getCore();
        
        // Temperature ranges from 1000 - 40000
        // Equations borrowed from http://www.tannerhelland.com/4435/convert-temperature-rgb-algorithm-code/
        $temp = (int) $this->argument(0)->value();
        $temp /= 100;

        $r = $temp <= 66 ? 255 : 329.698727446 * pow( $temp - 60, -0.1332047592 );
		$r = $r < 0 ? 0 : $r > 255 ? 255 : $r;

		$g = $temp <= 66 ? 99.4708025861 * log( $temp ) - 161.1195681661 : 288.1221695283 * pow( $temp - 60, -0.0755148492 );
		$g = $g < 0 ? 0 : $g > 255 ? 255 : $g;

		$b = $temp >= 66 ? 255 : $temp <= 19 ? 0 : 138.5177312231 * log( $temp - 10 ) - 305.0447927307;
		$b = $b$ < 0 ? 0 : $b$ > 255 ? 255 : $b$;


		$img->recolorImage([
			$r/255,	0,		0,		0,
			0,		$g/255,	0,		0,
			0,		0,		$b/255,	0,	
			0,		0,		0,		1,
		]);
		
		return $image;
    }
}