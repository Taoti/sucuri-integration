<?php

namespace Drupal\sucuri_integration\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Cache\Cache;

class NodeSaveSubscriber implements EventSubscriberInterface {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  public static function getSubscribedEvents() {
    return [
      KernelEvents::RESPONSE => ['onNodeSave', 0],
    ];
  }

  public function onNodeSave(ResponseEvent $event) {

    // Check if the request is related to a node save
    $request = $event->getRequest();

    if ($request->attributes->has('_entity_form') && strpos($request->attributes->get('_entity_form'), 'node') === 0) {
      // Get node from request
      $node = \Drupal::routeMatch()->getParameter('node');

      // invalidate cache for the node
      Cache::invalidateTags($node->getCacheTags());

      // Log message
      \Drupal::logger('sucuri_integration')->notice('Node '. $node->id() .' saved and cache invalidated for the entity');
    }
  }
}
