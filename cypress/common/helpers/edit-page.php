<?php

use Drupal\user\Entity\User;

list($title, $workspace, $newTitle) = $extra;

/** @var \Drupal\workspaces\WorkspaceManagerInterface $workspacesManager */
$workspacesManager = \Drupal::service('workspaces.manager');
/** @var \Drupal\Core\Session\AccountSwitcherInterface $accountSwitcher */
$accountSwitcher = \Drupal::service('account_switcher');
$accountSwitcher->switchTo(User::load(1));

$workspacesManager->executeInWorkspace($workspace, function () use ($workspace, $title, $newTitle) {
  /** @var \Drupal\node\NodeStorageInterface $nodeStorage */
  $nodeStorage = \Drupal::entityTypeManager()->getStorage('node');
  $nodes = $nodeStorage->loadByProperties(['title' => $title]);
  if (!$nodes) {
    return;
  }
  $node = array_pop($nodes);
  /** @var \Drupal\workspaces\WorkspaceAssociationInterface $workspaceAssociation */
  $workspaceAssociation = \Drupal::service('workspaces.association');
  $tracked = $workspaceAssociation->getTrackedEntities($workspace, 'node', [$node->id()]);
  if (!$tracked) {
    return;
  }
  $revision = $nodeStorage->loadRevision(array_keys($tracked['node'])[0]);
  $newRevision = $nodeStorage->createRevision($revision);
  $newRevision->title = $newTitle;
  $newRevision->save();
});
