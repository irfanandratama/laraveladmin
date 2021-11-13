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
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; 

// Route::get('/', function () {
// 	    return view('welcome');
// 	});


Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    
    //Route::get('/home', 'HomeController@index')->name('home');

	Route::get('/', function () {
	    return view('dashboard');
	});
  
	 Route::get('/basicform', function () {
	    return view('form.basicform');
    	})->name('basicform');

        Route::get('/advanceform', function () {
	     return view('form.advanceform');
    	})->name('advanceform');



	Route::get('chart', function () {
	    return view('chart');
	});

	Route::get('icon', function () {
	    return view('icon');
	});
	Route::get('table', function () {
	    return view('table');
	});
	Route::get('widget', function () {
	    return view('widget');
	});
	

	Route::get('logout', function (){
        Auth::logout();
        return redirect('/');
    });


   Route::group(['prefix'=>'components'], function(){
       Route::get('/button', function () {
	    return view('components.button');
    	})->name('button');
       
        Route::get('/tab', function () {
	     return view('components.tab');
    	})->name('tab');

       Route::get('/card', function () {
	     return view('components.card');
    	})->name('card');

    	Route::get('/progressbar', function () {
	     return view('components.progressbar');
    	})->name('progressbar');
    	
    	Route::get('/grid', function () {
	     return view('components.grid');
    	})->name('grid');
    	

	});
   
    Route::resource('users', 'UserController');

    Route::resource('roles', 'RoleController');

    Route::resource('permissions', 'PermissionController');

    Route::resource('posts', 'PostController');

	Route::resource('satker', 'SatuanKerjaController');
});

Route::resource('profile', 'ProfileController');

Route::group(['prefix'=>'skp'], function(){
	Route::resource('/tahunan', 'SkpTahunanController');
	Route::get('/tahunan/export/{id}', 'SkpTahunanController@export')->name('tahunan.export');
	Route::get('/tahunan/validate/{id}', 'SkpTahunanController@validate_data')->name('tahunan.validate_data');
	Route::put('/tahunan/validation/{id}', 'SkpTahunanController@validation')->name('tahunan.validation');

	
	Route::group(['prefix'=>'tahunan'], function ()
	{

		Route::get('/target/{id}/create', 'SkpTahunanTargetController@create');
		Route::resource('/target', 'SkpTahunanTargetController');
		Route::get('/target/export/{id}', 'SkpTahunanTargetController@export')->name('target.export');
		Route::get('/target/validate/{id}', 'SkpTahunanTargetController@validate_data')->name('target.validate_data');
		Route::put('/target/validation/{id}', 'SkpTahunanTargetController@validation')->name('target.validation');

		Route::get('/realisasi/{id}/create', 'SkpTahunanRealisasiController@create');
		Route::resource('/realisasi', 'SkpTahunanRealisasiController');
		Route::get('/realisasi/export/{id}', 'SkpTahunanRealisasiController@export')->name('realisasi.export');
		Route::get('/realisasi/validate/{id}', 'SkpTahunanRealisasiController@validate_data')->name('realisasi.validate_data');
		Route::put('/realisasi/validation/{id}', 'SkpTahunanRealisasiController@validation')->name('realisasi.validation');

		Route::get('/tugas/{id}/create', 'TugasTambahanController@create');
		Route::resource('/tugas', 'TugasTambahanController');
		Route::get('/tugas/validate/{id}', 'TugasTambahanController@validate_data')->name('tugas.validate_data');
		Route::put('/tugas/validation/{id}', 'TugasTambahanController@validation')->name('tugas.validation');

		Route::get('/kreativitas/{id}/create', 'KreativitasController@create');
		Route::resource('/kreativitas', 'KreativitasController');
		Route::get('/tugas/validate/{id}', 'KreativitasController@validate_data')->name('kreativitas.validate_data');
		Route::put('/tugas/validation/{id}', 'KreativitasController@validation')->name('kreativitas.validation');
	});

	Route::resource('/penilaian', 'PenilaianPerilakuController');
	Route::get('/penilaian/export/{id}', 'PenilaianPerilakuController@export')->name('penilaian.export');
	Route::get('/penilaian/validate/{id}', 'PenilaianPerilakuController@validate_data')->name('penilaian.validate_data');
	Route::put('/penilaian/validation/{id}', 'PenilaianPerilakuController@validation')->name('penilaian.validation');
});


 Route::get('/create_role_permission', function(){
        $role = Role::create(['name'=> 'Administrator']);
        $permission = Permission::create(['name'=> 'Administrator']);
        auth()->user()->assignRole('Administrator');
        auth()->user()->givePermissionTo('Administrator');
    });