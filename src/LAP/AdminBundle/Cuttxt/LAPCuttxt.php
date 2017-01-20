<?php

namespace LAP\AdminBundle\Cuttxt;

class LAPCuttxt
{
	/**
	* Cut the text as a resume for viewing a list of articles in table
	*/
	public function cuttext($txt)
	{
		$txt = preg_replace('/<img(.*?)>/', '', $txt);
		$txt = preg_replace('/<p><\/p>/', '', $txt);
		return $txt;
	}
}