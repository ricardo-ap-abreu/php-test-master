<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="../../assets/css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="../../assets/css/styles.css"  media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body>

<div class="dashboard">
    <nav>
        <div class="nav-wrapper">
            <div class="container">
                <a href="/dashboard" class="brand-logo">SGC</a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="/perfil">Perfil</a></li>
                    <li><a href="/logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="headDash">
            <h4>Editar Endereco</h4>
            <a href="/enderecos/<?= $data['id_cliente']; ?>" class="btn-floating btn-large waves-effect waves-light blue-grey tooltipped"
               data-position="bottom" data-tooltip="Voltar ao Dashboard">
                <i class="material-icons">arrow_back</i>
            </a>
        </div>

        <div class="row">
            <form class="col s12">
                <div class="row">
                    <div class="input-field col s6">
                        <input id="cep" type="text" name="cep" value="<?= $data['endereco']['cep']; ?>" class="validate">
                        <label for="cep">CEP</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="cidade" type="text" name="cidade" value="<?= $data['endereco']['cidade']; ?>" class="validate">
                        <label for="cidade">Cidade</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s10">
                        <input id="endereco" type="text" name="endereco" value="<?= $data['endereco']['endereco']; ?>" class="validate">
                        <label for="endereco">Endereço</label>
                    </div>
                    <div class="input-field col s2">
                        <input id="numero" type="text" name="numero" value="<?= $data['endereco']['numero']; ?>" class="validate">
                        <label for="numero">Número</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="bairro" type="text" name="bairro" value="<?= $data['endereco']['bairro']; ?>" class="validate">
                        <label for="bairro">Bairro</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="estado" type="text" name="estado" value="<?= $data['endereco']['estado']; ?>" class="validate">
                        <label for="estado">Estado</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <button class="btn waves-effect waves-light" type="submit" name="action">Alterar</button>
                    </div>
                </div>
            </form>
        </div>


    </div>
</div>

<!--JavaScript at end of body for optimized loading-->
<script type="text/javascript" src="../../assets/js/materialize.min.js"></script>
<script type="text/javascript" src="../../assets/js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="../../assets/js/jquery.mask.js"></script>
<script type="application/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.tooltipped');
        M.Tooltip.init(elems);
    });

    $('form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            data: $('form').serializeArray(),
            dataType: "json",
            success: function(data) {
                if(data.erro) {
                    M.toast({html: data.erro});
                    return;
                }
                M.toast({html: 'Endereço alterado com sucesso!'});
                setTimeout(() => { window.location.replace("/enderecos/<?= $data['id_cliente']; ?>"); }, 500);
            },
        });
    });

    $('#cep').mask('00000-000').on('blur', function(){

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $('#endereco').val("...");
                $('#bairro').val("...");
                $('#cidade').val("...");
                $('#estado').val("...");
                M.updateTextFields();

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $('#endereco').val(dados.logradouro);
                        $('#bairro').val(dados.bairro);
                        $('#cidade').val(dados.localidade);
                        $('#estado').val(dados.uf);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        $('form').trigger("reset");
                        M.toast({html: 'CEP não encontrado!'});
                        M.updateTextFields();
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                $('form').trigger("reset");
                M.toast({html: 'Formato de CEP inválido!'});
                M.updateTextFields();
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            $('form').trigger("reset");
            M.updateTextFields();
        }
    });
</script>
</body>
</html>
