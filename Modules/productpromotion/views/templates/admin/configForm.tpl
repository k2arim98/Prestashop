<div class="panel">
    <h3>{$module_name} - {$title}</h3>

    {if isset($confirmation)}
        <div class="alert alert-success">
            <p>{$confirmation}</p>
        </div>
    {/if}

    <form method="post" action="{$form_action}" class="defaultForm form-horizontal">
        <div class="form-group">
            <label class="control-label col-lg-3" for="promo_title">
                {$promo_title_label}
            </label>
            <div class="col-lg-6">
                <input type="text" name="PROMO_TITLE" id="promo_title" 
                       value="{$promo_title}" class="form-control" required />
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-lg-3" for="promo_category">
                {$promo_category_label}
            </label>
            <div class="col-lg-6">
                <input type="number" name="PROMO_CATEGORY" id="promo_category" 
                       value="{$promo_category}" class="form-control" required />
            </div>
        </div>

        <!-- New Product ID Field -->
        <div class="form-group">
            <label class="control-label col-lg-3" for="promo_product">
                {$promo_product_label}
            </label>
            <div class="col-lg-6">
                <input type="number" name="PROMO_PRODUCT_ID" id="promo_product" 
                       value="{$promo_product_id}" class="form-control" required />
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-6">
                <button type="submit" name="submitPromoConfig" class="btn btn-primary">
                    {$save_button_label}
                </button>
            </div>
        </div>
    </form>
</div>
