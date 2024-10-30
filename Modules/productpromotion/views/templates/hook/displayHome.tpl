<div class="promo-banner" style="text-align: center; margin: 20px;">
    <h2>{$promo_title}</h2>
    {if $product}
        <img src="{$product.cover.bySize.home_default.url}" alt="{$product.name}">
        <p>Price: {$product.price} €</p>
        <a href="{$product.link}" class="btn btn-primary">Buy Now</a>
    {else}
        <p>No products found in this category.</p>
    {/if}
</div>
