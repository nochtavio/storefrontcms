$(document).ready(function(){
  //Function Write Paging
  writePaging = function (total_page, page, class_page){
    if (total_page > 1){
      var initial = (parseInt(page) - parseInt(4) > 1) ? parseInt(page) - parseInt(5) : 0 ;
      var total = (parseInt(page) + parseInt(4) <= total_page) ? parseInt(page) + parseInt(4) : parseInt(total_page);
      
      $('#paging').empty();
      $('#paging').append("<li><a href='#' class='firstpage'>&laquo;</a></li>");
      for (var y = initial; y < total; y++){
        $('#paging').append("<li class='page" + (y + 1) + "'><a href='#' class='page'>" + (y + 1) + "</a></li>");
      }
      $('#paging').append("<li><a href='#' class='lastpage'>&raquo;</a></li>");
    }
    
    for (var x = 0; x <= total; x++){
      $(class_page + (x + 1)).removeClass("active");
    }
    $(class_page + page).addClass("active");
  };

  $(document).on('click', 'a.page', function (event) {
    event.preventDefault();
    page = $(this).html();
    get_data(page);
  });

  $(document).on('click', 'a.firstpage', function (event) {
    event.preventDefault();
    page = 1;
    get_data(page);
  });

  $(document).on('click', 'a.lastpage', function (event) {
    event.preventDefault();
    page = last_page;
    get_data(page);
  });
  //End Function Write Paging
  
  //Bind Enter Key
  $('#main_panel').bind('keypress', function (e) {
    if (e.keyCode === 13) {
      get_data(page);
    }
  });
  //End Bind Enter Key
  
  //Default AJAX Setting
  $(document).ajaxStart(function() {
    $(document.body).overlay();
  });
  
  $(document).ajaxStop(function() {
    $(document.body).overlayout();
  });
  //End AJAX Setting
});