<?php

namespace Intervention\Image\Imagick\Commands;

use \Imagick;
use \ImagickPixel;

class CamanChannelsCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {	

		// modeled to match CamanJS vibrance
		// for color matrices refer to https://docs.rainmeter.net/tips/colormatrix-guide/
		/*
		var hasProp = {}.hasOwnProperty;
		Filter.register("channels", function(options) {
		  var chan, value;
		  for (chan in options) {
		    if (!hasProp.call(options, chan)) continue;
		    value = options[chan];
		    if (value === 0) {
		      delete options[chan];
		      continue;
		    }
		    options[chan] /= 100;
		  }
		  if (options.length === 0) {
		    return this;
		  }
		  return this.process("channels", function(rgba) {
		    if (options.red != null) {
		      if (options.red > 0) {
		        rgba.r += (255 - rgba.r) * options.red;
		      } else {
		        rgba.r -= rgba.r * Math.abs(options.red);
		      }
		    }
		    if (options.green != null) {
		      if (options.green > 0) {
		        rgba.g += (255 - rgba.g) * options.green;
		      } else {
		        rgba.g -= rgba.g * Math.abs(options.green);
		      }
		    }
		    if (options.blue != null) {
		      if (options.blue > 0) {
		        rgba.b += (255 - rgba.b) * options.blue;
		      } else {
		        rgba.b -= rgba.b * Math.abs(options.blue);
		      }
		    }
		    return rgba;
		  });
		});
		*/

        $img = $image->getCore();
        $channels = $this->argument(0)->value();
        foreach($channels as $key => &$value) {
        	$value = $value/150;
        }

        $r = isset($channels['red']) ? $channels['red'] : 0;
        $g = isset($channels['green']) ? $channels['green'] : 0;
        $b = isset($channels['blue']) ? $channels['blue'] : 0;

        $quantum = $img->getQuantumRange()['quantumRangeLong'];
        
        // i do this to closer match caman's pixel editing
        // caman tends to tint the blacks more so i cut them out in order for the color leveling to work better
        $image->tankCurves([[0, 25], [127, 120], [255, 255]]); 

        $img->levelImage((0)*$quantum, 1+($r*1.5), (1-$r)*$quantum, Imagick::CHANNEL_RED);
        $img->levelImage((0)*$quantum, 1+($g*1.5), (1-$g)*$quantum, Imagick::CHANNEL_GREEN);
        $img->levelImage((0)*$quantum, 1+($b*1.5), (1-$b)*$quantum, Imagick::CHANNEL_BLUE);

        /*

        // attempted using color matrix to slightly different effect
		$img->setColorspace(Imagick::COLORSPACE_RGB);
		$img->recolorImage([
			1,		0,		0,		0,		0,
			0,		1,		0,		0,		0,
			0,		0,		1,		0,		0,
			0,		0,		0,		1,		0,
			0,		0,		0,		0,		0,
		]);

        
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