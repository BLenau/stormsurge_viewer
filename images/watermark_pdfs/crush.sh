#!/bin/bash

/home/blenau/pngcrush/pngcrush $1 ../images/watermark_pdfs/tmp.png
mv -f ../images/watermark_pdfs/tmp.png $1
