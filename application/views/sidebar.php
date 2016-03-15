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
    if(check_menu("Static_content", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Static_content") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>static_content/"><i class="fa fa-bookmark"></i> <span>Static Content</span></a></li>  
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
    if(check_menu("Color", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Color") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>color/"><i class="fa fa-tint"></i> <span>Color</span></a></li>
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
    if(check_menu("Brand", $type = 0)){
      ?>
        <li class="<?php echo ($page == "Brand") ? "active" : ""; ?>"><a href="<?php echo base_url() ?>brand/"><i class="fa fa-leaf"></i> <span>Brand</span></a></li>
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
</ul>