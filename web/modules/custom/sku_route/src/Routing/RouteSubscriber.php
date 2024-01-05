namespace Drupal\sku_route\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriber extends RouteSubscriberBase {
  protected function alterRoutes(RouteCollection $collection) {
    // Define the route for products by SKU.
    $route = $collection->get('entity.commerce_product.canonical');
    $route->setPath('/product/{commerce_product_sku}');
  }
}



