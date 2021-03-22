<?php

namespace mixtra\controllers;

use MITBooster;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends MITController
{
    public function init()
    {
    }
    public function getLogin()
    {
        $this->mitLoader();

        if (MITBooster::myId()) {
            return redirect(MITBooster::adminPath());
        }


        return view('mitbooster::login');
    }

    public function postLogin()
    {
        try {
            $validator = Validator::make(Request::all(), [
                'username' => 'required|exists:' . config('mixtra.USER_TABLE'),
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                $message = $validator->errors()->all();

                return redirect()->back()->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
            }

            $username = Request::input("username");
            $password = Request::input("password");
            $users = DB::table(config('mixtra.USER_TABLE'))->where("username", $username)->first();

            if (\Hash::check($password, $users->password)) {
                $priv = DB::table("mit_privileges")->where("id", $users->mit_privileges_id)->first();

                $roles = DB::table('mit_privileges_roles')->where('mit_privileges_id', $users->mit_privileges_id)->join('mit_modules', 'mit_modules.id', '=', 'mit_modules_id')->select('mit_modules.name', 'mit_modules.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')->get();

                $photo = ($users->photo) ? asset($users->photo) : asset('assets/images/user.png');
                Session::put('admin_id', $users->id);
                Session::put('admin_is_superadmin', $priv->is_superadmin);
                Session::put('admin_name', $users->name);
                Session::put('admin_photo', $photo);
                Session::put('admin_privileges_roles', $roles);
                Session::put("admin_privileges", $users->mit_privileges_id);
                Session::put('admin_privileges_name', $priv->name);
                Session::put('admin_lock', 0);
                Session::put('theme_color', $priv->theme_color);

                MITBooster::insertLog(trans("mixtra.log_login", ['email' => $users->email, 'ip' => Request::server('REMOTE_ADDR')]));

                //$cb_hook_session = new \App\Http\Controllers\CBHook;
                //$cb_hook_session->afterLogin();

                return redirect(MITBooster::adminPath());
            } else {
                return redirect()->route('getLogin')->with('message', 'Sorry your password is wrong !');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'message_type' => 'danger']);
        }
    }

    function getIndex()
    {
        $this->mitLoader();

        $data = [];
        $data['page_title'] = '<strong>Dashboard</strong>';

        return view('mitbooster::home', $data);
    }

    public function getLogout()
    {

        $me = MITBooster::me();
        //CRUDBooster::insertLog(trans("crudbooster.log_logout", ['email' => $me->email]));

        Session::flush();

        return redirect()->route('getLogin')->with('message', 'Thank You, See You Later !');
    }

    public function getLockscreen()
    {

        if (!MITBooster::myId()) {
            Session::flush();

            return redirect()->route('getLogin')->with('message', 'Your session was expired, please login again !');
        }

        Session::put('admin_lock', 1);
        return view('mitbooster::lockscreen');
    }

    public function postUnlockScreen()
    {
        $id = MITBooster::myId();
        $password = Request::input('password');
        $users = DB::table(config('mixtra.USER_TABLE'))->where('id', $id)->first();

        if (\Hash::check($password, $users->password)) {
            Session::put('admin_lock', 0);
            return redirect(MITBooster::adminPath());
        } else {
            echo "<script>alert('Sorry your password is wrong !');history.go(-1);</script>";
        }
    }

    public function getForgot()
    {
        $this->mitLoader();

        if (MITBooster::myId()) {
            return redirect(MITBooster::adminPath());
        }

        return view('mitbooster::forgot');
    }

    public function postForgot(Request $request)
    {
        try {
            $validator = Validator::make(Request::all(), [
                'username' => 'required|exists:' . config('mixtra.USER_TABLE')
            ]);

            if ($validator->fails()) {
                $message = $validator->errors()->all();

                return redirect()->back()->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
            }

            $username = Request::input("username");
            $url = url("/admin/reset?email=" . urlencode($username));
            $users = DB::table(config('mixtra.USER_TABLE'))->where("username", $username)->first();

            if ($users) {
                try {
                    Mail::send('mitbooster::email', ['nama' => Request::input("username"), 'link' => $url], function ($message) use ($request) {
                        $message->subject('Reset Password Link');
                        $message->from('donotreply@solusi-pack.com', 'donotreply');
                        $message->to(Request::input("username"));
                    });
                    return redirect()->route('getLogin')->with('message', 'Reset Password Link Berhasil di Kirim');
                } catch (Exception $e) {
                    return redirect()->back()->with(['status' => false, 'message' => $e->getMessage()]);
                }
            } else {
                return redirect()->route('getForgot')->with('message', 'Email not Registered');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'message_type' => 'danger']);
        }
    }

    public function getReset()
    {
        $this->mitLoader();

        if (MITBooster::myId()) {
            return redirect(MITBooster::adminPath());
        }

        return view('mitbooster::reset');
    }

    public function postReset(Request $request)
    {
        // return redirect()->back()->with('message', Request::input("email"));
        try {
            $validator = Validator::make(Request::all(), [
                'password' => 'required'
            ]);

            //check if input is valid before moving on
            if ($validator->fails()) {
                return redirect()->back()->with('message', 'please fill the form');
            }

            $username = Request::input("email");
            $password = Request::input("password");
            $rePassword = Request::input("re-password");

            if ($password != $rePassword) return redirect('/admin/reset?email=' . urlencode($username))->with('message', 'Password not match');

            $users = DB::table(config('mixtra.USER_TABLE'))->where("username", $username)->first();

            if ($users) {
                $hashPassword = \Hash::make($password);
                DB::table(config('mixtra.USER_TABLE'))->where("username", $username)->update(['password' => $hashPassword]);

                return redirect()->route('getLogin')->with('message', 'Your password updated, please login');
            } else {
                return redirect()->route('getReset')->with('message', 'Email not Registered');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'message_type' => 'danger']);
        }
    }
}
