<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoBackendFormsBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

use InspiredMinds\ContaoBackendFormsBundle\EventListener\ParseWidgetListener;

$GLOBALS['TL_HOOKS']['parseWidget'][] = [ParseWidgetListener::class, '__invoke'];
