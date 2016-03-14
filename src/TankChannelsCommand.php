<?php

namespace Intervention\Image\Imagick\Commands;

use \Imagick;
use \ImagickPixel;

class TankChannelsCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {	
        $img = $image->getCore();
        $channels = $this->argument(0)->value();
        foreach($channels as $key => &$value) {
        	$value = $value/100;
        }

        $r = isset($channels['red']) ? $channels['red'] : 0;
        $g = isset($channels['green']) ? $channels['green'] : 0;
        $b = isset($channels['blue']) ? $channels['blue'] : 0;

		$img->recolorImage([
			1,		0,		0,		$r,
			0,		1,		0,		$g,
			0,		0,		1,		$b,	
			0,		0,		0,		1,
		]);
		
		return $image;
    }
}