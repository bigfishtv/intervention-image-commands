<?php

namespace Intervention\Image\Imagick\Commands;

use Imagick;
use ImagickPixel;
use ImagickDraw;
use DrQue\PolynomialRegression;

class TankGradientMapCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        bcscale(20);
        $markers = $this->argument(0)->value();
        $colors = interpolateGradient($markers, 255);

        $img = $image->getCore();

        $gradient = new Imagick();
        $gradient->newImage(255, 1, new ImagickPixel('white'));

        for ($x = 0; $x < 255; ++$x) {

            $pixel = new ImagickPixel('rgb(' . $colors[$x*4] . ',' . $colors[$x*4 + 1] . ',' . $colors[$x*4 + 2] . ')');

            $draw = new ImagickDraw();
            $draw->setFillColor($pixel);
            $draw->rectangle($x, 0, $x + 1, 1);

            $gradient->drawImage($draw);
        }

        if (method_exists($gradient, 'setImageInterpolateMethod')) {
            $gradient->setImageInterpolateMethod(Imagick::INTERPOLATE_BILINEAR);
        }
        
        $img->clutImage($gradient);

        return $image;
    }
}

function interpolateGradient($_markers = [], $range = 255) {
    // map the position and alpha to 255 then sort by position
    $markers = array_map(function($marker) use($range) {
        $marker['position'] = floor($marker['position'] * $range);
        $marker['alpha'] = floor($marker['alpha'] * 255);
        return $marker;
    }, $_markers);

    usort($markers, function($a, $b) {
        return $a['position'] < $b['position'] ? -1 : ($a['position'] > $b['position'] ? 1 : 0);
    });

    // if first marker is above 0 then create an identical one at 0
    if($markers[0]['position'] > 0) {
        $newMarker = $markers[0];
        $newMarker['position'] = 0;
        array_unshift($markers, $newMarker);
    }

    // and vice versa
    if($markers[count($markers)-1]['position'] < 255) {
        $newMarker = $markers[count($markers)-1];
        $newMarker['position'] = 255;
        $markers[] = $newMarker;
    }

    $rgbaData = [];
    $currentMarker = $markers[0];
    $nextMarker = $markers[1];
    for($i=0; $i<$range; $i++) {

        // bump current marker if reached next marker position
        if($i >= $nextMarker['position']) $currentMarker = $nextMarker;

        // don't proceed if reached last marker (position 255)
        $currentMarkerIndex = array_search($currentMarker, $markers);
        if($currentMarkerIndex === count($markers)-1) break;
        $nextMarker = $markers[$currentMarkerIndex+1];

        // get percentage along current and next marker
        $amt = ($i - $currentMarker['position']) / ($nextMarker['position'] - $currentMarker['position']);

        // add pixel data to array
        $rgbaData[] = $currentMarker['color'][0]*(1-$amt) + $nextMarker['color'][0]*$amt;
        $rgbaData[] = $currentMarker['color'][1]*(1-$amt) + $nextMarker['color'][1]*$amt;
        $rgbaData[] = $currentMarker['color'][2]*(1-$amt) + $nextMarker['color'][2]*$amt;
        $rgbaData[] = $currentMarker['alpha']*(1-$amt)    + $nextMarker['alpha']*$amt;
    }
    return $rgbaData;
}