<div class="side-menu">
  <div id="sidebar-menu">
    <ul>
        <!-- <li class="text-muted menu-title">Navigation</li> -->

        <?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 49) { ?>
            <li class="has_sub">
                <a href="<?= base_url() ?>admin/dashboard" class="waves-effect <?php
                    if ($active_page == "dashboard") {
                        echo "active";
                    }
                    ?>"><i class="fa fa-dashboard"></i> <span> Dashboard </span> 
                </a>
            </li>

            <li class="has_sub">
                <a href="javascript:void(0);" class="waves-effect opensub <?php
                if ($active_page == "category") {
                    echo "active";
                }
                ?>" data-id="users"><i class="fa fa-user" aria-hidden="true"></i> <span> Category </span> <span class="menu-arrow"></span> </a>
                <ul class="list-unstyled" id="users">
                    <li><a href="<?= base_url() ?>admin/addcategory">Add Category</a></li>
                    <li><a href="<?= base_url() ?>admin/viewcategory">View Category</a></li>
                </ul>
            </li>

            <li class="has_sub">
                <a href="javascript:void(0);" class="waves-effect opensub <?php
                if ($active_page == "questions") {
                    echo "active";
                }
                ?>" data-id="questions"><i class="fa fa-user" aria-hidden="true"></i> <span> Questions </span> <span class="menu-arrow"></span> </a>
                <ul class="list-unstyled" id="questions">
                    <li><a href="<?= base_url() ?>admin/addquestions">Add Questions</a></li>
                    <li><a href="<?= base_url() ?>admin/addnewquestions">Add New Questions</a></li>
                    <li><a href="<?= base_url() ?>admin/viewquestions">View Questions</a></li>
                    <li><a href="<?= base_url() ?>admin/viewnewquestions">View New Questions</a></li>
                </ul>
            </li>

            <li class="has_sub">
                <a href="javascript:void(0);" class="waves-effect opensub <?php if ($active_page == "user") {echo "active"; } ?>" data-id="user">
                  <i class="fa fa-user" aria-hidden="true"></i> 
                  <span> Tribe Member </span> 
                  <span class="menu-arrow"></span> 
                </a> 
                <ul class="list-unstyled" id="user">
                    <li><a href="<?= base_url() ?>admin/adduser">Add Tribe Member</a></li>
                    <li><a href="<?= base_url() ?>admin/viewuser">View Tribe Member</a></li>
                </ul>
            </li>
        <?php } ?>
        <?php if ($_SESSION['role'] == 1) { ?>

            <li class="has_sub">
                <a href="javascript:void(0);" class="waves-effect opensub <?php if ($active_page == "qamember") {echo "active"; } ?>" data-id="qamember">
                  <i class="fa fa-user" aria-hidden="true"></i> 
                  <span> QA Member </span> 
                  <span class="menu-arrow"></span> 
                </a> 
                <ul class="list-unstyled" id="qamember">
                  <!--  <li><a href="<?= base_url() ?>admin/adduser">Add Tribe Member</a></li>-->
                    <li><a href="<?= base_url() ?>admin/viewqamember">View QA Member</a></li>
                </ul>
            </li>
            <li class="has_sub">
                <a href="<?= base_url() ?>admin/paused_account" class="waves-effect <?php if ($active_page == "paused_account") {echo "active"; } ?>"><i class="fa fa-dashboard"></i> <span> Paused Account </span> </a>
            </li>
            <li class="has_sub">
                <a href="<?= base_url() ?>admin/view_previous" class="waves-effect <?php if ($active_page == "viewprevious") {echo "active"; } ?>"><i class="fa fa-dashboard"></i> <span> View Previous </span> </a> </li>

        <?php } ?>
        <?php if ($_SESSION['role'] == 2) { ?>
            <li class="has_sub">
              <a href="<?= base_url() ?>newdashboard" class="waves-effect <?php if ($active_page == "dashboard") {echo "active"; } ?>"><i class="fa fa-dashboard"></i> <span> Dashboard </span> </a>
            </li>
            <li class="has_sub">
                <a href="javascript:void(0);" class="waves-effect opensub <?php if ($active_page == "changepassword") {echo "active"; } ?>" data-id="changepassword"><i class="fa fa-user" aria-hidden="true"></i>
                  <span> Tribe Member </span>
                  <span class="menu-arrow"></span> 
                </a>
                <ul class="list-unstyled" id="changepassword">
                    <li><a href="<?= base_url() ?>changepassword">Change Password</a></li>
                    <li><a href="<?= base_url() ?>changepassword">Change Profile</a></li>
                </ul>
            </li>
            <li class="has_sub">
              <a href="<?= base_url() ?>pages/view_previous" class="waves-effect <?php if ($active_page == "viewprevious") {echo "active"; } ?>"><i class="fa fa-dashboard"></i> <span> View Previous </span> </a>
            </li>            
        <?php } ?>

        <?php if ($_SESSION['role'] == 49) { ?>
          <li class="has_sub">
            <a href="<?= base_url() ?>qamember/view_previous" class="waves-effect <?php if ($active_page == "viewprevious") { echo "active"; }?>"><i class="fa fa-dashboard"></i> <span> View Previous </span> </a>
          </li>
        <?php } ?>        
    </ul>
  </div>
</div>
