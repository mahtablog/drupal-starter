<?php

namespace Drupal\Tests\server_general\ExistingSite;

use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\og\Og;
use Symfony\Component\HttpFoundation\Response;
use Drupal\og\OgGroupAudienceHelperInterface;

/**
 * Test for Node Group view builder.
 */
class ServerGeneralNodeGroupViewBuilderTest extends ServerGeneralTestBase {

  /**
   * Tests the group page displays a subscription prompt for non-members.
   */
  public function testGroupSubscriptionPromptShownForNonMember() {
    // Create a user who will be the group owner.
    $owner = $this->createUser([], NULL, TRUE);

    // Log in as the owner to create the group.
    $this->drupalLogin($owner);

    // Ensure the group content type has the OG field.
    if (!Og::groupTypeManager()->isGroup('node', 'group')) {
      Og::createField(OgGroupAudienceHelperInterface::DEFAULT_FIELD, 'node', 'group');
    }

    // Create the group node.
    $group = Node::create([
      'type' => 'group',
      'title' => 'Test Group',
      'body' => [
        'value' => 'This is a test group.',
        'format' => 'full_html',
      ],
      'uid' => $owner->id(),
      'status' => 1,
    ]);
    $group->save();

    // Create a second user who is NOT a member.
    $non_member = $this->createUser([], NULL, TRUE);
    $this->drupalLogin($non_member);

    // Visit the group's page.
    $this->drupalGet($group->toUrl());

    // Confirm page loads.
    $this->assertSession()->statusCodeEquals(200);

    // Confirm the subscription prompt is shown.
    $this->assertSession()->pageTextContains('Hi ' . $non_member->getDisplayName());
    $this->assertSession()->pageTextContains('click here if you would like to subscribe');
  }

}
