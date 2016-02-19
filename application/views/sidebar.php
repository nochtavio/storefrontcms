<ul class="sidebar-menu">
  <li class="<?php echo ($page == "Dashboard") ? "active" : "" ; ?>"><a href="<?php echo base_url() ?>dashboard/"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
  <li class="<?php echo ($page == "Admin") ? "active" : "" ; ?>"><a href="<?php echo base_url() ?>admin/"><i class="fa fa-user"></i> <span>Admin</span></a></li>
  <li class="<?php echo ($page == "Color") ? "active" : "" ; ?>"><a href="<?php echo base_url() ?>color/"><i class="fa fa-tint"></i> <span>Color</span></a></li>
  <li class="<?php echo ($page == "Category") ? "active" : "" ; ?>"><a href="<?php echo base_url() ?>category/"><i class="fa fa-bell"></i> <span>Category</span></a></li>
  <li class="<?php echo ($page == "Products") ? "active" : "" ; ?>"><a href="<?php echo base_url() ?>products/"><i class="fa fa-gavel"></i> <span>Products</span></a></li>
</ul>