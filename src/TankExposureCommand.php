<?php

namespace Intervention\Image\Imagick\Commands;

class TankExposureCommand extends \Intervention\Image\Commands\AbstractCommand
{	
	public function execute($image)
    {	
        $exposure = (int) $this->argument(0)->value();
        $p = $exposure / 100;
        $ctrl1 = [0, 255*$p];
        $ctrl2 = [255 - (255*$p), 255];
        if($exposure < 0) {
        	array_reverse($ctrl1);
        	array_reverse($ctrl2);
        }
        $points = [$ctrl1, $ctrl2];
        $image->tankCurves($points);
        return $image;
    }
}