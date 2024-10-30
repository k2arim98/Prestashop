<div class="product-banner">
    {if isset($product_image)}
        <div style="display: flex; justify-content: center; align-items: center; height: 200px;">
            <img src="{$product_image}" alt="Promotional Product" style="width: 300px; height: 200px;">
        </div>
        <h3>{$product_name|escape:'html':'UTF-8'}</h3> 
        <p>Price: {$product_price}</p> 
    {else}
        <p>No image available for this product.</p>
    {/if}
</div>
