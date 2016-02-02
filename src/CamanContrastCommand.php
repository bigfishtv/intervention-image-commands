<?php

namespace Intervention\Image\Imagick\Commands;

class CamanContrastCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {	

		// modeled to match CamanJS contrast
		// for color matrices refer to https://docs.rainmeter.net/tips/colormatrix-guide/
		/*
		Filter.register("contrast", function(adjust) {
		  adjust = Math.pow((adjust + 100) / 100, 2);
		  return this.process("contrast", function(rgba) {

		    rgba.r /= 255;
		    rgba.r -= 0.5;
		    rgba.r *= adjust;
		    rgba.r += 0.5;
		    rgba.r *= 255;

		    rgba.g /= 255;
		    rgba.g -= 0.5;
		    rgba.g *= adjust;
		    rgba.g += 0.5;
		    rgba.g *= 255;

		    rgba.b /= 255;
		    rgba.b -= 0.5;
		    rgba.b *= adjust;
		    rgba.b += 0.5;
		    rgba.b *= 255;

		    return rgba;
		  });
		});
		*/
		
		// return $image->gamma(pow($this->argument(0)->value()/20, -1));
		$img = $image->getCore();
        $contrast = $this->argument(0)->value();	



        /*
		
		// Attempt 6 - tried another method, could work but doesn't do negative contrast

        list ($width, $height) = array_values($img->getImageGeometry());
		$img->contrastStretchImage ($width * $height * 0.2, $width * $height * 0.8);
        return $img;
		*/


        /*

        // Attempt 5 -- tried looping over the one-trick-pony Imagick contrast function, nah

        for($i = 0; $i < abs($contrast); $i ++) {
        	$img->contrastImage($contrast > 0 ? 0 : 1);
        }
        return $img;
        */


        /*

        // Attempt 4 -- tried a different Imagick contrast method, didn't match at all

        $quanta = $img->getQuantumRange()['quantumRangeLong'];
        // pr($quanta); exit;
		$img->sigmoidalContrastImage($contrast > 0 ? true : false, abs($contrast), 0.5*$quanta);
		return $img;
		*/


		

		// Attempt 3 -- attempted to create contrast with curves -- actually close, might revisit

        $amt = 75;

		$image->camanCurves([
			[0,		0],
			[$amt, 	0+$amt-$contrast],
			[180,	255-$amt+$contrast],
			[255,	255]
		]);

		return $image;
		
		

		/*
		
		// Attempt 2 -- attempted to use color matrix .. just wasn't right

		$amt = pow(($contrast + 100)/100, 2); // caman

		// This is an optimized version that uses a color matrix instead of iterating over pixels
		
		// $amt = $amt * 1.1; // idk why it just matches better
		// $amt = $contrast;
		$amt = (0.5 * $amt) + 0.5;
		$img->recolorImage([
			$amt,	0,		0,		-0.1,
			0,		$amt,	0,		-0.1,
			0,		0,		$amt,	-0.1,
			0,		0,		0,		$amt,
		]);
		*/
		
		
		/*

		// Attempt 1 -- accurate but needlessly slow

 		$iterator = $img->getPixelIterator();
        foreach ($iterator as $row => $pixels) {
        	foreach ($pixels as $column => $pixel) {

        		$color = $pixel->getColor();
				$r = max(0, min(255, intval((((($color['r']/255)-0.5)*$amt)+0.5)*255)));
				$g = max(0, min(255, intval((((($color['g']/255)-0.5)*$amt)+0.5)*255)));
				$b = max(0, min(255, intval((((($color['b']/255)-0.5)*$amt)+0.5)*255)));
				// $rgb = 'rgb('.$r.', '.$g.', '.$b.')';
				$hex = '#' . sprintf('%02x', $r) . sprintf('%02x', $g) . sprintf('%02x', $b);
				// if(strlen($hex) > 7) pr(compact('r', 'g', 'b'));
				$pixel->setColor($hex);
        		// $pixel->setColor('rgb('.$r.', '.$g.', '.$b.')');
        	}
       		$iterator->syncIterator();
        }
        */

        return $img;
    }
}

function rgb2hex($rgb) {
   $hex = "#";
   $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

   return $hex; // returns the hex value including the number sign (#)
}