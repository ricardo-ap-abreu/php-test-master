<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="../assets/css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="../assets/css/styles.css"  media="screen,projection"/>

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
            <h4>Editar Cliente</h4>
            <a href="/dashboard" class="btn-floating btn-large waves-effect waves-light blue-grey tooltipped" data-position="bottom" data-tooltip="Voltar ao Dashboard">
                <i class="material-icons">arrow_back</i>
            </a>
        </div>

        <div class="row">
            <form class="col s12">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="nome" type="text" name="nome" value="<?= $data['cliente']['nome']; ?>" class="validate">
                        <label for="nome">Nome</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="nascimento" type="date" name="nascimento" value="<?= $data['cliente']['nascimento']; ?>" class="validate">
                        <label for="nascimento">Data de Nascimento</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="telefone" type="text" name="telefone" value="<?= $data['cliente']['telefone']; ?>" class="validate">
                        <label for="telefone">Telefone</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="cpf" type="text" name="cpf" value="<?= $data['cliente']['cpf']; ?>" class="validate">
                        <label for="cpf">CPF</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="rg" type="text" name="rg" value="<?= $data['cliente']['rg']; ?>" class="validate">
                        <label for="rg">RG</label>
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
<script type="text/javascript" src="../assets/js/materialize.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery.mask.js"></script>
<script type="application/javascript">
    var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };

    $('#telefone').mask(SPMaskBehavior, spOptions);
    $('#cpf').mask('000.000.000-00');
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
                M.toast({html: 'Cliente alterado com sucesso!'});
                setTimeout(() => { window.location.replace("/dashboard"); }, 500);
            },
        });
    })
</script>
</body>
</html>
