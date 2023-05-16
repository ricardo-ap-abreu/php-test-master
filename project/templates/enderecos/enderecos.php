<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="../../assets/css/materialize.min.css" media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="../../assets/css/styles.css" media="screen,projection"/>

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
            <h4>Enderecos</h4>
            <a href="/adicionar-endereco/<?= $data['id_cliente']; ?>" class="btn-floating btn-large waves-effect waves-light blue-grey tooltipped" data-position="bottom" data-tooltip="Adicionar Endereço"><i class="material-icons">add</i></a>
        </div>

        <?php if(empty($data['enderecos'])): ?>
            <h4>Nenhum endereco cadastrado</h4>
        <?php else: ?>
            <table class="highlight">
                <thead>
                    <tr>
                        <th>Endereço</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                        <th>Opções</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($data['enderecos'] as $endereco): ?>
                        <tr id="endereco-<?= $endereco['id']; ?>">
                            <td><?= $endereco['endereco']; ?></td>
                            <td><?= $endereco['cidade']; ?></td>
                            <td><?= $endereco['estado']; ?></td>
                            <td>
                                <a href="/editar-endereco/<?= $data['id_cliente']; ?>/<?= $endereco['id']; ?>" class="btn-floating btn-small blue tooltipped" data-position="bottom" data-tooltip="Editar Endereço"><i class="material-icons">edit</i></a>
                                <a onclick="deletarEndereco(<?= $endereco['id']; ?>)" href="#" class="btn-floating btn-small red tooltipped" data-position="bottom" data-tooltip="Deletar Endereço"><i class="material-icons">delete</i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!--JavaScript at end of body for optimized loading-->
<script type="text/javascript" src="../../assets/js/materialize.min.js"></script>
<script type="text/javascript" src="../../assets/js/jquery-3.4.1.min.js"></script>
<script type="application/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.tooltipped');
        M.Tooltip.init(elems);
    });
    function deletarEndereco(id){
        $.ajax({
            url: "/deletar-endereco",
            type: "POST",
            data: { id },
            dataType: "json",
            success: function(data) {
                if(data.erro) {
                    M.toast({html: data.erro});
                    return;
                }
                M.toast({html: 'Endereço deletado com sucesso!'});
                $('#endereco-'+id).fadeOut(300);
            },
        });
    }
</script>
</body>
</html>
