<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->group(["prefix" => "api/v1"], function() use($router) {

    $router->get('/', function () use ($router) {
        return $router->app->version();
    });


    $router->group(["prefix" => "auth"], function () use($router) {
        $router->post("/login", "AuthController@login");
        $router->post("/register", "AuthController@register");

    });

    $router->group(["prefix" => "wallet", "middleware" => "auth"], function () use($router) {
        $router->post("/create", "WalletController@create");
        $router->get("/balance_enquiry", "WalletController@balance_enquiry");
        $router->post("/initaite/wallet_funding", "WalletController@initiate_wallet_funding");
        $router->post("/fund","WalletController@fund_wallet");
        $router->get("/transactions", "WalletController@transactions");
    });



    $router->group(["prefix" => "webhook"], function () use($router) {
        $router->get("/payments/paystack", "WebhookController@payment_callback_paystack");
    });

});



