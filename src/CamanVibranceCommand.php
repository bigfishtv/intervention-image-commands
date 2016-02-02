<?php

namespace Intervention\Image\Imagick\Commands;

class CamanVibranceCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {	

		// modeled to match CamanJS vibrance
		// for color matrices refer to https://docs.rainmeter.net/tips/colormatrix-guide/
		/*
		Filter.register("vibrance", function(adjust) {

		  adjust *= -1;

		  return this.process("vibrance", function(rgba) {

		    var max = Math.max(rgba.r, rgba.g, rgba.b);
		    var avg = (rgba.r + rgba.g + rgba.b) / 3;
		    var amt = ((Math.abs(max - avg) * 2 / 255) * adjust) / 100;

		    if (rgba.r !== max) {
		      rgba.r += (max - rgba.r) * amt;
		    }
		    if (rgba.g !== max) {
		      rgba.g += (max - rgba.g) * amt;
		    }
		    if (rgba.b !== max) {
		      rgba.b += (max - rgba.b) * amt;
		    }
		    return rgba;
		  });
		});
		*/

        $img = $image->getCore();
        $vibrance = $this->argument(0)->value();
        $vibrance *= -1;

        $iterator = $img->getPixelIterator();
        foreach ($iterator as $row => $pixels) {
        	foreach ($pixels as $column => $pixel) {

        		$color = $pixel->getColor();
				$r = $color['r'];
				$g = $color['g'];
				$b = $color['b'];

				$max = max($r, $g, $b);
				$avg = ($r + $g + $b) / 3;
		    	$amt = ((abs($max - $avg) * 2 / 255) * $vibrance) / 100;

		    	if($r !== $max) $r += ($max - $r) * $amt;
		    	if($g !== $max) $g += ($max - $g) * $amt;
		    	if($b !== $max) $b += ($max - $b) * $amt;

				$hex = '#' . sprintf('%02x', min(255, $r)) . sprintf('%02x', min(255, $g)) . sprintf('%02x', min(255, $b));
				$pixel->setColor($hex);

        		// $pixel->setColor('rgba('.$r.', '.$g.', '.$b.', '.$a.')');
        	}
       		$iterator->syncIterator();
        }

        return $img;
    }
}