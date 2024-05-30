<?php

namespace App\Http\Services\Auth;

use App\Helper\GlobalResponse;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginService
{
    /**
     * Authenticates a user based on the provided email or phone and password.
     *
     * @param Request $request The HTTP request object containing the email or phone and password.
     * @return mixed Returns a JSON response with a token if the authentication is successful, or an error message if it fails.
     */
    public function login(Request $request)
    {
        $loginField = request()->input('email');

        $credentials = null;

        $loginType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where('email', '=', $loginField)->first();

        if ($user == null)
        {
            return GlobalResponse::error("", "Email does not exists on record", Response::HTTP_BAD_REQUEST);
        }else{

            if (Hash::check($request->password, $user->password))
            {
                request()->merge([$loginType => $loginField]);

                $credentials = request([$loginType, 'password']);

                if (!$token = JWTAuth::attempt($credentials))
                {

                    return GlobalResponse::error("", "Unauthorized", Response::HTTP_UNAUTHORIZED);

                }else{

                    return $this->responseWithToken($token);
                }
            }else{
                return GlobalResponse::error("", "Password did not match", Response::HTTP_BAD_REQUEST);
            }
        }
    }

    /**
     * Logout the user by invalidating the JWT token.
     *
     * @param Request $request The HTTP request object.
     * @throws JWTException If there is an error invalidating the token.
     * @return mixed The exception object if an error occurs, otherwise void.
     */
    public function logout(Request $request)
    {
        $token = $request->header("Authorization");

        try {

            JWTAuth::invalidate(JWTAuth::getToken());

        } catch (JWTException $e) {

            return $e;
        }
    }

    /**
     * Generates a response with a token for the authenticated user.
     *
     * @param string $token The access token for the user.
     * @return array The response data containing user details, role, permissions, access token, token type, and expiration time.
     */
    protected function responseWithToken($token)
    {
        $user = User::where('id', Auth::id())->first();

        $role = $user->getRoleNames();

        $permissions = $user->getAllPermissions();

        $menus = Menu::with('menuDropdown')->get();
        $data = [];

        foreach ($menus as $m) {
            $menuDropDown = [];

            // Collect dropdown items if the menu is a parent (permission_id is null)
            if ($m->permission_id == null) {
                foreach ($m->menuDropdown as $md) {
                    foreach ($permissions as $p) {
                        if ($p->id == $md->permission_id) {
                            $menuDropDown[] = [
                                "id" => $md->id,
                                "menu_id" => $md->menu_id,
                                "permission_id" => $p->name,
                                "title" => $md->title,
                                "icon" => $md->icon,
                                "route" => $md->route,
                                "created_at" => $md->created_at,
                                "updated_at" => $md->updated_at,
                            ];
                        }
                    }
                }
            }

            // Check if the menu has permission or is a parent menu with dropdowns
            $hasPermission = false;
            foreach ($permissions as $p) {
                if ($p->id == $m->permission_id || !empty($menuDropDown)) {
                    $hasPermission = true;
                    break;
                }
            }

            // Add the menu to the final data if it has permission or is a parent menu with dropdowns
            if ($hasPermission) {
                $data[] = [
                    "id" => $m->id,
                    "permission_id" => $p->name,
                    "title" => $m->title,
                    "icon" => $m->icon,
                    "route" => $m->route,
                    "header_menu" => $m->header_menu,
                    "sidebar_menu" => $m->sidebar_menu,
                    "dropdown" => $m->dropdown,
                    "created_at" => $m->created_at,
                    "updated_at" => $m->updated_at,
                    "menu_drop_down" => $menuDropDown
                ];
            }
        }

        $data = [
            "user" => $user,
            'role' => $role,
            'permissions' => $permissions,
            'menus' => $data,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ];

        return $data;
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
