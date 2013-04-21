#!/bin/bash

convert $1 ../images/watermark_images/tmp.ppm
./../images/watermark_images/convert_image < ../images/watermark_images/tmp.ppm >| ../images/watermark_images/size.dat
a=$(cat ../images/watermark_images/size.dat)
convert -geometry $a $1 $1
rm ../images/watermark_images/tmp.ppm
rm ../images/watermark_images/size.dat
