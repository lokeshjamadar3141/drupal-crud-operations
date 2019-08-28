<?php
namespace Drupal\mydata\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

class DisplayTableController extends ControllerBase {
     public function getContent() {
 
    	$build = [
      		'description' => [
        	'#theme' => 'mydata_description',
        	'#description' => 'foo',
        	'#attributes' => [],
      		],
    	];
    	return $build;
  	}
  
  public function display() {
	    $header_table = array(
     		'id'=>    t('SrNo'),
      		'name' => t('Name'),
        	'mobilenumber' => t('MobileNumber'),
        	'pass' => t('pass'),
        	'opt' => t('operations'),
        	'opt1' => t('operations'),
    	);

		//select records from table
   		$query = \Drupal::database()->select('mydata', 'm');
     	$query->fields('m', ['id','name','mobilenumber','email','pass']);
      	$results = $query->execute()->fetchAll();
        $rows=array();
    	foreach($results as $data){
        	$delete = Url::fromUserInput('/mydata/form/delete/'.$data->id);
        	$edit   = Url::fromUserInput('/mydata/form/mydata?num='.$data->id);

      		//print the data from table
            $rows[] = array(
            	'id' =>$data->id,
                'name' => $data->name,
                'mobilenumber' => $data->mobilenumber,
                'pass' => $data->pass,
                 \Drupal::l('Delete', $delete),
                 \Drupal::l('Edit', $edit),
            );
		}
    	//display data in site
    	$form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('No users found'),
        ];
        return $form;
    }
}