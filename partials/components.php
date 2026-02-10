<?php
function renderFeatureCard(array $group): void
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

function renderMetric(array $metric): void
{
    echo '<article class="metric">';
    echo '<p class="metric-value">' . htmlspecialchars($metric['value']) . '</p>';
    echo '<p class="metric-label">' . htmlspecialchars($metric['label']) . '</p>';
    echo '</article>';
}

function renderValueCard(array $item): void
{
    echo '<article class="value-card">';
    echo '<h3>' . htmlspecialchars($item['title']) . '</h3>';
    echo '<p>' . htmlspecialchars($item['desc']) . '</p>';
    echo '</article>';
}

function renderPlanCard(array $plan): void
{
    echo '<article class="plan-card">';
    echo '<h3>' . htmlspecialchars($plan['name']) . '</h3>';
    echo '<p class="price">' . htmlspecialchars($plan['price']) . '</p>';
    echo '<ul>';
    foreach ($plan['features'] as $feature) {
        echo '<li>' . htmlspecialchars($feature) . '</li>';
    }
    echo '</ul>';
    echo '<button type="button">Paketi İncele</button>';
    echo '</article>';
}

function renderFaq(array $faq): void
{
    echo '<details class="faq-item">';
    echo '<summary>' . htmlspecialchars($faq['q']) . '</summary>';
    echo '<p>' . htmlspecialchars($faq['a']) . '</p>';
    echo '</details>';
}
