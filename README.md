# Inventory-Management-System


```CMD
laravel new inventory_management

```
To migrate your existing database:
```
php artisan migrate
```

to see in the local server:

```
php artisan serve      
```

# Login and registration system using Breeze:
link : https://laravel.com/docs/11.x/starter-kits

If you have already created a new Laravel application without a starter kit, you may manually install Laravel Breeze using Composer:

```CMD
composer require laravel/breeze --dev
```
The breeze: install command will prompt you for your preferred frontend stack and testing framework:

```CMD
php artisan breeze:install

php artisan migrate
npm install
npm run dev
```
# Add Column in the existing table:

```
php artisan make:migration add_role_to_users --table users
```
and add this to that new database:

```
$table->string('role')->after('name')->default('user');
```
 
and then,

```
php artisan migrate   
```

# Separate Admin and user dashboard:
resource ->admin(folder)->admin_dashboard.blade.php

- Create Middleware
Generate a middleware to check if the user is an admin:

```
php artisan make:middleware AdminMiddleware
```
- In app/Http/Middleware/AdminMiddleware.php:

```
public function handle(Request $request, Closure $next) {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }
    return redirect('/dashboard'); // Send normal users away
}
```
- Separate Routes:
Organize your routes/web.php so the URLs and logic are totally distinct:
```
// User Dashboard (Normal Customers/Staff)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
});

// Admin Dashboard (Management Only)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('products', ProductController::class); // Only admin can CRUD products
});

```
- bootstrap/app.php:
```
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
```
- UserDashboarController:
  
```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
class UserDashboardController extends Controller
{
   public function index()
    {
        // Check if user is logged in first to avoid errors
        if (Auth::check() && Auth::user()->role === 'admin') {
            return view('admin.admin_dashboard'); 
        }

        return view('user.dashboard'); 
    }
}

```
# 403 abort message:

- UserDashboardController:
```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
class UserDashboardController extends Controller
{
   public function index()
    {
        // Check if user is logged in first to avoid errors
        if (Auth::check() && Auth::user()->role === 'admin') {
            return view('admin.admin_dashboard'); 
        }
        else if (Auth::check() && Auth::user()->role === 'user') {
            return view('user.dashboard'); 
        }
        else{
            abort(403);
        // return view('user.dashboard'); 
        }
    }
}

```
- AdminMiddleware:
```
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    
   public function handle($request, Closure $next)
{
    
    if ($request->user() && $request->user()->role === 'admin') {
        return $next($request);
    }

    abort(403, 'You do not have administrative privileges to access this page.');
}
}

```
and add a file under view->errors->403.blade.php




