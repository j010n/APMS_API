<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function user(Request $request)
    {
      return response()->json($request->user());
    }

    public function update(Request $request) 
    {
      $user = $request->user();

      $fields = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'password' => 'sometimes|string|confirmed|min:6',

        'phone' => 'sometimes|string|nullable|min:11|max:11',
        'tel' => 'sometimes|string|nullable|min:10|max:11',
        'cpf' => 'sometimes|string|nullable|min:11|max:11',
        'rg' => 'sometimes|string|nullable|max:20',
        'sex' => 'sometimes|string|in:M,F,O|nullable',
        'birthdate' => 'sometimes|date|nullable',
        
        'country' => 'sometimes|string|nullable|max:100',
        'state' => 'sometimes|string|nullable|max:100',
        'city' => 'sometimes|string|nullable|max:100',
        
        'affiliated' => 'sometimes|boolean',
      ]);

      $updatable = [
        'name', 'email', 'password', 'phone', 'tel', 'cpf', 'rg', 
        'sex', 'birthdate', 'country', 'state', 'city', 'affiliated'
      ] ;

      foreach ($updatable as $field) {
        if (isset($fields[$field])) {
            if ($field === 'password') {
              $user->password = Hash::make($fields['password']);
            } else {
              $user->$field = $fields[$field];
            }
        }
      }

      $user->save();

      return response()->json([
        'message' => 'Dados atualizados com sucesso!',
        'user' => $user,
      ]);
    }

    public function deactivate(Request $request) 
    {
      $user = $request->user();

      // Soft delete
      $user->delete();

      // Revoga todos os tokens (logout forçado)
      $user->tokens()->delete();

      return response()->json([
          'message' => 'Sua conta foi desativada com sucesso.',
      ]);
    }

    public function membershipStatus(Request $request) 
    {
      $user = $request->user();

      return response()->json([
        'affiliated' => $user->affiliated,
      ]);
    }
}
