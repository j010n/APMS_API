<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
      $credentials = $request->validate([
        'email'     => ['required', 'email'],
        'password'  => ['required'],
      ]);

      $user = User::withTrashed()
        ->where('email', $credentials['email'])
        ->first();

      if (!$user || !Hash::check($credentials['password'], $user->password)) {
        Log::warning('Tentativa de login inválida.', [
            'email' => $request->input('email'),
            'ip'    => $request->ip(),
        ]);

        throw ValidationException::withMessages([
            'email' => ['Credenciais inválidas.'],
        ]);
      }

      $restored = false;

      if ($user->trashed()) {
        $user->restore();
        $restored = true;
      }

      $user->tokens()->where('name', 'auth_token')->delete();

      $token = $user->createToken('auth_token');

      $token->accessToken->expires_at = now()->addHours(6); // addMinutes(1); // addHours(6); // addDays(7); // addWeek(); Z
      $token->accessToken->save();

      $plainTextToken = $token->plainTextToken;

      //  $request->session()->regenerate();

      return response()->json([
        'message'  => 'Login realizado com sucesso!',
        'restored' => $restored,
        'user' => new UserResource($user),
        'token'    => $plainTextToken,
      ]);
    }

    public function register(Request $request) 
    {
      $fields = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|confirmed|min:6',

        'phone' => 'nullable|string|min:10|max:11',
        'tel' => 'nullable|string|min:10|max:11',
        'cpf' => 'nullable|string|min:11|max:11',
        'rg' => 'nullable|string|max:20',
        'sex' => 'nullable|string|in:M,F,O',
        'birthdate' => 'nullable|date',

        'country' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'city' => 'nullable|string|max:100',

        'affiliated' => 'nullable|boolean',
      ]);

      $user = User::create([
        'name' => $fields['name'],
        'email' => $fields['email'],
        'password' => Hash::make($fields['password']),
        'phone' => $fields['phone'] ?? null,
        'tel' => $fields['tel'] ?? null,
        'cpf' => $fields['cpf'] ?? null,
        'rg' => $fields['rg'] ?? null,
        'sex' => $fields['sex'] ?? null,
        'birthdate' => $fields['birthdate'] ?? null,
        'country' => $fields['country'] ?? null,
        'state' => $fields['state'] ?? null,
        'city' => $fields['city'] ?? null,
        'affiliated' => $fields['affiliated'] ?? false,
      ]);

      $user->tokens()->where('name', 'auth_token')->delete();
      $token = $user->createToken('auth_token');

      $token->accessToken->expires_at = now()->addHours(6);
      $token->accessToken->save();

      return response()->json([
        'message' => 'Cadastro realizado com sucesso!',
        'user' => $user,
        'token' => $token->plainTextToken,
      ], 201);
    }

    public function logout(Request $request) 
    {
      $request->user()->currentAccessToken()->delete(); // logou disp actual

      // $request->user()->tokens()->delete(); // Logou all disp

      // $request->session()->invalidate();
      // $request->session()->regenerateToken();

      return response()->json(['message' => 'Logout efetuado com sucesso!']);
    }
}
