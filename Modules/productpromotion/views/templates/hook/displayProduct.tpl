{if isset($product)}
    <div class="product-info">
        <h2>{$product.name}</h2>
        <p class="product-price">{$product.price}</p>
        <a href="{$product.link}" class="btn btn-primary">View Product</a>
        {if isset($product.image)}
            <img src="{$product.image}" alt="{$product.name}" style="width: 300px; height: 200px;">
        {/if}
    </div>
{else}
    <p>No product information available.</p>
{/if}
