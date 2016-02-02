<?php

namespace Intervention\Image\Imagick\Commands;

use \Imagick;
use \ImagickPixel;

class CamanGrainCommand extends \Intervention\Image\Commands\AbstractCommand
{	
	public function execute($image)
    {	
    	$img = $image->getCore();
        $grain = $this->argument(0)->value();
        $grain = (int) max(0, min(10, $grain/3)); 

        // $size = $img->getSize();
        // $noise = new Imagick();
        // $noise->newImage($size['columns'], $size['rows'], new ImagickPixel('gray'));
        // $noise->addNoiseImage(\Imagick::NOISE_RANDOM);
        // $noise->setImageOpacity($grain/100);


        // pr(\Imagick::NOISE_UNIFORM);exit;
    	// $img->combineImages();

    	for($i=0; $i < $grain; $i ++) {
	    	$img->addNoiseImage(Imagick::NOISE_LAPLACIAN);
	    }
        return $img;
    }
}