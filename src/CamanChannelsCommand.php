<?php

namespace Intervention\Image\Imagick\Commands;

use \Imagick;
use \ImagickPixel;

class CamanChannelsCommand extends \Intervention\Image\Commands\AbstractCommand
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
			1,		0,		0,		$r,		0,
			0,		1,		0,		$g,		0,
			0,		0,		1,		$b,		0,
			0,		0,		0,		1,		0,
			0,		0,		0,		0,		0,
		]);
		return $img;
        
        /*

        // this matches caman's exact pixel filter but takes a ridiculous amount of time to render
        $iterator = $img->getPixelIterator();
        foreach ($iterator as $row => $pixels) {
        	foreach ($pixels as $column => $pixel) {

        		$color = $pixel->getColor();
				$r = $color['r'];
				$g = $color['g'];
				$b = $color['b'];

				if(isset($channels['red'])) {
					if($channels['red'] > 0) {
						$r += (255 - $r) * $channels['red'];
					}else{
						$r -= $r * abs($channels['red']);
					}
				}
				if(isset($channels['green'])) {
					if($channels['green'] > 0) {
						$g += (255 - $g) * $channels['green'];
					}else{
						$g -= $g * abs($channels['green']);
					}
				}	
				if(isset($channels['blue'])) {
					if($channels['blue'] > 0) {
						$b += (255 - $b) * $channels['blue'];
					}else{
						$b -= $b * abs($channels['blue']);
					}
				}

				$hex = '#' . sprintf('%02x', min(255, $r)) . sprintf('%02x', min(255, $g)) . sprintf('%02x', min(255, $b));
				$pixel->setColor($hex);
        	}
       		$iterator->syncIterator();
        }
        */

        return $img;
    }
}