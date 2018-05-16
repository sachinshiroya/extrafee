
<?php
global $wpdb;
$args     = array( 'post_type' => 'product', 'posts_per_page' => -1 );
$products = get_posts( $args );
//echo "<pre>"; print_r($products);exit;

if(isset($_POST['submit'])){
	if(isset($_POST['products'])){
		foreach ($_POST['products'] as $key => $value) {
			# code...
			$value['products_id'] = implode(",", $value['products_id']);
			if(isset($value['id']) && $value['id'] !=""){
				$wpdb->update("{$wpdb->prefix}extra_fee",$value,array('id'=>$value['id']));
			}else{
				$wpdb->insert("{$wpdb->prefix}extra_fee",$value);
			}
		}
	}
}


$get_results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}extra_fee");

?>
<style type="text/css">
	span.select2.select2-container.select2-container--default{
		width: 250px !important;
	}
</style>
<script>
	<?php if(isset($get_results) && !empty($get_results)) : ?>
		var count = <?php echo count($get_results);?>
	<?php else : ?>
		var count = 0;
	<?php endif; ?>		
</script>

<div class="wrap">
		<h2>Add Extra Fees on Perticular products</h2> <button class="addmore">Add</button>
</div>

<form action="#" method="POST">
<table class="products_fees_table">
	<?php for ($i=0; $i < count($get_results); $i++ ): ?>

	<tr id="row_<?php echo $i;?>" >
		<td style="width:250px">
		<select class="js-example-basic-multiple" name="products[<?php echo $i;?>][products_id][]" multiple="multiple">

	  	<?php if(!empty($products))  :
				foreach($products as $key => $product) : ?>
				<option <?php echo (in_array($product->ID, explode(",", $get_results[$i]->products_id))) ? 'selected="selected"' : '';?> value="<?php echo $product->ID; ?>"><?php echo $product->post_name;?></option>
			<?php endforeach; 
				endif; ?>				  	
		</select>
	</td>
	<td>
	

		<input type="text" name="products[<?php echo $i;?>][lable_name]" value="<?php echo $get_results[$i]->lable_name; ?>" placeholder="Fee label">
	</td>
	<td>
		<select name="products[<?php echo $i;?>][fee_type]">
			<option value="fix" <?php echo  (isset($get_results[$i]->fee_type) &&  $get_results[$i]->fee_type == "fix") ? 'selected="selected"' : '';?>>Fix</option>
			<option value="percentage" <?php echo (isset($get_results[$i]->fee_type) &&  $get_results[$i]->fee_type == "percentage") ? 'selected="selected"' : '';?>>Percentage</option>
		</select>
	</td>
	<td>
		<input placeholder="products" type="number" name="products[<?php echo $i; ?>][fee]" value="<?php echo $get_results[$i]->fee; ?>">
		<input type="hidden" value="<?php echo $get_results[$i]->id; ?>" name="products[<?php echo $i;?>][id]">
	</td>
	<td>
		<input type="button" class="remove_row" name="button" data-id="<?php echo $i;?>" value="remove">
	</td>
</tr>
	</div>

<?php endfor ;?>
</table>
	<input type="submit" name="submit" value="submit">

</form>

<div class="copy" style="display: none">
	<table class="copy_tr">
	<tr id="row_#remove#" >
		<td style="width:250px">
		<select class="js-example-basic-multiple option_get" name="products[#remove#][products_id][]" multiple="multiple">

	  	<?php if(!empty($products))  :
				foreach($products as $key => $product) : ?>
				<option  value="<?php echo $product->ID; ?>"><?php echo $product->post_name;?></option>
			<?php endforeach; 
				endif; ?>				  	
		</select>
	</td>
	<td>
		<input type="text" name="products[#remove#][lable_name]"  placeholder="Fee label">
	</td>
	<td>
		<select name="products[#remove#][fee_type]">
			<option value="fix">Fix</option>
			<option value="percentage">Percentage</option>
		</select>
	</td>
	<td>
		<input placeholder="Fee" type="number" name="products[#remove#][fee]" value="<?php echo $get_results[$i]->fee; ?>">
	</td>
</tr>
</table>
</div>
