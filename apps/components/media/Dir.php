<?php

class Dir extends \Helper
{
	public static function initDir(string $dir)
	{
		if(!file_exists($dir)) {
			return mkdir($dir, 0755, true);
		}
		return true;
	}
}

