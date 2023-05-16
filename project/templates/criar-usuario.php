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

<div class="baseLogin">
    <div class="card darken-1">
        <div class="card-content">
            <span class="card-title">Criar Usuário</span>
            <form method="post" class="col s12">
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">account_circle</i>
                        <input id="nome" type="text" class="validate" name="nome" autocomplete="false">
                        <label for="nome">Nome</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">mail</i>
                        <input id="email" type="email" class="validate" name="email" autocomplete="false">
                        <label for="email">E-mail</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">lock</i>
                        <input id="password" type="password" class="validate" name="senha" autocomplete="false">
                        <label for="password">Senha</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">repeat</i>
                        <input id="repassword" type="password" class="validate" name="resenha" autocomplete="false">
                        <label for="repassword">Repetir a Senha</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <button class="btn waves-effect waves-light" type="submit" name="action">Cadastrar</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-action">
            <a href="/">Fazer Login</a>
        </div>
    </div>
</div>

<!--JavaScript at end of body for optimized loading-->
<script type="text/javascript" src="../assets/js/materialize.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery-3.4.1.min.js"></script>
<script type="application/javascript">
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
                window.location.replace("/dashboard");
            },
        });
    })
</script>
</body>
</html>
