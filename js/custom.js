jQuery(document).ready(function() {
    AfterLookUpLoad();

    

    jQuery("body").on("click",".remove_row", function(){
        var id = jQuery(this).attr('data-id');
        jQuery("#row_"+id).remove();

    });     
    jQuery("body").on("click",".addmore", function(){
        var option_get = jQuery(".option_get").html();
        var row = '<tr id="row_'+count+'"><td style="width:250px"> <select class="js-example-basic-multiple option_'+count+'" name="products['+count+'][products_id][]" multiple="multiple">'+option_get+'</select> </td><td> <input type="text" name="products['+count+'][lable_name]" placeholder="Fee label"> </td><td> <select name="products['+count+'][fee_type]"> <option value="fix">Fix</option> <option value="percentage">Percentage</option> </select> </td><td> <input placeholder="Fee" type="number" name="products['+count+'][fee]"> </td><td><input type="button" class="remove_row" name="button" data-id="'+count+'" value="remove"></td></tr>';
        count++;
        jQuery('.products_fees_table').append(row);
        AfterLookUpLoad();
    });     

  
});

function AfterLookUpLoad(){
      jQuery('.js-example-basic-multiple').select2({});
     }

