namespace Drupal\sku_route\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\commerce_product\Entity\ProductVariationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\commerce_product\ProductVariationStorageInterface;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends ControllerBase {
  /**
   * The product variation storage.
   *
   * @var \Drupal\commerce_product\ProductVariationStorageInterface
   */
  protected $variationStorage;

  public function __construct(ProductVariationStorageInterface $variation_storage) {
    $this->variationStorage = $variation_storage;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('commerce_product_variation')
    );
  }

  public function show($commerce_product_sku) {
    // Load the product variation by SKU.
    $variations = $this->variationStorage->loadByProperties(['sku' => $commerce_product_sku]);

    if ($variation = reset($variations)) {
      // Render the product variation.
      $view_builder = $this->entityTypeManager()->getViewBuilder('commerce_product_variation');
      $render_array = $view_builder->view($variation);

      return new Response(render($render_array));
    }
    else {
      // Return a 404 response if the SKU is not found.
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
    }
  }
}
