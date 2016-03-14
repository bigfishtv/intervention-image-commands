<?php

namespace Intervention\Image\Imagick\Commands;

use \Imagick;
use \ImagickPixel;

class TankGrainCommand extends \Intervention\Image\Commands\AbstractCommand
{	
	public function execute($image)
    {	
        // I'd like to modify this to have more control over the grain 
        // but this is an acceptable and performant method for now
    	$img = $image->getCore();
        $grain = $this->argument(0)->value();
        $grain = (int) max(0, min(10, $grain/3));
    	for($i=0; $i < $grain; $i ++) {
	    	$img->addNoiseImage(Imagick::NOISE_LAPLACIAN);
	    }
        return $image;
    }
}