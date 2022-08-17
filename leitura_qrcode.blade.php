<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex">

    <title>Castelo park aquático - Escanear</title>

    {{-- Carregamento CSS --}}
    <link rel="stylesheet" href="{{ asset('css/leitura-camera.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/css/iziToast.css') }}">

    <script src="{{ asset('libs/js/iziToast.min.js') }}" defer></script>
</head>
<body>
{{-- CSRF gerado pelo blade, necessário nas requisições ajax post pelo jquery --}}
@csrf

{{-- Sons que irão tocar nas verificações de leitura do qrcode --}}
<audio id="som-sucesso" src="recursos/beep-success.mp3"></audio>
<audio id="som-erro" src="recursos/beep-error.wav"></audio>

{{-- Loader que irá aparecer na parte de busca por ingressos --}}
<div id="loader-busca-ingresso">
    <div class="la-ball-clip-rotate-pulse la-sm">
        <div></div>
        <div></div>
    </div>
    <p>Buscando ingresso..</p>
</div>

<header>
    <div id="info-usuario-logado">
        <h5>Olá, {{ $infoUsuario['nomeUsuario'] ?? '(Não informado)' }}.</h5>
        <a title="Sair" href="{{ route('pagina_executar_sair') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
        </a>
    </div>
</header>

<main>
    <div id="container-indicacoes">
        <div class="indicacao">
            <img src="{{ asset('img/circular-number-1.png') }}">
            <h2>Aponte o QR Code para a câmera</h2>
        </div>
        <div class="box-scan border-box-scan">
            <div id="icone-qr-code">
                <img src="{{ asset('img/qr-code.png') }}">
            </div>
        </div>
        <div class="indicacao">
            <img src="{{ asset('img/circular-number-2.png') }}">
            <h2>Aguarde a verificação da leitura</h2>
        </div>
        <div class="indicacao">
            <p>Te avisaremos quando o ingresso for válido ou inválido</p>
        </div>
    </div>
    <div id="container-exibicao-camera">
        <div id="exibicao-camera">
            <video id="qr-video"></video> {{-- Onde será renderizado a câmera --}}
        </div>
        <div id="botoes-interacoes-camera">
            <fieldset>
                <legend>Ações</legend>
                <button onclick="exibirNotificacaoSucesso()">Iniciar câmera</button>
                <button onclick="exibirNotificacaoErro()">Interromper câmera</button>
                <button onclick="buscarPorIngresso(740743)">BuscarIngresso Teste</button>
                {{-- As opções de câmeras serão injetadas via javascript ~> arquivo 'main.js' --}}
                <select id="opcoes-camera">
                    <option value="environment" selected>Câmera traseira (padrão)</option>
                    <option value="user">Câmera frontal</option>
                </select>
            </fieldset>
        </div>
    </div>
</main>

{{-- Utilização do jquery para as requisições ajax --}}
<script src="{{ asset('libs/js/jquery-3.6.0.min.js') }}"></script>

 {{-- Declaração de variáveis url's neste arquivo blade para ficar disponível no contexto javascript (serão usadas no "main.js") --}}
<script>
    var urlBuscarIngresso = "{{ route('buscar_ingresso') }}";
    var idUsuario = "{{ $infoUsuario['idUsuario'] }}";
</script>

{{-- O objeto principal na lib "QrScanner" será utilizado no arquivo "main.js". Então primeiro carrega as libs e depois o arquivo main --}}
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{asset('libs/js/qr-scanner.min.js')}}"></script>
<script> QrScanner.WORKER_PATH = "{{asset('libs/js/qr-scanner-worker.min.js')}}";</script>
</body>
</html>
