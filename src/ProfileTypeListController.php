<?php

/**
 * @file
 * Contains \Drupal\profile2\ProfileTypeListController.
 */

namespace Drupal\profile2;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;

/**
 * List controller for profile types.
 */
class ProfileTypeListController extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['type'] = t('Profile type');
    $header['registration'] = t('Registration');
    $header['multiple'] = t('Allow multiple profiles');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['type'] = \Drupal::linkGenerator()
      ->generateFromUrl($entity->label(), $entity->urlInfo());
    $row['registration'] = $entity->registration ? t('Yes') : t('No');
    $row['multiple'] = $entity->multiple ? t('Yes') : t('No');
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations(EntityInterface $entity) {
    $operations = parent::getOperations($entity);
    // Place the edit operation after the operations added by field_ui.module
    // which have the weights 15, 20, 25.
    if (isset($operations['edit'])) {
      $operations['edit'] = array(
          'title' => t('Edit'),
          'weight' => 30,
        ) + $entity->urlInfo('edit-form')->toArray();
    }
    if (isset($operations['delete'])) {
      $operations['delete'] = array(
          'title' => t('Delete'),
          'weight' => 35,
        ) + $entity->urlInfo('delete-form')->toArray();
    }
    // Sort the operations to normalize link order.
    uasort($operations, array(
      'Drupal\Component\Utility\SortArray',
      'sortByWeightElement'
    ));

    return $operations;
  }

}