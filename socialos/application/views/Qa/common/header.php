<?php
$uri_seg = $this->uri->segment(2);
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
        <li title="Dashboard" class="<?php if ($uri_seg == "index") echo "active"; ?>">
            <a href="<?= site_url('Qa/index') ?>">
                <i class="icon-home2"></i>
            </a>
        </li>
        <li title="Assessment" class="<?php if ($uri_seg == "assessment") echo "active"; ?>">
            <a href="<?= site_url('Qa/assessment') ?>">
                <i class="icon-meter"></i>
            </a>
        </li>
        <li title="Squad Session" class="<?php if ($uri_seg == "session") echo "active"; ?>">
            <a href="<?= site_url('Qa/session') ?>">
                <i class="icon-calendar"></i>
            </a>
        </li>
        <li title="My Performance" class="<?php if ($uri_seg == "performance") echo "active"; ?>">
            <a href="<?= site_url('Qa/performance') ?>">
                <i class="icon-heart3"></i>
            </a>
        </li>
        <!--<li title="Achievements" class="<?php if ($uri_seg == "achievements") echo "active"; ?>">
            <a href="<?= site_url('squad/achievements') ?>">
                <i class="icon-trophy-star"></i>
            </a>
        </li>-->
    </ul>

    <ul class="nav navbar-nav navbar-right collapse" id="navbar-icons">
        <li title="Settings"><a href="<?= site_url('Qa/setting'); ?>"><i class="icon-sun2"></i></a></li>
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
        <li title="Dashboard" class="<?php if ($uri_seg == "index") echo "active"; ?>">
            <a href="<?= site_url('Qa/index') ?>">
                Dashboard
            </a>
        </li>
        <li title="Assessment" class="<?php if ($uri_seg == "assessment") echo "active"; ?>">
            <a href="<?= site_url('Qa/assessment') ?>">
                Assessments
            </a>
        </li>
        <li title="Squad Session" class="<?php if ($uri_seg == "session") echo "active"; ?>">
            <a href="<?= site_url('Qa/session') ?>">
                Squad Session
            </a>
        </li>
        <li title="My Performance" class="<?php if ($uri_seg == "performance") echo "active"; ?>">
            <a href="<?= site_url('Qa/performance') ?>">
                My Performance
            </a>
        </li>
        <!--<li title="Achievements" class="<?php if ($uri_seg == "achievements") echo "active"; ?>">
            <a href="<?= site_url('squad/achievements') ?>">
                Achievements
            </a>
        </li>-->
    </ul>

    <!--<ul class="nav navbar-nav navbar-right collapse" id="navbar-menu-right">
        <li><a onclick="return confirm('Are you sure you want to pause account?');" href="<?= site_url('squad/pause_account'); ?>"><i class="icon-pause pull-right"></i> Pause Account</a></li>
    </ul>-->
</div>