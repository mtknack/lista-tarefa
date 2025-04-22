<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarefaController extends Controller
{
    public function index()
    {
        $tarefas = Tarefa::orderBy('ordem')->get();
        $total = $tarefas->sum('custo');

        return view('tarefas.index', compact('tarefas', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|unique:tarefas,nome',
            'custo' => 'required|numeric|min:0',
            'data_limite' => 'required|date',
        ]);

        $ultimaOrdem = Tarefa::max('ordem') ?? 0;

        Tarefa::create([
            ...$validated,
            'ordem' => $ultimaOrdem + 1,
        ]);

        return redirect('/');
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        $validated = $request->validate([
            'nome' => 'required|unique:tarefas,nome,' . $tarefa->id,
            'custo' => 'required|numeric|min:0',
            'data_limite' => 'required|date',
        ]);

        $tarefa->update($validated);

        return redirect('/');
    }

    public function destroy(Tarefa $tarefa)
    {
        $tarefa->delete();

        $tarefas = Tarefa::orderBy('ordem')->get();
        foreach ($tarefas as $index => $t) {
            $t->ordem = $index + 1;
            $t->save();
        }

        return redirect('/');
    }

    public function subir(Tarefa $tarefa)
    {
        $anterior = Tarefa::where('ordem', '<', $tarefa->ordem)->orderByDesc('ordem')->first();
        if ($anterior) {
            $this->trocarOrdem($tarefa, $anterior);
        }
        return redirect('/');
    }

    public function descer(Tarefa $tarefa)
    {
        $proxima = Tarefa::where('ordem', '>', $tarefa->ordem)->orderBy('ordem')->first();
        if ($proxima) {
            $this->trocarOrdem($tarefa, $proxima);
        }
        return redirect('/');
    }

    private function trocarOrdem($a, $b)
    {
        DB::transaction(function () use ($a, $b) {
            $tempOrdem = $a->ordem;

            $a->ordem = -1;
            $a->save();

            $a->ordem = $b->ordem;
            $b->ordem = $tempOrdem;

            $b->save();
            $a->save();
        });
    }

    public function reordenar(Request $request)
    {
        $alteracoes = $request->all();

        if (!is_array($alteracoes)) {
            return response()->json(['error' => 'Formato inválido'], 400);
        }

        DB::transaction(function () use ($alteracoes) {
            $ids = array_column($alteracoes, 'id');
            Tarefa::whereIn('id', $ids)->update([
                'ordem' => DB::raw('-ordem')
            ]);

            foreach ($alteracoes as $item) {
                Tarefa::where('id', $item['id'])->update(['ordem' => $item['ordem']]);
            }
        });

        return response()->json(['success' => true]);
    }
}
