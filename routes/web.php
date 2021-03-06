<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 首頁 + 登入

Route::resource('/', 'IndexController');

Route::get('index', ['as'=>'index','uses'=>'IndexController@index']);

Route::post('sendmail', ['as'=>'index.sendmail','uses'=>'IndexController@sendmail']);

Route::get('admin_index', ['as'=>'admin_index','uses'=>'IndexController@admin_index']);

Route::get('login', ['as'=>'login.index','uses'=>'Auth\LoginController@index']);

Route::get('logout', ['as'=>'login.index','uses'=>'Auth\LoginController@logout']);

Route::post('login', ['as'=>'login.process','uses'=>'Auth\LoginController@process']);

Route::post('refresh', ['as'=>'login.refresh','uses'=>'Auth\LoginController@refresh']);


// 註冊

Route::get('register', ['as'=>'register.index','uses'=>'Auth\RegisterController@index']);

Route::post('register', ['as'=>'register.register_process','uses'=>'Auth\RegisterController@register_process']);

Route::get('register_finish', ['as'=>'register.register_finish','uses'=>'Auth\RegisterController@register_finish']);

Route::get('forgot_password', ['as'=>'login.register_finish','uses'=>'Auth\ForgotPasswordController@index']);

Route::post('forgot_password', ['as'=>'login.register_finish','uses'=>'Auth\ForgotPasswordController@process']);

Route::get('reset', ['as'=>'login.reset','uses'=>'Auth\ForgotPasswordController@reset']);

Route::post('social_process', ['as'=>'social.login','uses'=>'Auth\RegisterController@social_process']);


// 使用者

Route::resource('user', 'UserController');

Route::post('extend_user_process', ['as'=>'admin.extend_user_process','uses'=>'UserController@extend_user_process']);

Route::post('invite_friend', ['as'=>'admin.invite_friend','uses'=>'UserController@send_invite_mail']);

// photo

Route::get('photo', ['as'=>'user.photo_index','uses'=>'UserController@photo_index']);

Route::post('photo_upload_process', ['as'=>'user.photo_upload_process','uses'=>'UserController@photo_upload_process']);

// 角色

Route::resource('role', 'RoleController');

// 服務

Route::resource('service', 'ServiceController');

Route::get('service_public', ['as'=>'service.public','uses'=>'ServiceController@service_public']);

Route::get('service_public_process', ['as'=>'service.public_process','uses'=>'ServiceController@service_public_process']);


// record

Route::get('record', ['as'=>'record.index','uses'=>'RecordController@index']);


// msg

Route::resource('msg', 'MsgController');

Route::get('msg_clone', ['as'=>'msg.clone','uses'=>'MsgController@clone']);

// store

Route::resource('store', 'StoreController');

Route::post('change_store', ['as'=>'store.change','uses'=>'StoreController@change_store']);

Route::post('extend_store_process', ['as'=>'admin.extend_store_process','uses'=>'StoreController@extend_store_process']);

Route::post('get_child_store', ['as'=>'store.get_child_store','uses'=>'StoreController@get_child_store']);

// mall

Route::resource('mall', 'MallController');

// shop

Route::get('buy', ['as'=>'shop.buy','uses'=>'ShopController@index']);

Route::get('buy_record', ['as'=>'shop.service_buy_record','uses'=>'ShopController@shop_record']);

Route::get('property_list', ['as'=>'shop.property_list','uses'=>'ShopController@property_list']);

Route::post('shop_buy_process', ['as'=>'shop.service_buy_process','uses'=>'ShopController@shop_buy_process']);

Route::post('get_mall_product', ['as'=>'shop.get_mall_product','uses'=>'ShopController@get_mall_product']);

Route::post('get_extend_deadline_option', ['as'=>'shop.get_extend_deadline_option','uses'=>'ShopController@get_extend_deadline_option']);

Route::post('DataReceive', ['as'=>'shop.DataReceive','uses'=>'ShopController@DataReceive']);


// product

Route::resource('product', 'ProductController');

Route::post('get_product_spec', ['as'=>'product.get_product_spec','uses'=>'ProductController@get_product_spec']);


// purchase

Route::resource('purchase', 'PurchaseController');

Route::post('purchase_verify', ['as'=>'purchase.purchase_verify','uses'=>'PurchaseController@verify']);

// stock

Route::get('stock_batch_list', ['as'=>'stock.stock_batch_list','uses'=>'StockController@stock_batch_list']);

Route::get('stock_total_list', ['as'=>'stock.stock_total_list','uses'=>'StockController@stock_total_list']);

Route::get('immediate_stock_list', ['as'=>'stock.immediate_stock_list','uses'=>'StockController@immediate_stock_list']);

Route::get('lack_of_stock_list', ['as'=>'stock.lack_of_stock_list','uses'=>'StockController@lack_of_stock_list']);

// order

Route::resource('order', 'OrderController');

Route::post('order_verify', ['as'=>'order.order_verify','uses'=>'OrderController@verify']);

Route::get('order_output', ['as'=>'order.order_output','uses'=>'OrderController@output']);

Route::post('order_output_process', ['as'=>'order.order_output_process','uses'=>'OrderController@order_output_process']);

// upload

Route::get('product_upload', ['as'=>'upload.product_upload','uses'=>'UploadController@product_upload']);

Route::post('product_upload_process', ['as'=>'upload.product_upload_process','uses'=>'UploadController@product_upload_process']);

Route::get('product_upload_format_download', ['as'=>'upload.product_upload_format_download','uses'=>'UploadController@product_upload_format_download']);

Route::get('product_spec_upload', ['as'=>'upload.product_spec_upload','uses'=>'UploadController@product_spec_upload']);

Route::post('product_spec_upload_process', ['as'=>'upload.product_spec_upload_process','uses'=>'UploadController@product_spec_upload_process']);

Route::get('product_spec_upload_format_download', ['as'=>'upload.product_spec_upload_format_download','uses'=>'UploadController@product_spec_upload_format_download']);

Route::get('purchase_upload', ['as'=>'upload.purchase_upload','uses'=>'UploadController@purchase_upload']);

Route::post('purchase_upload_process', ['as'=>'upload.purchase_upload_process','uses'=>'UploadController@purchase_upload_process']);

Route::get('purchase_upload_format_download', ['as'=>'upload.purchase_upload_format_download','uses'=>'UploadController@purchase_upload_format_download']);

Route::get('order_upload', ['as'=>'upload.order_upload','uses'=>'UploadController@order_upload']);

Route::post('order_upload_process', ['as'=>'upload.order_upload_process','uses'=>'UploadController@order_upload_process']);

Route::get('order_upload_format_download', ['as'=>'upload.order_upload_format_download','uses'=>'UploadController@order_upload_format_download']);

// report

Route::post('month_order_view', ['as'=>'report.month_order_view','uses'=>'ReportController@month_order_view']);

Route::post('year_stock_view', ['as'=>'report.year_stock_view','uses'=>'ReportController@year_stock_view']);

Route::post('stock_analytics', ['as'=>'report.stock_analytics','uses'=>'ReportController@stock_analytics']);

Route::post('year_product_top5', ['as'=>'report.year_product_top5','uses'=>'ReportController@year_product_top5']);

Route::post('product_top5_stack', ['as'=>'report.product_top5_stack','uses'=>'ReportController@product_top5_stack']);

// product_category

Route::resource('product_category', 'ProductCategoryController');

Route::post('get_child_list', ['as'=>'product_category.get_child_list','uses'=>'ProductCategoryController@get_child_list']);

// EDM

Route::resource('edm', 'EDMController');

Route::get('edm_verify_list', ['as'=>'edm.edm_verify_list','uses'=>'EDMController@edm_verify_list']);

Route::get('edm_clone', ['as'=>'edm.clone','uses'=>'EDMController@clone']);

Route::post('edm_verify', ['as'=>'edm.verify','uses'=>'EDMController@verify']);

Route::get('edm_cancel_list', ['as'=>'edm.edm_cancel_list','uses'=>'EDMController@edm_cancel_list']);

Route::post('edm_cancel', ['as'=>'edm.cancel','uses'=>'EDMController@cancel']);

Route::get('edm_example', ['as'=>'edm.edm_example','uses'=>'EDMController@edm_example']);

// mall promo price

Route::resource('promo', 'PromoPriceController');

// Ecoupon

Route::resource('ecoupon', 'EcouponController');

Route::post('getEcouponDiscount', ['as'=>'ecoupon.getEcouponDiscount','uses'=>'EcouponController@getEcouponDiscount']);
