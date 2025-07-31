<?php

use App\Http\Controllers\backendController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\taskController;
use App\Http\Controllers\userController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    

    // Admin Routes
    Route::get('/', [backendController::class, 'dashboard'])->name('dashboard');

    // User Routes
    Route::get('/profile/edit/', [userController::class, 'profile_edit'])->name('user.edit');
    Route::post('/profile/update/', [userController::class, 'profile_update'])->name('user.update');
    Route::post('/password/update/', [userController::class, 'user_pass'])->name('user.pass');
    Route::post('/profile/photo', [userController::class, 'user_photo'])->name('user.photo');
    Route::get('/users', [userController::class, 'user_view'])->name('user.view');
    Route::get('/users/delete/{id}', [userController::class, 'user_del'])->name('user.del');
    Route::post('/users/store', [userController::class, 'user_store'])->name('user.store');

    // Task Routes
    Route::get('/task/view', [taskController::class, 'task_view'])->name('task.view');
    Route::post('/task/add', [taskController::class, 'task_add'])->name('task.add');
    Route::get('/task/delete/{task_id}', [taskController::class, 'task_del'])->name('task.del');
    Route::get('/my/task', [taskController::class, 'my_task'])->name('my.task');
    Route::post('/task/comment', [taskController::class, 'task_comment'])->name('task.comment');

    // Category Routes
    Route::get('/category/view',[CategoryController::class, 'category_view'])->name('category.view');
    Route::post('/category/store',[CategoryController::class, 'category_store'])->name('category.store');
    Route::post('/category/edit',[CategoryController::class, 'category_edit'])->name('category.edit');
    Route::get('/category/delete/{id}',[CategoryController::class, 'category_delete'])->name('category.delete');
    Route::get('/category/trash',[CategoryController::class, 'category_trash'])->name('category.trash');
    Route::get('/category/trash/delete/{id}',[CategoryController::class, 'category_trash_delete'])->name('category.trash.delete');
    Route::get('/category/restore/{id}',[CategoryController::class, 'category_restore'])->name('category.restore');
    Route::post('/category/checked/delete',[CategoryController::class, 'category_check'])->name('category.check');
    Route::post('/category/trash/check/delete',[CategoryController::class, 'trash_category_check'])->name('trash.category.check');

    // Subcategory Routes
    Route::post('/subcategory/store', [SubcategoryController::class, 'subcategory_store'])->name('subcategory.store');
    Route::get('/subcategory/delete/{id}', [SubcategoryController::class, 'subcategory_delete'])->name('subcategory.delete');
    Route::get('/subcategory/restore/{id}', [SubcategoryController::class, 'subcategory_restore'])->name('subcategory.restore');
    Route::get('/subcategory/trash/delete/{id}', [SubcategoryController::class, 'subcategory_trash_delete'])->name('subcategory.trash.delete');
    Route::post('subcategory/trash/check/delete', [SubcategoryController::class, 'trash_subcategory_check'])->name('trash.subcategory.check');
    Route::post('/subcategory/edit', [SubcategoryController::class, 'subcategory_edit'])->name('subcategory.edit');

    // Brand Routes
    Route::get('/brand/view', [BrandController::class, 'brand_view'])->name('brand.view');
    Route::post('/brand/store', [BrandController::class, 'brand_store'])->name('brand.store');
    Route::get('/brand/delete/{id}', [BrandController::class, 'brand_delete'])->name('brand.delete');
    Route::post('/brand/edit', [BrandController::class, 'brand_edit'])->name('brand.edit');
    Route::post('/brand/check', [BrandController::class, 'brand_check'])->name('brand.check');
    Route::get('/brand/restore/{id}', [BrandController::class, 'brand_restore'])->name('brand.restore');
    Route::get('/brand/trash/delete/{id}', [BrandController::class, 'brand_trash_delete'])->name('brand.trash.delete');
    Route::post('/brand/trash/check/delete', [BrandController::class, 'trash_brand_check'])->name('trash.brand.check');

    //Tag Routes
    Route::get('/tag/view', [TagController::class, 'tag_view'])->name('tag.view');
    Route::post('/tag/store', [TagController::class, 'tag_store'])->name('tag.store');
    Route::get('/tag/delete/{id}', [TagController::class, 'tag_delete'])->name('tag.delete');
    Route::get('/tag/trash/delete/{id}', [TagController::class, 'tag_trash_delete'])->name('tag.trash.delete');
    Route::post('/tag/check/delete', [TagController::class, 'tag_check_delete'])->name('tag.check.delete');
    Route::get('/tag/restore/{id}', [TagController::class, 'tag_restore'])->name('tag.restore');
    Route::post('/tag/trash/action', [TagController::class, 'tag_trash_action'])->name('tag.trash.action');

    // Products Routes
    Route::get('/product',[ProductController::class, 'product_view'])->name('product.view');
    Route::post('/getSubcategory',[ProductController::class, 'getSubcategory']);
    Route::get('/product/store/view',[ProductController::class, 'product_store_view'])->name('product.store.view');
    Route::post('/product/store',[ProductController::class, 'product_store'])->name('product.store');
    Route::get('/product/delete/{id}',[ProductController::class, 'product_delete'])->name('product.delete');
    Route::get('/product/restore/{id}',[ProductController::class, 'product_restore'])->name('product.restore');
    Route::get('/product/trash/delete/{id}',[ProductController::class, 'product_trash_delete'])->name('product.trash.delete');
    Route::get('/product/edit/{id}',[ProductController::class, 'product_edit'])->name('product.edit');
    Route::post('/product/update/{id}',[ProductController::class, 'product_update'])->name('product.update');
    Route::get('/product/variation',[ProductController::class, 'product_variation'])->name('product.variation');
    Route::post('/color/store',[ProductController::class, 'color_store'])->name('color.store');
    Route::post('/size/store',[ProductController::class, 'size_store'])->name('size.store');
    Route::get('/size/delete/{id}',[ProductController::class, 'size_del'])->name('size.del');
    Route::get('/color/delete/{id}',[ProductController::class, 'color_del'])->name('color.del');
    Route::get('/inventory/{id}',[ProductController::class, 'product_inventory'])->name('product.inventory');
    Route::post('/inventory/store/{id}',[ProductController::class, 'inventory_store'])->name('inventory.store');
    Route::get('/inventory/delete/{id}',[ProductController::class, 'inventory_del'])->name('inventory.del');
    Route::post('/getGalImage',[ProductController::class, 'getGalImage']);

    // Coupon Routes
    Route::get('/coupon', [CouponController::class, 'coupon'])->name('coupon');
    Route::post('/coupon/store', [CouponController::class, 'coupon_store'])->name('coupon.store');
    Route::get('/coupon/delete/{id}', [CouponController::class, 'coupon_delete'])->name('coupon.delete');

    // Role Routes
    Route::get('/role/view', [RoleController::class, 'role_view'])->name('role.view');
    Route::post('/permission/store', [RoleController::class, 'permission_store'])->name('permission.store');
    Route::get('/permission/delete/{id}', [RoleController::class, 'permission_delete'])->name('permission.delete');
    Route::post('/role/store', [RoleController::class, 'role_store'])->name('role.store');
    Route::post('/role/assign', [RoleController::class, 'role_assign'])->name('role.assign');
    Route::get('/role/edit/{id}', [RoleController::class, 'role_edit'])->name('role.edit');
    Route::post('/role/sync/{id}', [RoleController::class, 'role_sync'])->name('role.sync');
    Route::post('/role/delete/{id}', [RoleController::class, 'role_delete'])->name('role.delete');
    Route::get('/role/unassign/{id}', [RoleController::class, 'role_unassign'])->name('role.unassign');

    // Order Routes
    Route::get('/orders', [OrderController::class, 'order_view'])->name('order.view');
    Route::post('/order/status/{id}', [OrderController::class, 'order_status'])->name('order.status');
    Route::get('/order/info/{order_id}', [OrderController::class, 'order_info'])->name('order.info');

});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
