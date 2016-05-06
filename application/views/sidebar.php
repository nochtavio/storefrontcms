<ul class="sidebar-menu">
  <li class="<?php echo ($page == "Dashboard") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>dashboard/"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
  <?php 
    if(check_menu("Admin", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Admin") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>admin/"><i class="fa fa-user"></i> <span>Admin</span></a></li>  
      <?php
    }
  ?>
  <?php 
    if(check_menu("Brand", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Brand") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>brand/"><i class="fa fa-leaf"></i> <span>Brand</span></a></li>
      <?php
    }
  ?>
  <?php 
    if(check_menu("Category", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Category") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>category/"><i class="fa fa-bell"></i> <span>Category</span></a></li>
      <?php
    }
  ?>
  <?php 
    if(check_menu("Color", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Color") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>color/"><i class="fa fa-tint"></i> <span>Color</span></a></li>
      <?php
    }
  ?>
  <?php 
    if(check_menu("Credit_log", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Credit_log") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>credit_log/"><i class="fa fa-money"></i> <span>Credit Log</span></a></li> 
      <?php
    }
  ?>
  <?php 
    if(check_menu("Customer", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Customer") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>customer/"><i class="fa fa-users"></i> <span>Customer</span></a></li> 
      <?php
    }
  ?>
  <?php 
    if(check_menu("Customer_return", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Customer_return") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>customer_return/"><i class="fa fa-history"></i> <span>Customer Return</span></a></li> 
      <?php
    }
  ?>
  <?php 
    if(check_menu("Inventory_correction", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Inventory_correction") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>inventory_correction/"><i class="fa fa-check-square"></i> <span>Inventory Correction</span></a></li> 
      <?php
    }
  ?>
  <?php 
    if(check_menu("Order", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Order") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>order/"><i class="fa fa-shopping-bag"></i> <span>Order</span></a></li> 
      <?php
    }
  ?>
  <?php 
    if(check_menu("Payment", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Payment") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>payment/"><i class="fa fa-credit-card"></i> <span>Payment</span></a></li>
      <?php
    }
  ?>
  <?php 
    if(check_menu("Products", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Products") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>products/"><i class="fa fa-gavel"></i> <span>Products</span></a></li> 
      <?php
    }
  ?>
  <?php 
    if(check_menu("Reseller", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Reseller") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>reseller/"><i class="fa fa-user-plus"></i> <span>Reseller</span></a></li> 
      <?php
    }
  ?>
  <?php 
    if(check_menu("Reseller_request", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Reseller_request") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>reseller_request/"><i class="fa fa-tty"></i> <span>Reseller Request</span></a></li> 
      <?php
    }
  ?>
  <?php 
    if(check_menu("Shipping", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Shipping") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>shipping/"><i class="fa fa-paper-plane"></i> <span>Shipping</span></a></li>
      <?php
    }
  ?>
  <?php 
    if(check_menu("Slider", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Slider") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>slider/"><i class="fa fa-object-group"></i> <span>Slider</span></a></li>
      <?php
    }
  ?>
  <?php 
    if(check_menu("Static_content", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Static_content") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>static_content/"><i class="fa fa-bookmark"></i> <span>Static Content</span></a></li>  
      <?php
    }
  ?>
  <?php 
    if(check_menu("Voucher", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Voucher") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>voucher/"><i class="fa fa-money"></i> <span>Voucher</span></a></li> 
      <?php
    }
  ?>
</ul>