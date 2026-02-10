<?php
function renderFeatureCard($group)
{
    echo '<section class="card">';
    echo '<h3>' . htmlspecialchars($group['title']) . '</h3>';
    echo '<ul>';
    foreach ($group['items'] as $item) {
        echo '<li>' . htmlspecialchars($item) . '</li>';
    }
    echo '</ul>';
    echo '</section>';
}
