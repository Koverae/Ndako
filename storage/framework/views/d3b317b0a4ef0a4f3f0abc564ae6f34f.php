<div>
    <div class="table-responsive w-100 mb-2">
        <table class="table card-table text-nowrap">
            <thead class="order-table">
                <tr class="order-tr">
                    <th><button><?php echo e(__('Nom du Produit')); ?></button></th>
                    <th><button><?php echo e(__('Description')); ?></button></th>
                    <th><button><?php echo e(__('Quantité')); ?></button></th>
                    <th><button><?php echo e(__('Prix unitaire')); ?></button></th>
                    <th><button><?php echo e(__('Réduction')); ?></button></th>
                    
                    <th><button><?php echo e(__('Sous total')); ?></button></th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="cursor-pointer">
                <?php if($cart_items->isNotEmpty()): ?>
                    <?php $__currentLoopData = $cart_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="k_field_list_row">
                        <td class="k_field_list">
                            <input type="text"  class="k_input" value="<?php echo e($cart_item->name); ?>" />
                        </td>
                        <td class="k_field_list">
                            <input type="text" value="<?php echo e($cart_item->options->description); ?>" class="k_input">
                        </td>
                        <td class="k_field_list">
                            <?php echo $__env->make('livewire.cart.includes.product-cart-quantity', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </td>
                        <td class="k_field_list">
                            <input type="text" value="<?php echo e(format_currency($cart_item->options->unit_price)); ?>" class="k_input">
                        </td>
                        <td class="k_field_list">
                            <input type="text" value="<?php echo e(format_currency($cart_item->options->product_discount)); ?>" class="k_input">
                        </td>
                        
                        <td class="k_field_list">
                            <input type="text" value="<?php echo e(format_currency($cart_item->options->sub_total)); ?>" class="k_input">
                        </td>
                        <td class="k_field_list cursor-pointer">
                            <span wire:click.prevent="removeItem('<?php echo e($cart_item->rowId); ?>')">
                                <i class="bi bi-trash"></i>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                <?php $__currentLoopData = $inputs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="k_field_list_row">
                        <td class="k_field_list">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('search.search-input-text', ['special' => $key]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1145789483-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        </td>
                        <td class="k_field_list">
                            <input type="text" wire:model="description.<?php echo e($value); ?>" class="k_input">
                        </td>
                        <td class="k_field_list">
                            <input type="number" wire:model="qty.<?php echo e($value); ?>" class="k_input">
                        </td>
                        <td class="k_field_list">
                            <input type="text" wire:model="unit_price.<?php echo e($value); ?>" class="k_input">
                        </td>
                        <td class="k_field_list">
                            <input type="text" wire:model="tax.<?php echo e($value); ?>" class="k_input">
                        </td>
                        
                        <td class="k_field_list">
                            <input type="text" wire:model="subtotal.<?php echo e($value); ?>" class="k_input">
                        </td>
                        <td class="k_field_list cursor-pointer">
                            <span wire:click.prevent="remove(<?php echo e($key); ?>)">
                                <i class="bi bi-trash"></i>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($status == 'posted'): ?>
                <tr class="k_field_list_row">
                    <td class="k_field_list">
                        &nbsp;
                    </td>
                </tr>
                <?php else: ?>
                <tr class="k_field_list_row">
                    <td class="k_field_list">
                        <span wire:click.prevent="add(<?php echo e($i); ?>)" class=" cursor-pointer" href="avoid:js">
                            <i class="bi bi-plus-circle"></i> Ajouter un produit
                        </span>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Note and total part -->
    <div class="k_group row align-items-start mt-md-0">

        <div class="k_inner_group col-lg-10">
            <div class="k_cell flex-grow-0 flex-sm-grow-0">
                <div class="note-editable" id="note_1">
                    <textarea wire:model="term" id="term" style="width: 75%; padding-left: 5px; padding-top:10px;" id="" cols="30" rows="5" class="k_input textearea" placeholder="Termes & conditions">
                        <?php echo $term; ?>

                    </textarea>
                </div>
            </div>
        </div>

        <div class="k_inner_group k_subtotal_footer col-lg-2 right overflow-y-auto h-100">
            
            <?php if(settings()->has_discount): ?>
            <div class="discounts-btn mt-2 mb-2 text-end pb-2">
                <span class="btn btn-secondary">
                    Remise
                </span>
            </div>
            <?php endif; ?>

            <?php if($cart_items->isNotEmpty()): ?>
            <td class="k_td_label">
                <span>
                    <?php echo e(__('Total HT')); ?> :
                </span>
            </td>
            <br>
            <td class="k_list_monetary">
                <span>
                    
                    (=) <?php echo e(format_currency(convertToIntSimple(Cart::instance($this->cart_instance)->subtotal) / 100 )); ?>

                </span>
            </td>
            <?php endif; ?>
            <br class="mb-2">
            <?php if($this->global_tax > 0 && $cart_items->isNotEmpty()): ?>
                <!-- Taxes -->
                <td class="k_td_label">
                    <label for="" class="k_text_label k_tax_total_label">
                        <?php echo e(__('Taxe')); ?> (<?php echo e(sales_tax()->amount); ?>%) :
                    </label>
                </td>
                <br>
                <td class="k_list_monetary">
                    <span>
                        (+) <?php echo e(format_currency(convertToIntSimple($this->global_tax) / 100)); ?>

                    </span>
                </td>
                <br>
            <?php endif; ?>

            <!-- Reduction
            <td class="k_td_label">
                <label for="" class="k_text_label k_tax_total_label">
                    <?php echo e(__('Réduction')); ?> (<?php echo e($global_discount); ?>%)
                </label>
            </td>
            <br>
            <td class="k_list_monetary">
                <span>
                    (-) <?php echo e(format_currency(0)); ?>

                </span>
            </td>
            <br> -->

            <?php if($shipping): ?>
                <!-- Livraison -->
                <td class="k_td_label">
                    <label for="" class="k_text_label k_tax_total_label">
                        <?php echo e(__('Livraison')); ?> :
                    </label>
                </td>
                <br>
                <td class="k_list_monetary">
                    <input type="hidden" value="<?php echo e($shipping); ?>" name="shipping_amount">
                    <span>
                        (+) <?php echo e($shipping); ?>

                    </span>
                </td>
                <br>
            <?php endif; ?>

            <td class="k_td_label">
                <label for="" class="k_text_label k_tax_total_label">
                    <b><?php echo e(__('Total')); ?> :</b>
                </label>
            </td>

            <?php
                $total_with_shipping = Cart::instance($cart_instance)->total();
            ?>

            <td class="k_list_monetary">
                <span>
                    (=) <?php echo e(format_currency(convertToIntSimple($total_with_shipping) / 100)); ?>

                </span>
            </td>
        </div>
    </div>
    <!-- Loading -->
    <div class="k-loading cursor-pointer pb-1" wire:loading>
        <p>En cours de chargement ...</p>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\livewire\components\cart\product-cart.blade.php ENDPATH**/ ?>