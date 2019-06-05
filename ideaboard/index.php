<?php
    //faz leitura dos dados na estrutura de json, poderia facilmente ser convertido em banco de dados
    $posts = json_decode(file_get_contents(__DIR__."/assets/files/data.json"));
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- Cabeçalho padrão, utilizando bootstrap e jquery via cdn e um único arquivo de CSS próprio -->
        <title>Quadro de idéias</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/estilo.css"/>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <br>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <h2 class="text-center">Quadro de idéias</h2>
                    <br>
                    <!-- botão para nova ideia -->
                    <button onclick="novaIdeia()" class="btn btn-primary">Nova idéia</button>
                    <!-- opção de ordenar as ideias que por padrão estarão em ordem decrescente -->
                    <label>Ordenar: </label>
                    <select class="form-control ordem">
                        <option value="desc" selected>Data decrescente</option>
                        <option value="asc">Data crescente</option>
                    </select>
                    <!-- mensagem que exibe a informação que foi salvo os dados -->
                    <div class="salvo"><p class="mensagem">Salvo</p></div>
                    <br><br>
                </div>
                <div class="col-12 col-lg-8">
                    <form id="form-posts" method="post" action="update.php">
                        <div class="row" id="ideias">
                            <?php
                                //verifica se tem ideias, se tiver faz um loop colocand o post-it de cada uma
                                if (!empty($posts)) {
                                    foreach ($posts as $post) {
                            ?>
                                        <div class="post col-12 col-sm-6 col-lg-4">
                                            <a href="#" onclick="removeIdeia(this)" class="fechar">x</a>
                                            <input type="text" name="titulo[]" class="titulo" placeholder="Insira um título" value="<?= $post->titulo ?>">
                                            <textarea name="corpo[]" class="corpo" placeholder="Insira uma idéia"><?= $post->corpo ?></textarea>
                                            <input type="hidden" name="data[]" value="<?= $post->data ?>">
                                        </div>
                            <?php
                                    }
                                //se não tem ideias, escreve na tela
                                } else {
                                    echo "<p id='vazio' class='col-12 text-center'><i>Sem idéias até o momento... :/</i></p>";
                                }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            //adiciona card de nova ideia
            function novaIdeia() {
                if ($('#vazio').length) {
                    $('#vazio').remove();
                }
                var date = new Date();
                var data = date.getFullYear()+"-"+formatDate(date.getMonth()+1)+"-"+formatDate(date.getDate())+" "+formatDate(date.getHours())+":"+formatDate(date.getMinutes())+":"+formatDate(date.getSeconds());
                $("#ideias").prepend('<div class="post col-12 col-sm-6 col-md-4"><input type="text" name="titulo[]" class="titulo" placeholder="Insira um título" value=""><textarea name="corpo[]" class="corpo" placeholder="Insira uma idéia"></textarea><input type="hidden" name="data[]" value="'+data+'"></div>');

                //atualiza acompanhamento do jquery em novos elementos da pagina
                $('input').change(function() {
                  $('#form-posts').submit();
                });
                $('textarea').change(function() {
                  $('#form-posts').submit();
                });
            }

            //remove ideia
            function removeIdeia(el) {
                //faz o efeito de fadeout e no callback remove o elemento e envia o form
                $(el).parent().fadeOut(300, function(){
                    $(this).remove();
                    $('#form-posts').submit();
                });
            }

            //formata datas para padrao YYYY-MM-DD HH:II:SS
            function formatDate(date) {
                if (date < 10) {
                    return "0"+date;
                } else {
                    return date;
                }
            }

            $(document).ready(function() {
                //acompanhamento do jquery para salvar automaticamente com mudanças
                $('input').change(function() {
                  $('#form-posts').submit();
                });
                $('textarea').change(function() {
                  $('#form-posts').submit();
                });

                $('select').change(function() {
                  $('#ideias').children().each(function(i,div){$('#ideias').prepend(div)})
                });

                //envia os dados atualizados para o backend que salva no arquivo
                $('#form-posts').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url : $(this).attr('action') || window.location.pathname,
                        type: "POST",
                        data: $(this).serialize(),
                        success: function (data) {
                            $(".mensagem").fadeIn(1000, function(){
                              $(".mensagem").fadeOut(500);
                            });
                        },
                        error: function (jXHR, textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                    });
                });
            });
        </script>
    </body>
</html>