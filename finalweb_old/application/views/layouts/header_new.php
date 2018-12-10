<?php
$uri_seg_val = $this->uri->segment(2);
?>

<div class="navbar navbar-inverse" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-icons">
            <span class="sr-only">Toggle right icons</span>
            <i class="icon-grid"></i>
        </button>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
            <span class="sr-only">Toggle menu</span>
            <i class="icon-paragraph-justify2"></i>
        </button>
    </div>

    <ul class="nav navbar-nav collapse" id="navbar-menu">
        <li title="Dashboard" class="<?php if ($uri_seg_val == "index") echo "active"; ?>">
            <a href="<?= site_url('squad/index') ?>">
                <i class="icon-home2"></i>
            </a>
        </li>
        <li title="Assessment" class="<?php if ($uri_seg_val == "assessment") echo "active"; ?>">
            <a href="<?= site_url('squad/assessment') ?>">
                <i class="icon-meter"></i>
            </a>
        </li>
        <li title="Learning and Reflection" class="<?php if ($uri_seg_val == "learning") echo "active"; ?>">
            <a href="<?= site_url('squad/learning') ?>">
                <i class="icon-headphones"></i>
            </a>
        </li>
        <li title="Squad Session" class="<?php if ($uri_seg_val == "session") echo "active"; ?>">
            <a href="<?= site_url('squad/session') ?>">
                <i class="icon-calendar"></i>
            </a>
        </li>
        <!--<li title="To Do's" class="<?php if ($uri_seg_val == "mytodo") echo "active"; ?>">
            <a href="<?= site_url('squad/mytodo') ?>">
                <i class="icon-heart3"></i>
            </a>
        </li>-->
        <!--<li title="Achievements" class="<?php if ($uri_seg_val == "achievements") echo "active"; ?>">
            <a href="<?= site_url('squad/achievements') ?>">
                <i class="icon-trophy-star"></i>
            </a>
        </li>-->
    </ul>

    <ul class="nav navbar-nav navbar-right collapse" id="navbar-icons">
        <li title="Settings"><a href="<?= site_url('squad/settings'); ?>"><i class="icon-sun2"></i></a></li>
        <li title="Logout"><a href="<?php echo site_url('auth/logout'); ?>"><i class="icon-exit"></i></a></li>
    </ul>
</div>

<div class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu-right">
            <span class="sr-only">Toggle navbar</span>
            <i class="icon-grid3"></i>
        </button>

        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu-left">
            <span class="sr-only">Toggle navigation</span>
            <i class="icon-paragraph-justify2"></i>
        </button>
    </div>

    <ul class="nav navbar-nav collapse" id="navbar-menu-left">
        <li title="Dashboard" class="<?php if ($uri_seg_val == "index") echo "active"; ?>">
            <a href="<?= site_url('squad/index') ?>">
                Dashboard
            </a>
        </li>
        <li title="Assessment" class="<?php if ($uri_seg_val == "assessment" or $uri_seg_val == "addrating") echo "active"; ?>">
            <a href="<?= site_url('squad/assessment') ?>">
                Assessments
            </a>
        </li>
        <li title="Learning and Reflection" class="<?php if ($uri_seg_val == "learning") echo "active"; ?>">
            <a href="<?= site_url('squad/learning') ?>">
                Learning and Reflection
            </a>
        </li>
        <li title="Squad Session" class="<?php if ($uri_seg_val == "session") echo "active"; ?>">
            <a href="<?= site_url('squad/session') ?>">
                Squad Session
            </a>
        </li>
        <!--<li title="To Do's" class="<?php if ($uri_seg_val == "mytodo") echo "active"; ?>">
            <a href="<?= site_url('squad/mytodo') ?>">
                My To Do’s
            </a>
        </li>
        <li title="Achievements" class="<?php if ($uri_seg_val == "achievements") echo "active"; ?>">
            <a href="<?= site_url('squad/achievements') ?>">
                Achievements
            </a>
        </li>-->
    </ul>

    <ul class="nav navbar-nav navbar-right collapse" id="navbar-menu-right">
        <?php if ($this->is_paused) { ?>
            <li><a href="<?= site_url('squad/unpause_account'); ?>"><i class="icon-play2 pull-right"></i> UnPause Account</a></li>
        <?php } else { ?>
            <li><a onclick="return confirm('Are you sure you want to pause account?');" href="<?= site_url('squad/pause_account'); ?>"><i class="icon-pause pull-right"></i> Pause Account</a></li>
            <?php } ?>
    </ul>
</div>