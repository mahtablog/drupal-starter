<?php

namespace Drupal\server_general\Plugin\EntityViewBuilder;

use Drupal\Core\Link;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\media\MediaInterface;
use Drupal\node\NodeInterface;
use Drupal\og\Og;
use Drupal\og\OgMembershipManagerInterface;
use Drupal\server_general\EntityViewBuilder\NodeViewBuilderAbstract;
use Drupal\server_general\ThemeTrait\ElementNodeGroupThemeTrait;
use Drupal\server_general\TagTrait;
use Drupal\server_general\SocialShareTrait;

/**
 * The "Node Group" plugin.
 *
 * @EntityViewBuilder(
 *   id = "node.group",
 *   label = @Translation("Node - Group"),
 *   description = "Node view builder for Group bundle."
 * )
 */
class NodeGroup extends NodeViewBuilderAbstract {

    use ElementNodeGroupThemeTrait;

  /**
   * Build full view mode.
   *
   * @param array $build
   *   The existing build.
   * @param \Drupal\node\NodeInterface $entity
   *   The group node entity.
   *
   * @return array
   *   Render array.
   */
  public function buildFull(array $build, NodeInterface $entity) {
    $subscription_prompt = [];
    $current_user = \Drupal::currentUser();
    // The node's label.
    $node_type = $this->entityTypeManager->getStorage('node_type')->load($entity->bundle());
    $label = $node_type->label();
    // Check if the user is logged in and the node is a group.
    if (
        $current_user->isAuthenticated() &&
        Og::isGroup($entity->getEntityTypeId(), $entity->bundle())
      ) {
      /** @var \Drupal\og\OgMembershipManagerInterface $membership_manager */
      $membership_manager = \Drupal::service('og.membership_manager');
      $check = $membership_manager->isMember($entity, $current_user);
      // If user is NOT already a member of the group.
      if (!$check) {
        $group_name = $entity->label();
        $user_name = $current_user->getDisplayName();

        $url = Url::fromRoute('og.subscribe', [
        'entity_type_id' => 'node',
        'group' => $entity->id(),
        ]);
        $link = Link::fromTextAndUrl(t('click here'), $url)->toRenderable();
        $subscription_prompt = [
            'user_name' => $user_name,
            'link' => $link,
            'group_name' => $group_name,              
        ];
      }
    }

    // The hero responsive image.
    $medias = $entity->get('field_featured_image')->referencedEntities();
    $image = $this->buildEntities($medias, 'hero');

    $element = $this->buildElementGroupNode(
      $entity->label(),
      $label,
      $this->getFieldOrCreatedTimestamp($entity, 'field_publish_date'),
      $image,
      $this->buildProcessedText($entity, 'body'),
      $subscription_prompt,
    );

    $build[] = $element;


    return $build;
  }
}
