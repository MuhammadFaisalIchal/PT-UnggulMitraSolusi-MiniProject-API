<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'jenis_kelamin' => 'required|in:PRIA,WANITA',
                'domisili' => 'required|string|max:255',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong',
                    'error' => response()->json($validator->errors(), 400)
                ], 'Authentication Failed', 500);
            } else {
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'domisili' => $request->domisili,
                    'password' => Hash::make($request->password)
                ]);

                $user = User::where('email', $request->email)->first();
                $tokenResult = $user->createToken('authToken')->plainTextToken;

                return ResponseFormatter::success([
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $user
                ], 'Berhasil registrasi!');
            }
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Registration Failed', 500);
        }
    }

    public function read(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong',
                    'error' => response()->json($validator->errors(), 400) // Isi error yang kita dapat dari Exception
                ], 'Validation Failed', 500);
            }

            $credential = request(['email', 'password']);

            if (!Auth::attempt($credential)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult, // Ambil hasil generate
                'token_type' => 'Bearer', // Pakai tipe ini
                'user' => $user // Kembalikan data User
            ], 'Authenticated');
        } catch (\Exception $error) {
            // Jika error
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'domisili' => 'string|max:255',
                'password' => 'string|integer|numeric|max:255'
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong',
                    'error' => response()->json($validator->errors(), 400)
                ], 'Validation Failed', 500);
            }

            $user = Auth::user();

            $user->update($data);

            return ResponseFormatter::success($user, 'Profile diperbarui');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Gagal merubah data profil', 500);
        }
    }

    public function delete(Request $request)
    {
        $user = User::where('email', $request->email)->get();

        if (!$user->isEmpty()) {
            User::where('email', $request->email)->delete();

            return ResponseFormatter::success(null, 'User berhasil dihapus');
        } else {
            return ResponseFormatter::error(null, 'User gagal dihapus', 404);
        }
    }
}
