<?php

use think\facade\Route;

Route::get('index', 'Index/index');
Route::post('chat', 'Chat/index');
Route::post('chat', 'Chat/chat');