<li class="dropdown user user-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-user"></i>
    <span><?php echo $this->session->userdata('username') ?> <i class="caret"></i></span>
  </a>
  <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
    <li class="dropdown-header text-center">Account</li>
    <li>
      <a id="btn_logout" href="#"><i class="fa fa-ban fa-fw pull-right"></i> Logout</a>
    </li>
  </ul>
</li>