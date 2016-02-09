$(document).ready(function () {
  page = 1;
  last_page = 0;
  total_data = 0;
  state = "";
  
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
  
  get_data(page);
});