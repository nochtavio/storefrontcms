$(document).ready(function () {
  page = 1;
  last_page = 0;
  total_data = 0;
  state = "";
  category = [];
  category_child = [];
  category_child_ = [];
  
  $('#txt_data_description').summernote({
    height: 150,
    toolbar:
      [
        //[groupname, [button list]]
        ['style', ['bold', 'italic', 'underline']],
        ['font', []],
        ['fontsize', ['fontsize']]
      ],
    onPaste: function (e) {
        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
        e.preventDefault();
        document.execCommand('insertText', false, bufferText);
    }
  });
  
  $('#txt_data_short_description').summernote({
    height: 150,
    toolbar:
      [
        //[groupname, [button list]]
        ['style', ['bold', 'italic', 'underline']],
        ['font', []],
        ['fontsize', ['fontsize']]
      ],
    onPaste: function (e) {
        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
        e.preventDefault();
        document.execCommand('insertText', false, bufferText);
    }
  });
  
  $('#sel_data_category').multiselect({
    enableFiltering: true,
    buttonWidth: '400px',
    maxHeight: 400,
    onChange: function(option, checked, select) {
      if($(option).is(':selected')){
        category.push($(option).val());
        category = unique_array(category);
      }else{
        category = remove_array(category, $(option).val());
      }
      generate_category_child();
    }
  });
  
  $('#category_child_container').hide();
  $('#category_child__container').hide();
  get_data(page);
});