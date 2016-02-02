<?php

namespace Intervention\Image\Imagick\Commands;
use \Imagick;

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
		
		// convert campus-5-jpg.JPG -colorspace RGB -fx 'mx=max(r,g,b); amt=((abs(mx-lightness)*2/255)*-30)/100; r=r+(mx-r)*amt; g=g+(mx-g)*amt; b=b+(mx-b)*amt' test.jpg
		// convert campus-5-jpg.JPG -colorspace RGB -fx 'r=r+0.5; g=g+0.5; b=b+0.5;' test.jpg
		/*

		if [ $amount -lt 0 ]; then
			gval=`convert xc: -format "%[fx:1-$amount*(0.25/100)]" info:`
		else
			gval=`convert xc: -format "%[fx:1-$amount*(0.50/100)]" info:`
		fi

		# convert amount for use by -modulate
		amount=`convert xc: -format "%[fx:100+$amount]" info:`

		# process image
		convert $tmp1A \
		\( -clone 0 -modulate 100,$amount,100 \) \
		\( -clone 0 -colorspace HSL -channel GB -separate +channel -negate \
		-compose blend -define compose:args=$blend -composite -gamma $gval \) \
		-compose over -composite \
		"$outfile"
	
		*/

		/*
		convert $tmp1A 
			( -clone 0 -modulate 100, $amount,100 ) 
			( -clone 0 -colorspace HSL -channel GB -separate +channel -negate -compose blend -define compose:args=$blend -composite -gamma $gval )
			-compose over -composite

			convert campus-5-jpg.JPG -colorspace HSL -channel GB -separate +channel -negate -compose blend test.jpg
			convert campus-5-jpg.JPG -colorspace HSL -channel GB -separate +channel -negate test.jpg
			convert campus-5-jpg.JPG -colorspace HSL -channel GB -separate +channel -negate test.jpg
		*/

		
        $img = $image->getCore();
        $vibrance = $this->argument(0)->value() ?: 0;

        $amt = $vibrance/200;

        $img->recolorImage([
			1+$amt,			-$amt/1.5,		-$amt/1.5,		0,
			-$amt/1.5,		1+$amt,			-$amt/1.5,		0,
			-$amt/1.5,		-$amt/1.5,		1+$amt,			0,
			-$amt/1.5,		-$amt/1.5,		-$amt/1.5,		1+$amt,
		]);

		return $img;

       	/* 



        $brightnessBlend = $this->argument(1)->value() ?: '25';
        $saturationBlend = $this->argument(2)->value() ?: '75';


        $gval = (1-$vibrance)*(($vibrance < 0 ? 0.25 : 0.5)/100);
        $amount = 100+$vibrance;

        // $img1 = clone $img;
        $img->modulateImage(100, $amount, 100);

        $img2 = clone $img;
        $img2->transformImageColorspace(Imagick::COLORSPACE_HSL);
        
        $imgG = clone $img2;
        $imgG->separateImageChannel(Imagick::CHANNEL_GREEN);
        $imgG->negateImage(true);
		
		$imgB = clone $img2;
        $imgB->separateImageChannel(Imagick::CHANNEL_BLUE);
        $imgB->negateImage(true);



        $imgG->compositeImage($imgB, Imagick::COMPOSITE_BLEND, 0, 0);// $img23 = $img2->compositeImage($img3, Imagick::COMPOSITE_BLEND, 0, 0);
        $img2->compositeImage($imgG, Imagick::COMPOSITE_BLEND, 0, 0);
        
        $img->setImageArtifact('compose:args', $brightnessBlend.','.$saturationBlend);
        
        $img->compositeImage($img2, Imagick::COMPOSITE_BLEND, 0, 0);
        // $img->gammaImage($gval);

        return $img;
        */

        /*
        $img1 = clone $img;
        $fx = 'mx=max(r,g,b); amt=((abs(mx-lightness)*2/255)*-30)/100; r=r+(mx-r)*amt; g=g+(mx-g)*amt; b=b+(mx-b)*amt';
        $img1->fxImage($fx);

        return $img;
		*/

		
        /*
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
        */

    }
}