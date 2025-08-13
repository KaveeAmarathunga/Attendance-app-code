<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Usertype;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // try{
        //     $request->validate([
        //     'email'=>"required|exists:users,email",
        //     'password'=>'required'
        // ]);

        // if(!(Auth::attempt(['email'=>$request->email,'password'=>$request->password]))){
        //     return false;
        // }

        // }catch(ValidationException $e){
        //     return response()->json([
        //     'errors' => $e->errors(),
        // ], 422);
        // }catch (Exception $e){
        //    return response()->json([
        //     'error' => $e->getMessage(),
        // ], 500);
        // }
    }

    public function register(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'epf_number' => 'required|string|max:20',
                'usertype_name' => 'required|string',
                'designation' => 'required|string|max:255',
                'company_name'=>'required|string|max:255',
                'date_of_append'=>'required',
                'date_of_birth'=>'required'
            ]);


        $user_type_id = Usertype::where('usertype_name',$request->usertype_name)->firstOrFail()->usertype_id;
        $company_id = Company::where('name',$request->company_name)->firstOrFail()->company_id;

        $user = [
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>Hash::make($request->password),
            "epf_number"=>$request->epf_number,
            "usertype_id"=>$user_type_id,
            "designation"=>$request->designation,
            "company_id"=>$company_id,
            "date_of_append"=>$request->date_of_append,
            "date_of_birth"=>$request->date_of_birth
        ];

        $result = User::create($user);

        return [
            'user'=>$result
        ];

        }catch(ValidationException $e){
            return response()->json([
            'errors' => $e->errors(),
        ], 422);
        }catch (Exception $e){
           return response()->json([
            'error' => $e->getMessage(),
        ], 500);
        }



    }

}
