<?php
namespace Drupal\mydata\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MydataForm extends FormBase {

  public function getFormId() {
    return 'mydata_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('mydata', 'm')
            ->condition('id', $_GET['num'])
            ->fields('m');
        $record = $query->execute()->fetchAssoc();
    }
    $form['candidate_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Candidate Name:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['name']) && $_GET['num']) ? $record['name']:'',
      );
    $form['mobile_number'] = array(
      '#type' => 'textfield',
      '#title' => t('Mobile Number:'),
      '#default_value' => (isset($record['mobilenumber']) && $_GET['num']) ? $record['mobilenumber']:'',
      );
    $form['candidate_mail'] = array(
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['email']) && $_GET['num']) ? $record['email']:'',
      );
  
    $form['pass'] = array (
      '#type' => 'password',
      '#title' => t('Password'),
        '#required' => TRUE,
        '#default_value' => (isset($record['pass']) && $_GET['num']) ? $record['pass']:'',
      );
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
    ];
    return $form;
  }
  
  public function validateForm(array &$form, FormStateInterface $form_state) {
         $name = $form_state->getValue('candidate_name');
          if(preg_match('/[^A-Za-z]/', $name)) {
             $form_state->setErrorByName('candidate_name', $this->t('your name must in characters without space'));
          }
         
          if (strlen($form_state->getValue('mobile_number')) < 10 ) {
            $form_state->setErrorByName('mobile_number', $this->t('your mobile number must in 10 digits'));
           }
    parent::validateForm($form, $form_state);
  }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $field=$form_state->getValues();
    $name=$field['candidate_name'];
    $number=$field['mobile_number'];
    $email=$field['candidate_mail'];
    $pass=$field['pass'];
    if (isset($_GET['num'])) {
          $field  = array(
              'name'   => $name,
              'mobilenumber' =>  $number,
              'email' =>  $email,
              'pass' => $pass,
          );
          $query = \Drupal::database();
          $query->update('mydata')
              ->fields($field)
              ->condition('id', $_GET['num'])
              ->execute();
          drupal_set_message("succesfully updated");
          $form_state->setRedirect('mydata.display_table_controller_display');
      }
       else
       {
           $field  = array(
              'name'   =>  $name,
              'mobilenumber' =>  $number,
              'email' =>  $email,
              'pass' => $pass,
          );
           $query = \Drupal::database();
           $query ->insert('mydata')
               ->fields($field)
               ->execute();
           drupal_set_message("succesfully saved");
           $response = new RedirectResponse("/crud/web/mydata/hello/table");
           $response->send();
       }
     }
}