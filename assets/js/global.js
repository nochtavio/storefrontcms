$(document).ready(function(){
  //Function Write Paging
  writePaging = function (total_page, page, class_page){
    $('#paging').empty();
    if (total_page > 1){
      var initial = (parseInt(page) - parseInt(4) > 1) ? parseInt(page) - parseInt(5) : 0 ;
      var total = (parseInt(page) + parseInt(4) <= total_page) ? parseInt(page) + parseInt(4) : parseInt(total_page);
      
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
  
  //Real Time Info
  global_real_time_interval = 60000; //1 Minute
  
  real_time_order = function() {
    $.ajax({
      url: base_url + 'realtime/get_unread_order',
      type: 'POST',
      global: false,
      data: {},
      dataType: 'json',
      success: function (result) {
        if(result['result'] == 'r1'){
          $('#realtime-order').show();
          $('#realtime-order').html(result['unread_data']);
        }else{
          $('#realtime-order').hide();
        }
      }
    });
  };
  
  real_time_credit_log = function() {
    $.ajax({
      url: base_url + 'realtime/get_unread_credit_log',
      type: 'POST',
      global: false,
      data: {},
      dataType: 'json',
      success: function (result) {
        if(result['result'] == 'r1'){
          $('#realtime-creditlog').show();
          $('#realtime-creditlog').html(result['unread_data']);
        }else{
          $('#realtime-creditlog').hide();
        }
      }
    });
  };
  
  real_time_customer_return = function() {
    $.ajax({
      url: base_url + 'realtime/get_unread_customer_return',
      type: 'POST',
      global: false,
      data: {},
      dataType: 'json',
      success: function (result) {
        if(result['result'] == 'r1'){
          $('#realtime-customerreturn').show();
          $('#realtime-customerreturn').html(result['unread_data']);
        }else{
          $('#realtime-customerreturn').hide();
        }
      }
    });
  };
  
  real_time_reseller_request = function() {
    $.ajax({
      url: base_url + 'realtime/get_unread_reseller_request',
      type: 'POST',
      global: false,
      data: {},
      dataType: 'json',
      success: function (result) {
        if(result['result'] == 'r1'){
          $('#realtime-resellerrequest').show();
          $('#realtime-resellerrequest').html(result['unread_data']);
        }else{
          $('#realtime-resellerrequest').hide();
        }
      }
    });
  };
  
  real_time_order();
  real_time_credit_log();
  real_time_customer_return();
  real_time_reseller_request();
  
  setInterval(real_time_order, global_real_time_interval);
  setInterval(real_time_credit_log, global_real_time_interval);
  setInterval(real_time_customer_return, global_real_time_interval);
  setInterval(real_time_reseller_request, global_real_time_interval);
  //End Real Time Info
  
  //Logout
  $('#btn_logout').click(function(event){
    event.preventDefault();
    $.ajax({
      url: base_url + 'login/logout',
      type: 'POST',
      data: {
        
      },
      dataType: 'json',
      success: function (result) {
        window.location = base_url+"login/";
      }
    });
  });
  //End Logout
});