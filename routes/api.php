<?php
	

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function(){
	Route::post('get-otp', 'API\UserController@postEmail')->name('postEmail');
	
	Route::post('login', 'API\UserController@postLogin')->name('postLogin');
	Route::post('forget-password', 'API\UserController@postForgetPassword')->name('postForgetPassword');
	Route::post('register', 'API\UserController@postRegister')->name('postRegister');
	Route::get('setting', 'API\SettingController@getSetting')->name('getSetting');
	Route::get('slide', 'API\SettingController@getSlide')->name('getSlide');
	Route::get('list-coin', 'API\SettingController@getListCoin')->name('getListCoin');
	
	Route::group(['prefix' => 'token'], function(){
		Route::post('price', 'API\TokenController@postPrice')->name('postPrice');
		
	});
	
	Route::group(['prefix' => 'auth', 'middleware' => ['auth:api']], function(){
		
		Route::get('logout', 'API\UserController@getLogout')->name('getLogout');
		Route::post('change-password', 'API\UserController@postChangePassword')->name('postChangePassword');
		Route::get('info', 'API\UserController@getInfo')->name('getInfo');
		Route::get('balance', 'API\UserController@getBalance')->name('getBalance');
		Route::post('history', 'API\UserController@postHistory')->name('postHistory');
		Route::get('coin', 'API\UserController@getCoin')->name('getCoin');
		Route::get('deposit/{id}', 'API\UserController@getDeposit')->name('getDeposit');
		Route::post('member-list', 'API\UserController@postMemberList')->name('postMemberList');
		Route::post('add-member', 'API\UserController@postAddMember')->name('postAddMember');
		Route::get('get-auth', 'API\UserController@getAuth')->name('getAuth');
		Route::post('auth-desible', 'API\UserController@postAuth')->name('postAuth');
		Route::post('confirm-auth', 'API\UserController@postConfirmAuth')->name('postConfirmAuth');
		Route::post('withdraw', 'API\MoneyController@PostWithdraw')->name('PostWithdraw');
		Route::post('confirm-withdraw', 'API\MoneyController@postConfirmWithdraw')->name('postConfirmWithdraw');
		Route::post('transfer', 'API\MoneyController@PostTransfer')->name('PostTransfer');
		Route::post('confirm-transfer', 'API\MoneyController@postConfirmTransfer')->name('postConfirmTransfer');
		Route::post('swap', 'API\MoneyController@postSwap')->name('postSwap');
		Route::post('confirm-swap', 'API\MoneyController@postConfirmSwap')->name('postConfirmSwap');
		Route::get('package', 'API\InvestmentController@getPackage')->name('getPackage');
		Route::post('invest', 'API\InvestmentController@postInvestment')->name('postInvestment');
		Route::post('check-refund', 'API\InvestmentController@postRefundInvest')->name('postRefundInvest');
		Route::post('cancel-package', 'API\InvestmentController@cancelPackage')->name('cancelPackage');
		
		Route::get('get-invest-group', 'API\InvestmentController@getInvestGroup')->name('getInvestGroup');
		
	    Route::post('borrow-list', 'API\BorrowController@postBorrowList')->name('postBorrowList');
	    Route::post('borrow', 'API\BorrowController@postBorrow')->name('postBorrow');
	    Route::post('lend', 'API\BorrowController@postLend')->name('postLend'); // lấy lệnh vay
	    Route::post('cancel-lend', 'API\BorrowController@postCancelLend')->name('postCancelLend'); // chuộc lệnh vay
	    Route::get('borrow-history', 'API\BorrowController@getHistoryBorrow')->name('getHistoryBorrow');
	    Route::post('borrow-info', 'API\BorrowController@postBorrowInfo')->name('postBorrowInfo');
		    
		Route::post('trade-buy', 'API\TradeController@postTradeBuy')->name('postTradeBuy');
		Route::post('trade-sell', 'API\TradeController@postTradeSell')->name('postTradeSell');

		Route::get('get-string-code', 'API\UserController@getStrinhCode')->name('getStrinhCode');
		Route::post('create-wallet', 'API\UserController@getCreateWallet')->name('getCreateWallet');
		
		Route::get('get-subject', 'API\OtherController@getSubject')->name('getSubject');
		Route::get('ticket', 'API\OtherController@getTicket')->name('getTicket');
		Route::post('send-ticket', 'API\OtherController@postTicket')->name('postTicket');
		Route::get('ticket-detail/{id}', 'API\OtherController@getTicketDetail')->name('getTicketDetail');
	});
	
});

