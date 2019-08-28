<?php

namespace Drupal\mydata\Controller;

Use Drupal\Core\Controller\ControllerBase;

class MydataController extends ControllerBase {

public function display() {

    return array(

      '#type' => 'markup',

      '#markup' => t('welcome to our website'),

    );

  }
}