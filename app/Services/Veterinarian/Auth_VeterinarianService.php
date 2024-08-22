<?php
namespace App\Services\Veterinarian;

use Throwable;
use App\Models\Veterinarian;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\FileStorageTrait;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class Auth_VeterinarianService{
use HasApiTokens,FileStorageTrait;
    public function register_veterinarian(array $input_data)
    {
        $data = [];
        $status_code = 400;
        $msg = '';
        $result = [];

        try{
            DB::beginTransaction();
            $veterinarian=Veterinarian::create([
             'name' => $input_data['name'],
             'password' => Hash::make($input_data['password']),
             'confirm_password' => Hash::make($input_data['confirm_password']),
             'email' => $input_data['email'],
             'role' =>'veterinarian',
             //store img using trait
             'certificate_image'=>$this->storeFile($input_data['certificate_image'],'Veterinarians'),
            ]);
         $veterinarian->assignRole(Role::where('name','veterinarian')->first());
         $auth_token=JWTAuth::fromUser($veterinarian);
         DB::commit();

         $data['Veterinarian'] = $veterinarian;
         $data['auth_token']=$auth_token;
         $status_code = 200;;
         $msg = 'Register Veterinarian ';
        }
        catch(Throwable $th){
            DB::rollBack();
            Log::debug($th);

            $status_code = 500;
            $data = $th;
            $msg = 'error ' . $th->getMessage();

        }

        $result = [
            'data' => $data,
            'status_code' => $status_code,
            'msg' => $msg,
        ];

        return $result;

        }


        //login
        public function login_veterinarian(array $input_data)
        {
            $data = [];
            $status_code = 400;
            $msg = '';
            $result = [];

             $credentials=[
                'email'=>$input_data['email'],
                'password' =>$input_data['password']
             ];
            if(!$auth_token = Auth::guard('veterinarian')->attempt($credentials)){
                $status_code = 404;
            $msg = 'Please Check your email and Password';
             }
             else{
           $veterinarian=Auth::guard('veterinarian')->user();

           $data = [
            'veterinarian' => $veterinarian,
            'auth_token' => $auth_token,
        ];
        $status_code = 200;
        $msg = 'logged In';
             }

            $result = [
                'data' => $data,
                'status_code' => $status_code,
                'msg' => $msg,
            ];

            return $result;

            }

            //logout
            public function logout_veterinarian()
            {
                $data = [];
                $status_code = 400;
                $msg = '';
                $result = [];

                $user = Auth::guard('veterinarian')->user();
               //$user->tokens()->delete(); // Or mark tokens as invalid

                // Log out the veterinarian
                Auth::guard('veterinarian')->logout();
                $msg = 'تم تسجيل الخروج بنجاح';
                $status_code = 200;
                $result = [
                    'data' => $data,
                    'status_code' => $status_code,
                    'msg' => $msg,
                ];

                return $result;

            }

}






?>
