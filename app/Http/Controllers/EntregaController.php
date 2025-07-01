<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EntregaService;
use App\Exceptions\DataSourceException;
use App\Rules\ValidCpf;
use Illuminate\Validation\ValidationException;
use Exception;

class EntregaController extends Controller
{
    public function __construct(protected EntregaService $entregaService)
    {
    }

    public function index()
    {
        return view('entregas.index');
    }

    public function buscar(Request $request)
    {
        try {
            $validated = $request->validate([
                'cpf' => ['required', 'string', new ValidCpf()]
            ], [
                'cpf.required' => 'O CPF é obrigatório.',
            ]);

            $cpf = ValidCpf::sanitize($validated['cpf']);
            $entregas = $this->entregaService->buscarEntregasPorCpf($cpf);
            
            return view('entregas.buscar', ['entregas' => $entregas]);
            
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (DataSourceException $e) {
            report($e);
            return back()->with('error', 'Não foi possível buscar as entregas no momento. Tente novamente mais tarde.');
        } catch (Exception $e) {
            report($e);
            return back()->with('error', 'Ocorreu um erro inesperado. Tente novamente mais tarde.');
        }
    }

    public function listar()
    {
        return view('entregas.listar');
    }

    public function detalhar($id)
    {
        try {
            $entrega = $this->entregaService->buscarEntregaPorId($id);
            return view('entregas.detalhar', ['entrega' => $entrega]);
        } catch (Exception $e) {
            report($e);
            return redirect()->route('entregas.index')
                ->with('error', 'Entrega não encontrada ou erro interno.');
        }
    }
}