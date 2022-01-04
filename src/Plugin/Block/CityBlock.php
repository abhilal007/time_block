<?php

namespace Drupal\time_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\time_block\TimeBlockHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'city' block.
 *
 * @Block(
 *   id = "city_block",
 *   admin_label = @Translation("Site Location"),
 *   category = @Translation("Location")
 * )
 */
class CityBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Timeblock service object.
   *
   * @var \Drupal\time_block\TimeBlockHelper
   */
  protected $blockHelper;

  /**
   * The form config object.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructor for the city block.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory to get the config data.
   * @param \Drupal\time_block\TimeBlockHelper $blockHelper
   *   The service class for the module services.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, TimeBlockHelper $blockHelper) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->blockHelper = $blockHelper;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('time_block.helper')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $config = $this->configFactory->get('time_block.settings');
    $time_zone = $config->get('timezone');
    if (!$time_zone) {
      return [
        '#theme' => 'status_messages',
        '#message_list' => [
          'error' => [
            $this->t('Module is not configured.'),
          ],
        ],
        '#status_headings' => [
          'error' => $this->t('Error Message'),
        ],
      ];
    }
    $time = $this->blockHelper->convert($time_zone);
    return [
      '#theme' => 'city_block',
      '#country' => $config->get('country'),
      '#city' => $config->get('city'),
      '#time' => $time,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge(): int {
    return 60;
  }

}
