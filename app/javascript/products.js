
(function ($) {
    
'use strict';  
    
/*
 * Tree View - Categorias infinitas
 */
    
    var btnNewCategory = $('#new-category');
    var btnEditCategory = $('#edit-category');
    var btnDeleteCategory = $('#delete-category');
    var inputCategoryname = $('#input-category-name');
    var inputCategorySelected = $('[name="category-selected"]');
    var updateTree;
    
    $(document).ready(function(){
        updateTree();
    });
    
    updateTree = function() {
        $('.tree li:has(ul)').addClass('parent_li').find(' > span > i');
        $('.tree li:has(ul)').find('> span > i').addClass('fa fa-plus');
    };
    
    $(document).on('click', '.tree li.parent_li > span', function (e) {
        
        var children = $(this).parent('li.parent_li').find(' > ul > li');      
        if (children.is(":visible")) 
        {
            children.hide('fast');
            $(this).find('> i').addClass('fa, fa-plus').removeClass('fa, fa-minus');
        } 
        else 
        {
            children.show('fast');
            $(this).find('> i').addClass('fa, fa-minus').removeClass('fa, fa-plus');
        }
        e.stopPropagation();
    });
    
    // Selects the clicked item
    $(document).on('click', '.span-category-name', function(){ 
        
        if ($(this).hasClass('bg-light-blue-active') )
        {
            $(this).removeClass('bg-light-blue-active').addClass('bg-teal');
            $(this).next('input[type="radio"]').prop('checked', false);
            $(this).closest('ul.first-treeview').find('input[type="radio"]').prop('checked', false);
            inputCategoryname.val('');
        }
        else
        {
            $(this).closest('ul.first-treeview').find('span').removeClass('bg-light-blue-active').addClass('bg-teal');
            $(this).removeClass('bg-teal').addClass('bg-light-blue-active');
            $(this).closest('ul.first-treeview').find('input[type="radio"]').prop('checked', false);
            $(this).next('input[type="radio"]').prop('checked', true);
            var selectedNameCategory = $(this).next('input[type="radio"]').attr('data-category-name');
            inputCategoryname.val(selectedNameCategory);  
        }
    });
    
    
/*
 * Edit, Create and Delete Categories
 */
    var categoryName, categorySelected, newCategory, editCategory, deleteCategory, recordSelected;
    
    $(document).on('click', '#new-category', function(){
        categoryName = $('#input-category-name').val();
        categorySelected = $('input[name="category-selected"]:checked').val();
       
        $.get('/app/modules/products/manage_categories.php', {action:'new-category', categoryName: categoryName, categorySelected: categorySelected}, function(data){
            if(data.status === 'success')
            {
                $(".tree > ul").load(location.href+" .tree > ul>*", function(){
                    updateTree();
                }).slideUp('slow').slideDown('slow');
                
                show_alert('success',false,data.message,'fa fa-check',false);       
            }
            else if(data.status === 'warning')
            {
                 show_alert('warning',false,data.message,'fa fa-exclamation-triangle',false);
            }
            else if(data.status === 'info')
            {
                 show_alert('info',false,data.message,'fa fa-info',false);
            }
            else 
            {
                 show_alert('error',false,data.message,'fa fa-meh-o',false);
            }
        });
        
    });

})(jQuery);