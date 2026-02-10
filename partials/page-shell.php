<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/components.php';
require_once __DIR__ . '/../lib/Repository.php';

function renderPanelPage(string $title, string $description, array $metrics, array $groups, array $actions): void
{
    echo '<section class="section container">';
    echo '<h1>' . htmlspecialchars($title) . '</h1>';
    echo '<p class="subtitle">' . htmlspecialchars($description) . '</p>';
    echo '<div class="metrics-grid">';
    foreach ($metrics as $metric) {
        renderMetric($metric);
    }
    echo '</div>';
    echo '<div class="quick-actions">';
    foreach ($actions as $action) {
        echo '<a class="btn ghost" href="' . htmlspecialchars($action['href']) . '">' . htmlspecialchars($action['label']) . '</a>';
    }
    echo '</div>';
    echo '<div class="grid three">';
    foreach ($groups as $group) {
        renderFeatureCard($group);
    }
    echo '</div>';
    echo '</section>';
}
