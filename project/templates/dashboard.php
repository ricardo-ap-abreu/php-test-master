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
            <h4>Clientes</h4>
            <a href="/adicionar-cliente" class="btn-floating btn-large waves-effect waves-light blue-grey tooltipped" data-position="bottom" data-tooltip="Adicionar Cliente"><i class="material-icons">add</i></a>
        </div>

        <?php if(empty($data['clientes'])): ?>
            <h4>Nenhum cliente cadastrado</h4>
        <?php else: ?>
            <table class="highlight">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Nascimento</th>
                        <th>Telefone</th>
                        <th>Opções</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($data['clientes'] as $cliente): ?>
                        <tr id="cliente-<?= $cliente['id']; ?>">
                            <td><?= $cliente['nome']; ?></td>
                            <td><?= $cliente['nascimento']; ?></td>
                            <td><?= $cliente['telefone']; ?></td>
                            <td>
                                <a href="/enderecos/<?= $cliente['id']; ?>" class="btn-floating btn-small green tooltipped" data-position="bottom" data-tooltip="Gerenciar Endereços"><i class="material-icons">home</i></a>
                                <a href="/editar-cliente/<?= $cliente['id']; ?>" class="btn-floating btn-small blue tooltipped" data-position="bottom" data-tooltip="Editar Cliente"><i class="material-icons">edit</i></a>
                                <a onclick="deletarCliente(<?= $cliente['id']; ?>)" href="#" class="btn-floating btn-small red tooltipped" data-position="bottom" data-tooltip="Deletar Cliente"><i class="material-icons">delete</i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!--JavaScript at end of body for optimized loading-->
<script type="text/javascript" src="../assets/js/materialize.min.js"></script>
<script type="text/javascript" src="../assets/js/jquery-3.4.1.min.js"></script>
<script type="application/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.tooltipped');
        M.Tooltip.init(elems);
    });
    function deletarCliente(id){
        $.ajax({
            url: "/deletar-cliente",
            type: "POST",
            data: { id },
            dataType: "json",
            success: function(data) {
                if(data.erro) {
                    M.toast({html: data.erro});
                    return;
                }
                M.toast({html: 'Cliente deletado com sucesso!'});
                $('#cliente-'+id).fadeOut(300);
            },
        });
    }
</script>
</body>
</html>
