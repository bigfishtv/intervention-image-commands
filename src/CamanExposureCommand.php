<?php

namespace Intervention\Image\Imagick\Commands;

class CamanExposureCommand extends \Intervention\Image\Commands\AbstractCommand
{	
	/*
	Filter.register("exposure", function(adjust) {
	  var ctrl1, ctrl2, p;
	  p = Math.abs(adjust) / 100;
	  ctrl1 = [0, 255 * p];
	  ctrl2 = [255 - (255 * p), 255];
	  if (adjust < 0) {
	    ctrl1 = ctrl1.reverse();
	    ctrl2 = ctrl2.reverse();
	  }
	  return this.curves('rgb', [0, 0], ctrl1, ctrl2, [255, 255]);
	});
	*/
	public function execute($image)
    {	
        $exposure = $this->argument(0)->value();
        $p = abs($exposure) / 100;
        $ctrl1 = [0, 255*$p];
        $ctrl2 = [255 - (255*$p), 255];
        if($exposure < 0) {
        	array_reverse($ctrl1);
        	array_reverse($ctrl2);
        }
        $points = [$ctrl1, $ctrl2];
        $image->camanCurves($points);
        return $image;
    }
}