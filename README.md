# moodle_local-sgevea
Plugin Moodle SGEVEA
plugin folder name: sgevea
plugin name: local_sgevea

# Installation in Moodle local plugin

## linux

Go to "local/" folder in moodle instance installation

sudo git clone https://github.com/dbanegasUNAE/moodle_local-sgevea.git sgevea

# Installation in my dashboard

## linux

Go to "my/" folder in moodle instance installation
sudo nano index.php

/* Set the next line before echo $OUTPUT->footer(); */
include("{$CFG->dirroot}/local/sgevea/views/my.php");
