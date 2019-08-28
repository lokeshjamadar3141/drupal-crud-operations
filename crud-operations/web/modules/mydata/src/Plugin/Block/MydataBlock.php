<?php

namespace Drupal\mydata\plugin\block;
use Drupal\Core\Block\Blockbase;

class MydataBlock extends BlockBase {
	public functoion build(){

		$from = Drupal::frombuilder()->getForm('Drupal\mydata\From\MydataFrom');

		return $from;
	}

}