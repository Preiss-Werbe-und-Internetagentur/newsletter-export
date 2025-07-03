<?php

use PWI\ContaoNewsletterExportBundle\Classes\Export;

$GLOBALS['BE_MOD']['content']['newsletter']['export'] = [Export::class, 'run'];