<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_1_4($object)
{
	return $object->registerHook('footer');
}


