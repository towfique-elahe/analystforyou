<?php
$current_user = wp_get_current_user();
?>
<div class="sidebar">
    <div class="top">
        <p class="welcome-message">
            Welcome, <strong>
                <?php echo esc_html(trim("{$current_user->first_name} {$current_user->last_name}") ?: $current_user->display_name); ?>
            </strong>
        </p>
        <ul class="menu">
            <li>
                <a class="menu-item <?php if (is_page('candidate-dashboard')) echo 'active'; ?>"
                    href="<?php echo esc_url(site_url('/candidate-dashboard')); ?>">
                    <ion-icon name="home-outline"></ion-icon>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a class="menu-item <?php if (is_page('candidate-profile')) echo 'active'; ?>"
                    href="<?php echo esc_url(site_url('/candidate-profile')); ?>">
                    <ion-icon name="person-outline"></ion-icon>
                    <span class="text">Profile Management</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="bottom">
        <ul class="menu">
            <li>
                <a class="menu-item logout" href="<?php echo wp_logout_url(site_url('/login')); ?>">
                    <ion-icon name="log-out-outline"></ion-icon>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>