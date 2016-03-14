<?php

namespace Intervention\Image\Imagick\Commands;
use \Imagick;

class TankVibranceCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {	
        $img = $image->getCore();
        $vibrance = $this->argument(0)->value() ?: 0;

        $amt = $vibrance/200;

        $img->recolorImage([
			1+$amt,			-$amt/1.5,		-$amt/1.5,		0,
			-$amt/1.5,		1+$amt,			-$amt/1.5,		0,
			-$amt/1.5,		-$amt/1.5,		1+$amt,			0,
			-$amt/1.5,		-$amt/1.5,		-$amt/1.5,		1+$amt,
		]);

		return $image;
    }
}