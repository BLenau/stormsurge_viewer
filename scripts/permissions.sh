#!/bin/bash

chmod o+rx ../../storm_surge/ ../../new_stormsurge/

chmod o+r ../*.php

chmod -R o+r ../scripts/ ../images/ ../admin/ ../pdfs/

chmod +x database_scripts/*.sql database_scripts/*.sh database_scripts/*.sed
chmod o+x database_scripts/*.sql database_scripts/*.sh database_scripts/*.sed

chmod o+x ../scripts/ ../admin/ 

chmod o+x css/ database_scripts/ js/ jquery/ validation/ pie/
chmod o+x lightbox/ lightbox/css/ lightbox/images/ lightbox/js/

chmod o+x ../images ../images/watermark_images/ ../images/watermark_pdfs/
chmod o+x ../images/devs/ ../images/markers/ ../pdfs/
