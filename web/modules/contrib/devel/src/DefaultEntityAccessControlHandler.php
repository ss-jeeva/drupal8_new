<?php

namespace Drupal\devel;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Default entity entity.
 *
 * @see \Drupal\devel\Entity\DefaultEntity.
 */
class DefaultEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\devel\Entity\DefaultEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished default entity entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published default entity entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit default entity entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete default entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add default entity entities');
  }


}
