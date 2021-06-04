<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirect($provider) {
        Session::flash('back_url',$_SERVER['HTTP_REFERER']);
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider) {
        $getInfo = Socialite::driver($provider)->stateless()->user();
        $link = $_COOKIE['link_file']??'';
        // Nếu thông tin facebook trả về không có Email thì thông báo
        if(!$getInfo->email) {

            return redirect(Session::get('back_url','/'))->with(['flash_level'=>'danger','flash_message'=> 'Tài khoản của bạn không hoạt động.']);

        } else {
            // Nếu có Email thì check tồn tại trong hệ thống
            $findUser = User::where('email', $getInfo->email)->first();
            // nếu tồn tại user trong DB thì đăng nhập còn không thì đăng ký
            if($findUser != null){
                if ($findUser->status == 1) {
                    Auth::login($findUser, true);
                    return Redirect::to($link);
                } else {
                    return redirect(Session::get('back_url','/'))->with(['flash_level'=>'danger','flash_message'=> 'Tài khoản của bạn không hoạt động.']);
                }
            } else {
                $created_at = $updated_at = date('Y-m-d H:i:s');
                $user = [
                    'username' => $getInfo->id,
                    'email' => $getInfo->email,
                    'name' => $getInfo->name??$getInfo->email,
                    'password' => bcrypt('*******'),
                    'status' => 1,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
                $user_id = User::insertGetId($user);
                Auth::login(User::find($user_id),true);
               return Redirect::to($link);
            }
        }
    }
}