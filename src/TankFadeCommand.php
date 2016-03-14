<?php

namespace Intervention\Image\Imagick\Commands;

class TankFadeCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {	
		$img = $image->getCore();
        $fade = $this->argument(0)->value();	

		$image->camanCurves([
			[0,		$fade],
			[255,	255 - $fade/2]
		]);

		return $image;
    }
}
