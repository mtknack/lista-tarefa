<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tarefas</title>
    <style>
        .caro { background-color: yellow; }
        table { border-collapse: collapse; width: 100%; }
        td, th { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h1>Lista de Tarefas</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Custo (R$)</th>
                <th>Data Limite</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="lista-tarefas">
            @foreach ($tarefas as $tarefa)
                <tr data-id="{{ $tarefa->id }}" data-ordem="{{ $tarefa->ordem }}" class="{{ $tarefa->custo >= 1000 ? 'caro' : '' }}">
                    <td>{{ $tarefa->id }}</td>
                    <td>{{ $tarefa->nome }}</td>
                    <td>{{ number_format($tarefa->custo, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($tarefa->data_limite)->format('d/m/Y') }}</td>
                    <td>
                        <form action="/tarefas/{{ $tarefa->id }}/up" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" {{ $loop->first ? 'disabled' : '' }}>⬆️</button>
                        </form>
                        <form action="/tarefas/{{ $tarefa->id }}/down" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" {{ $loop->last ? 'disabled' : '' }}>⬇️</button>
                        </form>

                        <form action="/tarefas/{{ $tarefa->id }}" method="POST" style="display:inline;">
                            @csrf @method('PUT')
                            <input name="nome" value="{{ $tarefa->nome }}" required>
                            <input name="custo" type="number" step="0.01" value="{{ $tarefa->custo }}" required>
                            <input name="data_limite" type="date" value="{{ $tarefa->data_limite }}" required>
                            <button type="submit">💾</button>
                        </form>

                        <form action="/tarefas/{{ $tarefa->id }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                            @csrf @method('DELETE')
                            <button type="submit">🗑️</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Total: <strong>R$ {{ number_format($total, 2, ',', '.') }}</strong></p>

    <h2>Nova Tarefa</h2>
    <form action="/tarefas" method="POST">
        @csrf
        <label>Nome:</label>
        <input name="nome" required>
        <label>Custo (R$):</label>
        <input name="custo" type="number" step="0.01" required>
        <label>Data Limite:</label>
        <input name="data_limite" type="date" required>
        <button type="submit">Incluir</button>
    </form>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    const el = document.getElementById('lista-tarefas');
    const sortable = Sortable.create(el, {
        animation: 150,
        onEnd: function (evt) {
            let ordem = [];
            document.querySelectorAll('#lista-tarefas tr').forEach((tr, index) => {
                ordem.push(tr.dataset.id);
            });

            // lista é o elemento HTML com as tarefas, onde cada item tem data-id
            const lista = document.getElementById('lista-tarefas');

            // salva a ordem atual dos itens (depois do drag)
            const novaOrdem = Array.from(lista.children).map((el, index) => ({
                id: parseInt(el.dataset.id),
                ordem: index + 1
            }));

            // compara com a ordem anterior e filtra apenas os modificados
            const alteracoes = novaOrdem.filter((item, index) => {
                const ordemOriginal = parseInt(lista.children[index].dataset.ordem);
                return item.ordem !== ordemOriginal;
            });

            // se nada mudou, não envia nada
            if (alteracoes.length === 0) return;

            fetch('/tarefas/reordenar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(alteracoes)
            })
            .then(res => {
                if (!res.ok) throw new Error('Erro na requisição');
                return res.json();
            })
            .then(data => {
                if (!data.success) {
                    console.error('Erro ao reordenar:', data);
                    alert('Erro ao reordenar tarefas');
                } else {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Erro no fetch:', error);
                alert('Erro de comunicação com o servidor');
            });
        }
    });
</script>
