<?php
namespace Drupal\stock_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Client;

/**
 * 
 * Provides a 'StockBlock' block to display Sony stock data.
 *
 * @Block(
 *   id = "stock_block",
 *   admin_label = @Translation("Stock Data Block"),
 * )
 */
class StockBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The HTTP client to fetch the stock data.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, Client $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->httpClient = $http_client;
  }

  public function build() {
    $sony_stock = $this->getStockData('SONY');

    return [
      '#markup' => $this->t('SONY: $@data', ['@data' => $sony_stock]),
    ];
  }

  protected function getStockData($symbol) {
    $api_key = '2MHXFFDDRZ9H6X8H'; // Replace with your actual API key
    $url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol={$symbol}&apikey={$api_key}";
    
    try {
      $response = $this->httpClient->request('GET', $url);
      $data = json_decode($response->getBody(), TRUE);
      return $data['Global Quote']['05. price']; 
    } catch (\Exception $e) {
      watchdog_exception('stock_data', $e);
      return $this->t('Unavailable');
    }
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('http_client')
    );
  }
}
