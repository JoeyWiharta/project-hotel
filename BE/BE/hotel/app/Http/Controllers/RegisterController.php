<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\MyUtil;

class RegisterController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return MyUtil::sendError('Validation Error.', $validator->errors());
        }

        if (User::where('email', '=', $request->email)->get()->first() === null) {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['role'] = 'user';
            $user = User::create($input);
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            return MyUtil::sendResponse($success, 'User register successfully.');
        } else {
            return MyUtil::sendError('User already exists.', "user duplicate");
        }
    }

    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            
            // Hapus riwayat token sebelumnya
            DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();

            $data = [
                'token' => $user->createToken('MyApp')->plainTextToken,
                'role' => $user->role, // Pastikan role disertakan dalam respons
            ];
            
            return MyUtil::sendResponse($data, 'User login successfully.');
        } else {
            return MyUtil::sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return MyUtil::sendResponse("LOGOUT", 'Logout successfully.');
    }
}
