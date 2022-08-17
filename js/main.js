// Vínculos elementos e inicializações
const somSucesso = document.getElementById('som-sucesso');
const somErro = document.getElementById('som-erro');
const video = document.getElementById('qr-video');
const opcoesCamera = document.getElementById('opcoes-camera');
var scanner = null;

// Função responsável por exibir notificação de sucesso
function exibirNotificacaoSucesso(){
    somSucesso.play();

    iziToast.success({
        title: 'QR Code correto!',
        message: 'O ingresso foi validado com sucesso.',
        position: 'topRight',
        timeout: 3000
    });
}

// Função responsável por exibir notificação de erro
function exibirNotificacaoErro(){
    somErro.play();

    iziToast.error({
        title: 'Erro!',
        message: 'O ingresso é inválido.',
        position: 'topRight',
        timeout: 3000
    });
}

// Função responsável por buscar no banco de dados um ingresso válido
function buscarPorIngresso(numeroIngresso){
    $.ajax({
        url: urlBuscarIngresso, // Valor da variável no arquivo "leitura_qrcode.blade.php"
        type: 'post',
        data: {
            _token: $("input[name='_token']").val(),
            numeroQrCode: numeroIngresso
        },
        dataType: 'json',
        beforeSend: function(){
            document.getElementById('loader-busca-ingresso').style.display = 'flex';
        },
        success: function(response) {
            if(response.existeIngresso) {
                exibirNotificacaoSucesso();
                console.log(response.informacoesIngresso);
            } else {
                exibirNotificacaoErro();
            }
        },
        error: function(jqXHR, exception) {
            alert('Desculpe! Houve um problema durante o processamento do app. Consulte o log (F12)')
            console.log(jqXHR.responseText);
        },
        complete: function(){
            document.getElementById('loader-busca-ingresso').style.display = 'none';
        }
    });
}

// Startar a leitura de câmera assim que entrar na aplicação
$(function () {
    // Associação ao objeto QrScanner e callbacks que serão executadas na leitura
    scanner = new QrScanner(video, resultadoObtidoLeitura => acaoAposLeitura(resultadoObtidoLeitura), error => {
        console.log(error);
    });

    // startar a câmera e listar câmeras disponíveis do dispositivo colocando as em um select
    scanner.start().then(() => {
        QrScanner.listCameras(true).then(cameras => cameras.forEach(camera => {
            const option = document.createElement('option');
            option.value = camera.id;
            option.text = camera.label;
            opcoesCamera.add(option);
        }));
    });

    // Setar câmera ativa após selecionar uma câmera no <select>
    opcoesCamera.addEventListener('change', event => {
        scanner.setCamera(event.target.value);
    });
});

/*
Função com objetivo de capturar o resultado lido pela camera para fazer as comparações necessárias de acordo
com as regras de negócio, exibindo os sons correspondentes.
(Essa função é passada como callback na inicialização da lib QrCode)
*/
function acaoAposLeitura(informacaoLida){
    // Desligar a câmera por alguns segundos, até dar um tempo considerável para processar a busca pelo QRCode.
    scanner.stop();

    buscarPorIngresso(informacaoLida);

    setTimeout(function(){
        scanner.start();
    }, 2500);
}
