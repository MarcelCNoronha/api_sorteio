<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email',
                'cpf' => 'required|string|unique:clients,cpf',
                'date_birth' => 'required|date',
                'address' => 'required|string',
            ]);

            $data = $request->all();
            $client = Client::create($data);

            return response()->json(['message' => 'Cliente cadastrado com sucesso', 'data' => $client], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function index(Request $request)
    {
        try {
            $nome = $request->input('nome');
            $cpf = $request->input('cpf');
            $dataNascimento = $request->input('data_nascimento');
    
            $clientes = Client::query();
    
            if ($nome) {
                $clientes->where('nome', 'like', '%' . $nome . '%');
            }
    
            if ($cpf) {
                $clientes->where('cpf', 'like', '%' . $cpf . '%');
            }
    
            if ($dataNascimento) {
                $clientes->whereDate('date_birth', $dataNascimento);
            }
    
            $resultados = $clientes->get();
    
            return response()->json(['data' => $resultados], Response::HTTP_OK );
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
      
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email,' . $id,
                'cpf' => 'required|string|unique:clients,cpf,' . $id,
                'date_birth' => 'required|date',
                'address' => 'required|string',
            ]);
            $client = Client::find($id);

            if (!$client) return response()->json(['message' => 'Cliente não encontrado'], Response::HTTP_NOT_FOUND);

            $client->update($request->all());

            return response()->json(['message' => 'Cliente atualizado com sucesso', 'data' => $client], Response::HTTP_OK );
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::find($id);

            if (!$client) return response()->json(['message' => 'Cliente não encontrado'], Response::HTTP_NOT_FOUND);

            $client->delete();

            return response()->json(['message' => 'Cliente deletado com sucesso'], Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
