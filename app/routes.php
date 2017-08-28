<?php

/** @var \Phprest\Application $app */
$app->get('/{version:\d\.\d}/', '\Moonwalker\Controllers\Test::get');
