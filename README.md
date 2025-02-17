Create laravel project
    composer create-project laravel/laravel "project name"

integrating JWT token service from tymon/Jwt
    composer require tymon/jwt-auth

publish the jwtservice provider
    php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

create the jwt secret
    php artisan jwt:secret

change the configaration in auth.php

     'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'jwt',  // Use JWT for API authentication
            'provider' => 'users',
            'hash' => false,
        ],
    ],

creating AuthController for authentication process

    <?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Hash;
    use App\Models\User;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    class AuthController extends Controller
    {

        public function register(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            $token = JWTAuth::fromUser($user);
            return response()->json(compact('user', 'token'), 201);
        }

        public function login(Request $request)
        {
            $credentials = $request->only('email', 'password');

            try {
                // Attempt to create the token for the user
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'Invalid credentials'], 401);
                }
                $user = JWTAuth::user();
                $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);
                return response()->json(compact('token'));
            } catch (JWTException $e) {
                return response()->json(['error' => 'Could not create token'], 500);
            }
        }

        public function logout()
        {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out']);
        }
    }


Create routes for authentication

    <?php
        use App\Http\Controllers\AuthController;
        use App\Http\Controllers\HomeController;
        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Route;

        Route::prefix('auth')->group(function () {

            // Register route
            Route::post("register", [AuthController::class, "register"]);
            // Login route
            Route::post("login", [AuthController::class, "login"]);
            // Logout route
            Route::post("logout", [AuthController::class, "logout"]);

        });

        // User Routes - These require JWT authentication
        Route::middleware('auth:api')->group(function () {
            // Route to get authenticated user data
            Route::get('/user', function (Request $request) {
                return response()->json($request->user());
            });
            
            // Get all users route (only accessible by authenticated users)
            Route::get("users", [HomeController::class, "index"]);
        });
