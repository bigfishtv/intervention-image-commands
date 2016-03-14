<?php

namespace Intervention\Image\Imagick\Commands;

class TankContrastCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {	
		$img = $image->getCore();
        $contrast = $this->argument(0)->value();	

        $amt = 75;

		$image->camanCurves([
			[0,		0],
			[$amt, 	0+$amt-$contrast],
			[180,	255-$amt+$contrast],
			[255,	255]
		]);

		return $image;
    }
}