<?php

use Contao\ArrayUtil;

ArrayUtil::arrayInsert($GLOBALS['TL_DCA']['tl_newsletter_recipients']['list']['global_operations'], 1, [
        'export' => [
            'label' => &$GLOBALS['TL_LANG']['tl_newsletter_recipients']['export'],
            'href' => 'key=export',
            'icon' => 'pickfile.svg',
            'attributes' => 'onclick="Backend.getScrollOffset();"'
        ]
    ]
);