<?php
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\ProductDataProvider;
use PrestaShop\PrestaShop\Core\Product\ProductPresenter;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductPromotion extends Module
{
    public function __construct()
    {
        $this->name = 'productpromotion';
        $this->version = '1.0.0';
        $this->author = 'Khelifa Abdelkarim';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Product Promotion Banner');
        $this->description = $this->l('Displays a promotional product banner on the homepage and product pages.');
    }

    public function install()
{
    return parent::install() 
        && $this->registerHook('displayHome')
        && $this->registerHook('displayProductAdditionalInfo')
        && $this->registerHook('header') 
        && Configuration::updateValue('PROMO_CATEGORY', 1)
        && Configuration::updateValue('PROMO_TITLE', 'Featured Product');
}


    public function uninstall()
    {
        return parent::uninstall()
            && Configuration::deleteByName('PROMO_CATEGORY')
            && Configuration::deleteByName('PROMO_TITLE')
            && Configuration::deleteByName('PROMO_PRODUCT_ID');
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitPromoConfig')) {
            $title = Tools::getValue('PROMO_TITLE');
            $category = (int)Tools::getValue('PROMO_CATEGORY');
            $productId = (int)Tools::getValue('PROMO_PRODUCT_ID');

            Configuration::updateValue('PROMO_TITLE', $title);
            Configuration::updateValue('PROMO_CATEGORY', $category);
            Configuration::updateValue('PROMO_PRODUCT_ID', $productId);

            $this->context->smarty->assign('confirmation', $this->l('Settings updated'));
        }

        return $this->renderForm();
    }

    protected function renderForm()
    {
        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Promotion Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->l('Banner Title'),
                        'name' => 'PROMO_TITLE',
                        'required' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Category ID'),
                        'name' => 'PROMO_CATEGORY',
                        'required' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Product ID'),
                        'name' => 'PROMO_PRODUCT_ID',
                        'required' => true,
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->submit_action = 'submitPromoConfig';
        $helper->fields_value['PROMO_TITLE'] = Configuration::get('PROMO_TITLE');
        $helper->fields_value['PROMO_CATEGORY'] = Configuration::get('PROMO_CATEGORY');
        $helper->fields_value['PROMO_PRODUCT_ID'] = Configuration::get('PROMO_PRODUCT_ID');

        return $helper->generateForm([$fields_form]);
    }

    public function hookDisplayHome($params)
{
    $productId = (int)Configuration::get('PROMO_PRODUCT_ID');
    $product = $this->getProductDetails($productId); // Fetch product details

    $this->context->smarty->assign([
        'product_image' => $product['image'],
        'product_name' => $product['name'],
        'product_price' => $product['price'],
        'product_link' => $product['link'],
    ]);

    return $this->display(__FILE__, 'views/templates/front/productBanner.tpl');
}

// Helper function to get product details
protected function getProductDetails($productId)
{
    $product = new Product($productId, true, $this->context->language->id);
    
    // Debugging line
    if (!Validate::isLoadedObject($product)) {
        error_log('Product not found or invalid: ' . $productId);
        return null;
    }
    
    $image = $product->getCover($productId);
    $imageUrl = $this->context->link->getImageLink(
        $product->link_rewrite, $image['id_image'], 'home_default'
    );

    return [
        'name' => $product->name,  // This might be the issue
        'price' => Tools::displayPrice($product->price, $this->context->currency),
        'link' => $this->context->link->getProductLink($product),
        'image' => $imageUrl,
    ];
}



    public function hookHeader($params)
{
    // Add your module's CSS file
    $this->context->controller->registerStylesheet(
        'module-productpromotion-style',
        'modules/'.$this->name.'/views/css/productpromotion.css',
        [
            'media' => 'all',
            'priority' => 150,
        ]
    );
}
    
    protected function getProductImage($productId)
    {
        $product = new Product($productId, false, $this->context->language->id);
        $images = $product->getImages($this->context->language->id);

        if (!empty($images)) {
            $image = $images[0];
            $imageUrl = $this->context->link->getImageLink($product->link_rewrite, $image['id_image'], 'small_default');
            return $imageUrl;
        }

        return null;
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        $product = $this->getRandomProduct();
        $this->context->smarty->assign('product', $product);

        return $this->display(__FILE__, 'views/templates/hook/displayProduct.tpl');
    }

    protected function getRandomProduct()
    {
        $categoryId = (int)Configuration::get('PROMO_CATEGORY');
        $products = Product::getProducts($this->context->language->id, 0, 10, 'id_product', 'ASC', $categoryId);

        if (!empty($products)) {
            return $products[array_rand($products)];
        }

        return null;
    }
}
