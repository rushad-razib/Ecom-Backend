<?php 

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('index', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('index'));
});

Breadcrumbs::for('product.landing', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Product Details', route('product.landing', ''));
});
Breadcrumbs::for('cart', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Cart', route('cart'));
});
Breadcrumbs::for('checkout', function (BreadcrumbTrail $trail) {
    $trail->parent('cart');
    $trail->push('Checkout', route('checkout'));
});
Breadcrumbs::for('order.confirmation', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Confirmation', route('order.confirmation'));
});
Breadcrumbs::for('recent.view', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Recent View', route('recent.view'));
});
Breadcrumbs::for('shop', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Shop', route('shop'));
});

Breadcrumbs::for('customer.profile', function (BreadcrumbTrail $trail) {
    $trail->push('Profile', route('customer.profile'));
});

Breadcrumbs::for('customer.orders', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.profile');
    $trail->push('Orders', route('customer.orders'));
});
Breadcrumbs::for('customer.order.cancel', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.orders');
    $trail->push('Order Cancellation', route('customer.order.cancel', ''));
});
Breadcrumbs::for('contact', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Contact', route('contact'));
});
Breadcrumbs::for('faq', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('FAQ', route('faq'));
});