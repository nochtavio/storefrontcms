$(document).ready(function(){
  //Function Write Paging
  writePaging = function (totalpage, page){
    if (totalpage > 1){
      var initial = 0;
      if (parseInt(page) - parseInt(4) > 1){
        initial = parseInt(page) - parseInt(5);
      }else{
        initial = 0;
      }
      
      var total = 0;
      if (parseInt(page) + parseInt(4) <= totalpage){
        total = parseInt(page) + parseInt(4);
      }else{
        total = parseInt(totalpage);
      }
      
      $('#paging').append("<li><a href='#' class='firstpage'>&laquo;</a></li>");
      for (var y = initial; y < total; y++){
        $('#paging').append("<li class='page" + (y + 1) + "'><a href='#' class='page'>" + (y + 1) + "</a></li>");
      }
      $('#paging').append("<li><a href='#' class='lastpage'>&raquo;</a></li>");
    }
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

  //Function Clear Paging Class
  clearPagingClass = function (css, total, page){
    for (var x = 0; x <= total; x++){
      $(css + (x + 1)).removeClass("active");
    }
    $(css + page).addClass("active");
  };
  //End Function Clear Paging Class
  
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