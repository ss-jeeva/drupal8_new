<?php

namespace Drupal\devel;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\devel\Entity\DefaultEntityInterface;

/**
 * Defines the storage handler class for Default entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Default entity entities.
 *
 * @ingroup devel
 */
class DefaultEntityStorage extends SqlContentEntityStorage implements DefaultEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(DefaultEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {employee_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {employee_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(DefaultEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {employee_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('employee_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
